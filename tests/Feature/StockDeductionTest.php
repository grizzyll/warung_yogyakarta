<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockDeductionTest extends TestCase
{
    use RefreshDatabase;

    private function menu(float $stock): array
    {
        $ing = Ingredient::create([
            'name' => 'Ayam Mentah',
            'unit' => 'kg',
            'stock_alert' => 1,
            'current_stock' => $stock,
        ]);

        $product = Product::forceCreate([
            'name' => 'Ayam Goreng',
            'category' => 'Makanan',
            'price' => 25000,
            'is_available' => true,
        ]);

        Recipe::forceCreate([
            'product_id' => $product->id,
            'ingredient_id' => $ing->id,
            'amount_needed' => 1,
        ]);

        return [$product, $ing];
    }

    private function kasir(): User
    {
        return User::factory()->create(['role' => 'cashier']);
    }

    public function test_harga_dihitung_server_bukan_dari_browser(): void
    {
        [$product] = $this->menu(10);

        $this->actingAs($this->kasir())->postJson('/pos/store', [
            'cart' => [['id' => $product->id, 'qty' => 2, 'price' => 1]],
            'total_price' => 1,
            'table_number' => '1',
        ])->assertOk();

        $this->assertEquals(50000, Order::first()->total_price);
    }

    public function test_stok_kurang_menolak_pesanan_dan_stok_tidak_berubah(): void
    {
        [$product, $ing] = $this->menu(1);

        $this->actingAs($this->kasir())->postJson('/pos/store', [
            'cart' => [['id' => $product->id, 'qty' => 2]],
        ])->assertStatus(422);

        $this->assertDatabaseCount('orders', 0);
        $this->assertEquals(1, $ing->fresh()->current_stock);
    }

    public function test_nomor_antrian_naik_per_pesanan(): void
    {
        [$product] = $this->menu(10);
        $user = $this->kasir();

        foreach ([1, 2] as $expected) {
            $this->actingAs($user)->postJson('/pos/store', [
                'cart' => [['id' => $product->id, 'qty' => 1]],
            ])->assertOk()->assertJsonPath('queue_number', $expected);
        }
    }
}