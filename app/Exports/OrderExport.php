<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // <--- Biar Lebar Otomatis
use Maatwebsite\Excel\Concerns\WithStyles;     // <--- Biar Bisa Bold
use Maatwebsite\Excel\Concerns\WithColumnFormatting; // <--- Biar Format Rupiah
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class OrderExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    public function collection()
    {
        return Order::where('payment_status', 'paid')
            ->with('orderItems.product')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function headings(): array
    {
        // Header dibuat Huruf Besar Semua biar tegas
        return ['TANGGAL', 'NO. NOTA', 'TIPE', 'TOTAL (RP)', 'DETAIL MENU'];
    }

    public function map($order): array
    {
        $details = $order->orderItems->map(function($item) {
            return $item->product->name . ' (x' . $item->quantity . ')';
        })->implode(', ');

        return [
            $order->created_at->format('d/m/Y H:i'),
            $order->order_number,
            strtoupper($order->order_type),
            $order->total_price, // Biarkan angka mentah, nanti diformat oleh Excel
            $details
        ];
    }

    // 1. STYLE: Bikin Header (Baris 1) jadi BOLD
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    // 2. FORMAT: Kolom D (Total Rupiah) dikasih titik/koma
    public function columnFormats(): array
    {
        return [
            'D' => '#,##0', // Format angka standar Excel (misal: 1.000.000)
        ];
    }
}