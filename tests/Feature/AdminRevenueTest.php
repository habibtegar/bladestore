<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRevenueTest extends TestCase
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
            'name' => 'Budi Pelanggan',
            'email' => 'budi@example.test',
            'role' => 'customer',
        ]);
    }

    public function test_guest_cannot_access_revenue_page(): void
    {
        $response = $this->get(route('admin.revenue.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_revenue_page(): void
    {
        $response = $this->actingAs($this->customer)->get(route('admin.revenue.index'));
        $response->assertForbidden();
    }

    public function test_admin_can_access_revenue_page(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.revenue.index'));
        $response->assertStatus(200);
        $response->assertSee('Omset &amp; Keuangan', false);
        $response->assertSee('Tren Pendapatan Harian');
        $response->assertSee('Perbandingan Omset Bulanan');
    }

    public function test_revenue_only_counts_verified_paid_orders(): void
    {
        // 1. Paid order (processing, non-COD) -> Rp 250.000
        Order::create([
            'order_code' => 'ORD-PAID-01',
            'user_id' => $this->customer->id,
            'total_price' => 250000,
            'payment_method' => 'QRIS Instant',
            'status' => 'processing',
            'shipping_address' => 'Jl. Merdeka No. 1',
            'customer_phone' => '081234567890',
        ]);

        // 2. Paid order (completed) -> Rp 350.000
        Order::create([
            'order_code' => 'ORD-PAID-02',
            'user_id' => $this->customer->id,
            'total_price' => 350000,
            'payment_method' => 'Transfer Bank Manual',
            'status' => 'completed',
            'shipping_address' => 'Jl. Sudirman No. 2',
            'customer_phone' => '081234567891',
        ]);

        // 3. Unpaid order (pending) -> Rp 1.000.000 (MUST NOT BE COUNTED)
        Order::create([
            'order_code' => 'ORD-UNPAID-01',
            'user_id' => $this->customer->id,
            'total_price' => 1000000,
            'payment_method' => 'Midtrans Snap',
            'status' => 'pending',
            'shipping_address' => 'Jl. Thamrin No. 3',
            'customer_phone' => '081234567892',
        ]);

        // 4. Cancelled order -> Rp 500.000 (MUST NOT BE COUNTED)
        Order::create([
            'order_code' => 'ORD-CANCEL-01',
            'user_id' => $this->customer->id,
            'total_price' => 500000,
            'payment_method' => 'QRIS Instant',
            'status' => 'cancelled',
            'shipping_address' => 'Jl. Gatot Subroto No. 4',
            'customer_phone' => '081234567893',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.revenue.index', ['period' => 'all']));

        $response->assertStatus(200);

        // Expected total = 250.000 + 350.000 = 600.000
        $response->assertSee('Rp 600.000');

        // Unpaid total of 1.000.000 or combined total of 2.100.000 must NOT be the omset
        $response->assertDontSee('Rp 2.100.000');

        // Transactions table shows paid orders
        $response->assertSee('ORD-PAID-01');
        $response->assertSee('ORD-PAID-02');
        $response->assertDontSee('ORD-UNPAID-01');
        $response->assertDontSee('ORD-CANCEL-01');
    }

    public function test_cod_orders_only_count_when_completed(): void
    {
        // COD order processing (not delivered, cash not collected yet) -> MUST NOT BE COUNTED
        Order::create([
            'order_code' => 'ORD-COD-PACK',
            'user_id' => $this->customer->id,
            'total_price' => 175000,
            'payment_method' => 'Cash on Delivery (COD)',
            'status' => 'processing',
            'shipping_address' => 'Jl. Melati No. 5',
            'customer_phone' => '081234567894',
        ]);

        // COD order completed (delivered & paid in cash) -> MUST BE COUNTED
        Order::create([
            'order_code' => 'ORD-COD-DONE',
            'user_id' => $this->customer->id,
            'total_price' => 225000,
            'payment_method' => 'Cash on Delivery (COD)',
            'status' => 'completed',
            'shipping_address' => 'Jl. Mawar No. 6',
            'customer_phone' => '081234567895',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.revenue.index', ['period' => 'all']));

        $response->assertStatus(200);

        // Revenue should only include the completed COD (225.000)
        $response->assertSee('Rp 225.000');
        $response->assertDontSee('Rp 400.000');

        $response->assertSee('ORD-COD-DONE');
        $response->assertDontSee('ORD-COD-PACK');
    }

    public function test_revenue_filtering_by_payment_method(): void
    {
        Order::create([
            'order_code' => 'ORD-FILTER-QRIS',
            'user_id' => $this->customer->id,
            'total_price' => 120000,
            'payment_method' => 'QRIS Instant',
            'status' => 'completed',
            'shipping_address' => 'Jl. Anggrek No. 7',
            'customer_phone' => '081234567896',
        ]);

        Order::create([
            'order_code' => 'ORD-FILTER-BANK',
            'user_id' => $this->customer->id,
            'total_price' => 310000,
            'payment_method' => 'Transfer Bank Manual',
            'status' => 'completed',
            'shipping_address' => 'Jl. Dahlia No. 8',
            'customer_phone' => '081234567897',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.revenue.index', [
            'period' => 'all',
            'payment_method' => 'QRIS Instant',
        ]));

        $response->assertStatus(200);
        $response->assertSee('ORD-FILTER-QRIS');
        $response->assertDontSee('ORD-FILTER-BANK');
        $response->assertSee('Rp 120.000');
    }

    public function test_revenue_filtering_by_search(): void
    {
        Order::create([
            'order_code' => 'ORD-SEARCH-TARGET',
            'user_id' => $this->customer->id,
            'total_price' => 99000,
            'payment_method' => 'QRIS Instant',
            'status' => 'completed',
            'shipping_address' => 'Jl. Kemuning No. 9',
            'customer_phone' => '081234567898',
        ]);

        Order::create([
            'order_code' => 'ORD-SEARCH-OTHER',
            'user_id' => $this->customer->id,
            'total_price' => 199000,
            'payment_method' => 'QRIS Instant',
            'status' => 'completed',
            'shipping_address' => 'Jl. Teratai No. 10',
            'customer_phone' => '081234567899',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.revenue.index', [
            'period' => 'all',
            'search' => 'SEARCH-TARGET',
        ]));

        $response->assertStatus(200);
        $response->assertSee('ORD-SEARCH-TARGET');
        $response->assertDontSee('ORD-SEARCH-OTHER');
    }

    public function test_revenue_export_csv(): void
    {
        Order::create([
            'order_code' => 'ORD-EXPORT-001',
            'user_id' => $this->customer->id,
            'total_price' => 450000,
            'payment_method' => 'Midtrans Snap',
            'status' => 'completed',
            'shipping_address' => 'Jl. Cendana No. 1',
            'customer_phone' => '08111222333',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.revenue.export', ['period' => 'all']));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $content = $response->streamedContent();

        $this->assertStringContainsString('Tanggal Transaksi', $content);
        $this->assertStringContainsString('Order ID', $content);
        $this->assertStringContainsString('ORD-EXPORT-001', $content);
        $this->assertStringContainsString('450000', $content);
    }

    public function test_empty_state_when_no_revenue_recorded(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.revenue.index', ['period' => 'today']));

        $response->assertStatus(200);
        $response->assertSee('Rp 0');
        $response->assertSee('Tidak Ada Riwayat Transaksi');
    }
}
