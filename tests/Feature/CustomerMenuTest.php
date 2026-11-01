<?php

namespace Tests\Feature;

use App\Models\DiningTable;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CustomerMenuTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_token_valid_menampilkan_menu_tanpa_login(): void
    {
        $table = DiningTable::create(['number' => '7']);
        Product::forceCreate([
            'name' => 'Ayam Bakar', 'category' => 'Makanan',
            'price' => 30000, 'is_available' => true,
        ]);

        $this->get("/m/{$table->token}")
            ->assertOk()
            ->assertSee('Ayam Bakar')
            ->assertViewHas('table', fn ($t) => $t->number === '7');
    }

    public function test_token_salah_menghasilkan_404(): void
    {
        $this->get('/m/' . Str::uuid())->assertNotFound();
    }

    public function test_meja_nonaktif_menghasilkan_404(): void
    {
        $table = DiningTable::create(['number' => '8', 'is_active' => false]);

        $this->get("/m/{$table->token}")->assertNotFound();
    }

    public function test_menu_tidak_tersedia_tidak_ditampilkan(): void
    {
        $table = DiningTable::create(['number' => '9']);
        Product::forceCreate([
            'name' => 'Menu Hantu', 'category' => 'Makanan',
            'price' => 10000, 'is_available' => false,
        ]);

        $this->get("/m/{$table->token}")->assertDontSee('Menu Hantu');
    }
}