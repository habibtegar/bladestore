<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_products(): void
    {
        $response = $this->get(route('admin.products.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_admin_can_view_product_catalog_and_create_form(): void
    {
        $admin = User::factory()->admin()->create();
        Product::factory()->create([
            'name' => 'Kemeja Batik Modern',
            'price' => 250000,
            'stock' => 15,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.products.index'));
        $response->assertStatus(200);
        $response->assertSee('Kemeja Batik Modern');
        $response->assertSee('Rp 250.000');
        $response->assertSee('Tambah Produk Baru');

        $createResponse = $this->actingAs($admin)->get(route('admin.products.create'));
        $createResponse->assertStatus(200);
        $createResponse->assertSee('Formulir Tambah Produk Baru');
        $createResponse->assertSee('Persentase Diskon (%)');
    }

    public function test_customer_cannot_access_admin_products_and_gets_403(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->get(route('admin.products.index'));
        $response->assertStatus(403);

        $createResponse = $this->actingAs($customer)->get(route('admin.products.create'));
        $createResponse->assertStatus(403);
    }

    public function test_customer_cannot_store_or_delete_product_and_gets_403(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $product = Product::factory()->create();

        $postResponse = $this->actingAs($customer)->post(route('admin.products.store'), [
            'name' => 'Produk Ilegal',
            'price' => 100000,
            'stock' => 5,
        ]);
        $postResponse->assertStatus(403);

        $deleteResponse = $this->actingAs($customer)->delete(route('admin.products.destroy', $product->id));
        $deleteResponse->assertStatus(403);
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    public function test_admin_can_store_new_product(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'name' => 'TWS Bluetooth Earbuds ANC',
            'category' => 'Elektronik',
            'price' => 249000,
            'discount_percentage' => 25,
            'stock' => 25,
            'image' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=600&q=80',
            'description' => 'Earbuds nirkabel dengan active noise cancellation.',
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'name' => 'TWS Bluetooth Earbuds ANC',
            'category' => 'Elektronik',
            'price' => 249000,
            'discount_percentage' => 25,
            'stock' => 25,
        ]);
    }

    public function test_admin_can_update_product(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create([
            'name' => 'Jaket Parka Musim Dingin',
            'category' => 'Fashion',
            'price' => 350000,
            'discount_percentage' => 0,
            'stock' => 5,
        ]);

        $editResponse = $this->actingAs($admin)->get(route('admin.products.edit', $product->id));
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Jaket Parka Musim Dingin');
        $editResponse->assertSee('Persentase Diskon (%)');

        $updateResponse = $this->actingAs($admin)->put(route('admin.products.update', $product->id), [
            'name' => 'Jaket Parka Musim Dingin Updated',
            'category' => 'Fashion',
            'price' => 399000,
            'discount_percentage' => 15,
            'stock' => 12,
            'description' => 'Versi pembaruan dengan lapisan bulu halus.',
        ]);

        $updateResponse->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Jaket Parka Musim Dingin Updated',
            'category' => 'Fashion',
            'price' => 399000,
            'discount_percentage' => 15,
            'stock' => 12,
        ]);
    }

    public function test_admin_can_delete_product(): void
    {
        $admin = User::factory()->admin()->create();
        $product = Product::factory()->create([
            'name' => 'Produk Akan Dihapus',
            'price' => 50000,
            'stock' => 2,
        ]);

        $deleteResponse = $this->actingAs($admin)->delete(route('admin.products.destroy', $product->id));
        $deleteResponse->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }
}
