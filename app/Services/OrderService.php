<?php

namespace App\Services;

use App\Models\DiningTable;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(private StockService $stock) {}

    /**
     * @param array $items  [['id' => 1, 'qty' => 2, 'note' => 'pedas'], ...]
     * @param array $meta   customer_name, order_type, table_number, payment_method
     * @param string $source cashier | qr | online
     */
    public function create(array $items, array $meta, string $source, ?DiningTable $table = null): Order
    {
        // Kalau dua pesanan berebut nomor antrian yang sama, yang kalah diulang
        for ($attempt = 1; ; $attempt++) {
            try {
                return DB::transaction(fn () => $this->build($items, $meta, $source, $table));
            } catch (UniqueConstraintViolationException $e) {
                if ($attempt >= 3) {
                    throw $e;
                }
            }
        }
    }

    private function build(array $items, array $meta, string $source, ?DiningTable $table): Order
    {
        // 1. Harga SELALU dari database, bukan dari browser
        $products = Product::with('recipes')
            ->whereIn('id', collect($items)->pluck('id'))
            ->where('is_available', true)
            ->get()
            ->keyBy('id');

        $lines = collect($items)->map(function ($i) use ($products) {
            $product = $products->get($i['id']);
            if (!$product) {
                throw new \DomainException('Ada menu yang sudah tidak tersedia. Muat ulang halaman.');
            }
            return [
                'product' => $product,
                'qty'     => (int) $i['qty'],
                'note'    => $i['note'] ?? null,
            ];
        });

        $total = $lines->sum(fn ($l) => $l['product']->price * $l['qty']);

        // 2. Cek + potong stok (dikunci)
        $this->stock->deduct($lines);

        // 3. Nomor antrian harian
        $today = today();
        $queue = (int) Order::where('queue_date', $today->toDateString())->max('queue_number') + 1;

        // 4. Status awal
        $orderType     = $meta['order_type'] ?? 'dine_in';
        $needsApproval = $orderType === 'catering' || $total >= config('restaurant.approval_threshold');

        $order = Order::create([
            'order_number'    => 'ORD-' . $today->format('Ymd') . '-' . str_pad($queue, 3, '0', STR_PAD_LEFT),
            'queue_date'      => $today->toDateString(),
            'queue_number'    => $queue,
            'source'          => $source,
            'dining_table_id' => $table?->id,
            'table_number'    => $table?->number ?? ($meta['table_number'] ?? null),
            'customer_name'   => $meta['customer_name'] ?? 'Pelanggan Umum',
            'order_type'      => $orderType,
            'status'          => $needsApproval ? 'waiting_approval' : 'pending',
            'payment_status'  => $source === 'cashier' ? 'paid' : 'unpaid',
            'payment_method'  => $source === 'cashier' ? ($meta['payment_method'] ?? 'cash') : null,
            'total_price'     => $total,
        ]);

        foreach ($lines as $l) {
            $order->orderItems()->create([
                'product_id' => $l['product']->id,
                'quantity'   => $l['qty'],
                'price'      => $l['product']->price,
                'note'       => $l['note'],
            ]);
        }

        if ($table && $orderType === 'dine_in') {
            $table->update(['status' => 'occupied']);
        }

        return $order;
    }
}