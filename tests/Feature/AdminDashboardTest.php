<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Admin BladeStore',
            'email' => 'admin@bladestore.test',
            'role' => 'admin',
        ]);

        $this->customer = User::factory()->create([
            'name' => 'Budi Customer',
            'email' => 'budi@customer.test',
            'role' => 'customer',
        ]);
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }

    public function test_regular_customer_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->customer)->get(route('admin.dashboard'));
        $response->assertForbidden();
    }

    public function test_admin_can_access_dashboard_with_all_metrics(): void
    {
        // 1. Paid Order
        Order::create([
            'order_code' => 'ORD-DASH-PAID1',
            'user_id' => $this->customer->id,
            'total_price' => 200000,
            'payment_method' => 'QRIS Instant',
            'status' => 'processing',
            'shipping_address' => 'Jl. Merdeka No. 10',
            'customer_phone' => '081234567890',
        ]);

        // 2. Pending Order
        Order::create([
            'order_code' => 'ORD-DASH-PENDING',
            'user_id' => $this->customer->id,
            'total_price' => 150000,
            'payment_method' => 'Transfer Bank Manual',
            'status' => 'pending',
            'shipping_address' => 'Jl. Sudirman No. 20',
            'customer_phone' => '081234567891',
        ]);

        // 3. Products
        Product::factory()->create([
            'name' => 'Keyboard Gaming RGB',
            'stock' => 2,
        ]);

        Product::factory()->create([
            'name' => 'Mouse Wireless Ergonomic',
            'stock' => 25,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard Utama');
        $response->assertSee('Total Omset');
        $response->assertSee('Rp 200.000');
        $response->assertDontSee('Rp 350.000'); // Unpaid order must not be in omset

        // Recent Orders
        $response->assertSee('ORD-DASH-PAID1');
        $response->assertSee('ORD-DASH-PENDING');

        // Low stock alerts
        $response->assertSee('Keyboard Gaming RGB');
        $response->assertSee('Sisa 2');
    }

    public function test_admin_root_redirects_to_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin');
        $response->assertRedirect('/admin/dashboard');
    }

    public function test_admin_login_redirects_to_dashboard(): void
    {
        $admin = User::factory()->create([
            'email' => 'superadmin@bladestore.test',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'superadmin@bladestore.test',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
    }
}
