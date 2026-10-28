<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_orders(): void
    {
        $response = $this->get(route('admin.orders.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_all_orders_sorted_by_latest(): void
    {
        $user1 = User::factory()->create(['name' => 'Budi Santoso']);
        $user2 = User::factory()->create(['name' => 'Siti Nurhaliza']);

        $olderOrder = Order::create([
            'order_code' => 'ORD-20261026-OLDER1',
            'user_id' => $user1->id,
            'total_price' => 150000,
            'payment_method' => 'Transfer Bank Manual',
            'status' => 'pending',
            'shipping_address' => 'Jl. Mawar No. 10',
            'customer_phone' => '081234567890',
        ]);
        $olderOrder->created_at = now()->subHours(2);
        $olderOrder->saveQuietly();

        $newerOrder = Order::create([
            'order_code' => 'ORD-20261026-NEWER2',
            'user_id' => $user2->id,
            'total_price' => 300000,
            'payment_method' => 'Cash on Delivery (COD)',
            'status' => 'processing',
            'shipping_address' => 'Jl. Melati No. 20',
            'customer_phone' => '089876543210',
            'created_at' => now(),
        ]);

        $admin = User::factory()->admin()->create(['name' => 'Admin User']);

        $response = $this->actingAs($admin)->get(route('admin.orders.index'));

        $response->assertStatus(200);
        $response->assertSee('ORD-20261026-NEWER2');
        $response->assertSee('ORD-20261026-OLDER1');
        $response->assertSee('Budi Santoso');
        $response->assertSee('Siti Nurhaliza');
        $response->assertSee('Rp 300.000');
        $response->assertSee('Rp 150.000');

        // Verify sorting by latest
        $ordersInView = $response->viewData('orders');
        $this->assertEquals($newerOrder->id, $ordersInView->first()->id);
    }

    public function test_customer_cannot_access_admin_orders_and_gets_403(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->get(route('admin.orders.index'));

        $response->assertStatus(403);
    }

    public function test_customer_cannot_view_admin_order_detail_and_gets_403(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $order = Order::create([
            'order_code' => 'ORD-20261026-SEC',
            'user_id' => $customer->id,
            'total_price' => 100000,
            'payment_method' => 'Transfer Bank Manual',
            'status' => 'pending',
            'shipping_address' => 'Jl. Test',
            'customer_phone' => '08123456789',
        ]);

        $response = $this->actingAs($customer)->get(route('admin.orders.show', $order->id));

        $response->assertStatus(403);
    }

    public function test_customer_cannot_update_order_status_and_gets_403(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $order = Order::create([
            'order_code' => 'ORD-20261026-PATCH',
            'user_id' => $customer->id,
            'total_price' => 100000,
            'payment_method' => 'Transfer Bank Manual',
            'status' => 'pending',
            'shipping_address' => 'Jl. Test',
            'customer_phone' => '08123456789',
        ]);

        $response = $this->actingAs($customer)->patch(route('admin.orders.update-status', $order->id), [
            'status' => 'completed',
        ]);

        $response->assertStatus(403);
        $this->assertEquals('pending', $order->fresh()->status);
    }

    public function test_customer_navbar_does_not_display_admin_menu(): void
    {
        $customer = User::factory()->create(['role' => 'customer', 'name' => 'Pelanggan Setia']);

        $response = $this->actingAs($customer)->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertDontSee('Panel Administrator');
        $response->assertDontSee('Admin Pesanan');
        $response->assertDontSee(route('admin.orders.index'));
        $response->assertDontSee(route('admin.products.index'));
    }

    public function test_admin_navbar_displays_admin_menu(): void
    {
        $admin = User::factory()->admin()->create(['name' => 'Super Admin']);

        $response = $this->actingAs($admin)->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee('Panel Administrator');
        $response->assertSee(route('admin.orders.index'));
    }

    public function test_customer_cannot_view_other_customers_order(): void
    {
        $customer1 = User::factory()->create(['role' => 'customer']);
        $customer2 = User::factory()->create(['role' => 'customer']);

        $order1 = Order::create([
            'order_code' => 'ORD-CUST1',
            'user_id' => $customer1->id,
            'total_price' => 100000,
            'payment_method' => 'Transfer Bank Manual',
            'status' => 'pending',
            'shipping_address' => 'Jl. Test',
            'customer_phone' => '08123456789',
        ]);

        // Customer 2 tries to access Customer 1's order detail
        $response = $this->actingAs($customer2)->get(route('orders.show', $order1->id));
        $response->assertStatus(403);

        // Customer 1 can access own order
        $responseOwn = $this->actingAs($customer1)->get(route('orders.show', $order1->id));
        $responseOwn->assertStatus(200);
    }

    public function test_authenticated_user_can_view_order_details_with_items_and_products(): void
    {
        $customer = User::factory()->create(['name' => 'Dewi Lestari']);
        $product = Product::factory()->create([
            'name' => 'Sepatu Sneakers Pria',
            'price' => 250000,
            'stock' => 15,
        ]);

        $order = Order::create([
            'order_code' => 'ORD-20261026-DETAIL',
            'user_id' => $customer->id,
            'total_price' => 500000,
            'payment_method' => 'Transfer Bank Manual',
            'status' => 'pending',
            'shipping_address' => 'Jl. Asia Afrika No. 8, Bandung',
            'customer_phone' => '081122334455',
            'notes' => 'Tolong kirim dengan bubble wrap tebal',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 250000,
            'subtotal' => 500000,
        ]);

        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.orders.show', $order->id));

        $response->assertStatus(200);
        $response->assertSee('ORD-20261026-DETAIL');
        $response->assertSee('Sepatu Sneakers Pria');
        $response->assertSee('Dewi Lestari');
        $response->assertSee('081122334455');
        $response->assertSee('Jl. Asia Afrika No. 8, Bandung');
        $response->assertSee('Tolong kirim dengan bubble wrap tebal');
        $response->assertSee('Simpan Status');
    }

    public function test_admin_can_update_order_status(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'order_code' => 'ORD-20261026-STATUS',
            'user_id' => $user->id,
            'total_price' => 100000,
            'payment_method' => 'Transfer Bank Manual',
            'status' => 'pending',
            'shipping_address' => 'Jl. Sudirman',
            'customer_phone' => '08123456789',
        ]);

        $admin = User::factory()->admin()->create();

        // Update to processing
        $response1 = $this->actingAs($admin)->patch(route('admin.orders.update-status', $order->id), [
            'status' => 'processing',
        ]);
        $response1->assertRedirect();
        $this->assertEquals('processing', $order->fresh()->status);

        // Update to completed
        $response2 = $this->actingAs($admin)->patch(route('admin.orders.update-status', $order->id), [
            'status' => 'completed',
        ]);
        $response2->assertRedirect();
        $this->assertEquals('completed', $order->fresh()->status);

        // Update to cancelled
        $response3 = $this->actingAs($admin)->patch(route('admin.orders.update-status', $order->id), [
            'status' => 'cancelled',
        ]);
        $response3->assertRedirect();
        $this->assertEquals('cancelled', $order->fresh()->status);
    }

    public function test_updating_with_invalid_status_fails_validation(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'order_code' => 'ORD-20261026-INVALID',
            'user_id' => $user->id,
            'total_price' => 100000,
            'payment_method' => 'Transfer Bank Manual',
            'status' => 'pending',
            'shipping_address' => 'Jl. Sudirman',
            'customer_phone' => '08123456789',
        ]);

        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->patch(route('admin.orders.update-status', $order->id), [
            'status' => 'invalid_status_value',
        ]);

        $response->assertSessionHasErrors('status');
        $this->assertEquals('pending', $order->fresh()->status);
    }
}
