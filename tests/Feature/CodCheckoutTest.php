<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CodCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_cart_page_renders_only_midtrans_and_cod_payment_options(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);

        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('cart.index'));

        $response->assertStatus(200);
        $response->assertSee('Midtrans Snap');
        $response->assertSee('Pembayaran Online');
        $response->assertSee('Cash on Delivery (COD)');
        $response->assertSee('COD (Bayar di Tempat)');
        $response->assertDontSee('Transfer Bank Manual');
    }

    public function test_checkout_index_page_renders_only_midtrans_and_cod_payment_options(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);

        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('checkout.index'));

        $response->assertStatus(200);
        $response->assertSee('Midtrans Snap');
        $response->assertSee('Pembayaran Online');
        $response->assertSee('Cash on Delivery (COD)');
        $response->assertSee('COD (Bayar di Tempat)');
        $response->assertDontSee('Transfer Bank Manual');
    }

    public function test_checkout_can_process_cod_via_ajax_without_calling_midtrans(): void
    {
        Http::fake();

        $user = User::factory()->create();
        $product = Product::factory()->create([
            'price' => 150000,
            'discount_percentage' => 0,
            'stock' => 10,
        ]);

        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)->postJson(route('checkout.snap-token'), [
            'customer_phone' => '081234567890',
            'shipping_address' => 'Jl. Kebon Sirih No. 10, Jakarta Pusat',
            'payment_method' => 'Cash on Delivery (COD)',
            'notes' => 'Tolong telepon sebelum antar paket',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'is_cod' => true,
            'snap_token' => null,
        ]);

        // Verifikasi tidak ada panggilan jaringan ke Midtrans API
        Http::assertNothingSent();

        // Verifikasi pesanan tersimpan di database dengan status pending (menunggu diproses), payment_status unpaid, dan tanpa snap_token
        $order = Order::where('user_id', $user->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals('pending', $order->status);
        $this->assertEquals('unpaid', $order->payment_status);
        $this->assertEquals('Menunggu Diproses', $order->status_label);
        $this->assertEquals('Belum Dibayar (COD)', $order->payment_status_label);
        $this->assertNull($order->tracking_number);
        $this->assertEquals('Cash on Delivery (COD)', $order->payment_method);
        $this->assertNull($order->snap_token);
        $this->assertEquals(300000, (int) $order->total_price);

        // Keranjang belanja dibersihkan
        $this->assertDatabaseMissing('carts', ['user_id' => $user->id]);

        // Stok produk berkurang dari 10 menjadi 8
        $this->assertEquals(8, $product->fresh()->stock);

        // Verifikasi status pesanan vs status pembayaran
        $this->assertTrue($order->isCod());
        $this->assertFalse($order->canBePaid());
        $this->assertFalse($order->isPaid()); // Belum lunas saat checkout!
    }

    public function test_checkout_can_process_midtrans_via_ajax_calling_midtrans(): void
    {
        Http::fake([
            config('midtrans.api_url') => Http::response([
                'token' => 'mock-midtrans-snap-token-999',
                'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/mock-midtrans-snap-token-999',
            ], 201),
        ]);

        $user = User::factory()->create();
        $product = Product::factory()->create([
            'price' => 250000,
            'discount_percentage' => 0,
            'stock' => 5,
        ]);

        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->postJson(route('checkout.snap-token'), [
            'customer_phone' => '081234567890',
            'shipping_address' => 'Jl. Sudirman Kav. 21, Jakarta Selatan',
            'payment_method' => 'Midtrans Snap',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'is_cod' => false,
            'snap_token' => 'mock-midtrans-snap-token-999',
        ]);

        // Verifikasi Midtrans API dipanggil 1 kali
        Http::assertSentCount(1);

        $order = Order::where('user_id', $user->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals('pending', $order->status);
        $this->assertEquals('Midtrans Snap', $order->payment_method);
        $this->assertEquals('mock-midtrans-snap-token-999', $order->snap_token);

        // Verifikasi helper method
        $this->assertFalse($order->isCod());
        $this->assertTrue($order->canBePaid());
        $this->assertFalse($order->isPaid());
    }

    public function test_cod_order_is_not_considered_paid_until_marked_completed(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'order_code' => 'ORD-COD-LIFECYCLE',
            'user_id' => $user->id,
            'total_price' => 175000,
            'payment_method' => 'Cash on Delivery (COD)',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'shipping_address' => 'Jl. Melati No. 8',
            'customer_phone' => '081234567899',
        ]);

        // Status pending (menunggu diproses) -> Belum Lunas
        $this->assertFalse($order->isPaid());
        $this->assertEquals('Belum Dibayar (COD)', $order->payment_status_label);
        $this->assertEquals(0, Order::paid()->where('id', $order->id)->count());

        // Status processing (dikemas) -> Belum Lunas
        $order->update(['status' => 'processing']);
        $this->assertFalse($order->fresh()->isPaid());
        $this->assertEquals(0, Order::paid()->where('id', $order->id)->count());

        // Status shipped (dikirim) -> Masih Belum Lunas
        $order->update(['status' => 'shipped']);
        $this->assertFalse($order->fresh()->isPaid());
        $this->assertEquals(0, Order::paid()->where('id', $order->id)->count());

        // Status completed (selesai / uang diterima kurir) -> Lunas!
        $order->update(['status' => 'completed', 'payment_status' => 'paid']);
        $this->assertTrue($order->fresh()->isPaid());
        $this->assertEquals('Sudah Dibayar (COD)', $order->fresh()->payment_status_label);
        $this->assertEquals(1, Order::paid()->where('id', $order->id)->count());
    }

    public function test_checkout_success_page_displays_cod_instructions_and_no_bayar_sekarang(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'order_code' => 'ORD-COD-VIEW-TEST',
            'user_id' => $user->id,
            'total_price' => 150000,
            'payment_method' => 'Cash on Delivery (COD)',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'shipping_address' => 'Jl. Diponegoro No. 11',
            'customer_phone' => '081234567890',
        ]);

        $response = $this->actingAs($user)->get(route('checkout.success', $order->id));

        $response->assertStatus(200);
        $response->assertSee('Cash on Delivery (Bayar di Tempat)');
        $response->assertSee('Menunggu Diproses (COD)');
        $response->assertSee('Belum Dibayar (COD)');
        $response->assertSee('Menunggu Diproses');
        $response->assertSee('Bayar Tunai ke Kurir');
        $response->assertDontSee('Bayar Sekarang');
        $response->assertDontSee('Pembayaran Berhasil Diverifikasi');
        $response->assertDontSee('Verifikasi Pembayaran');
    }

    public function test_order_history_displays_cod_order_under_pending_tab(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'order_code' => 'ORD-COD-HISTORY-TEST',
            'user_id' => $user->id,
            'total_price' => 150000,
            'payment_method' => 'Cash on Delivery (COD)',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'shipping_address' => 'Jl. Diponegoro No. 11',
            'customer_phone' => '081234567890',
        ]);

        $response = $this->actingAs($user)->get(route('orders.index', ['status' => 'pending']));

        $response->assertStatus(200);
        $response->assertSee('ORD-COD-HISTORY-TEST');
        $response->assertSee('Menunggu Diproses (COD)');
        $response->assertSee('Detail Pesanan');
        $response->assertDontSee('Bayar Sekarang');
    }

    public function test_get_order_snap_token_rejects_cod_order(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'order_code' => 'ORD-COD-NO-SNAP',
            'user_id' => $user->id,
            'total_price' => 150000,
            'payment_method' => 'Cash on Delivery (COD)',
            'status' => 'processing',
            'shipping_address' => 'Jl. Diponegoro No. 11',
            'customer_phone' => '081234567890',
        ]);

        $response = $this->actingAs($user)->postJson(route('orders.snap-token', $order->id));

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Pesanan dengan metode Cash on Delivery (COD) tidak memerlukan pembayaran online via Midtrans.',
        ]);
    }

    public function test_sync_status_handles_cod_order_without_calling_midtrans(): void
    {
        Http::fake();

        $user = User::factory()->create();
        $order = Order::create([
            'order_code' => 'ORD-COD-SYNC',
            'user_id' => $user->id,
            'total_price' => 150000,
            'payment_method' => 'Cash on Delivery (COD)',
            'status' => 'processing',
            'shipping_address' => 'Jl. Diponegoro No. 11',
            'customer_phone' => '081234567890',
        ]);

        $response = $this->actingAs($user)->postJson(route('orders.sync-status', $order->id));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'can_be_paid' => false,
            'updated' => false,
        ]);

        Http::assertNothingSent();
    }

    public function test_standard_checkout_process_for_cod_redirects_to_success_with_flash(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 5, 'price' => 100000]);

        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->post(route('checkout.process'), [
            'customer_phone' => '081234567890',
            'shipping_address' => 'Jl. Merdeka No. 100, Jakarta',
            'payment_method' => 'Cash on Delivery (COD)',
        ]);

        $order = Order::where('user_id', $user->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals('pending', $order->status);
        $this->assertEquals('unpaid', $order->payment_status);
        $this->assertEquals('Cash on Delivery (COD)', $order->payment_method);
        $this->assertNull($order->snap_token);
        $this->assertNull($order->tracking_number);

        $response->assertRedirect(route('checkout.success', $order->id));
        $response->assertSessionHas('success');
    }

    public function test_admin_cod_order_workflow_progression_and_simulation_tracking(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = User::factory()->create();

        $order = Order::create([
            'order_code' => 'ORD-COD-ADMIN-TEST',
            'user_id' => $customer->id,
            'total_price' => 200000,
            'payment_method' => 'Cash on Delivery (COD)',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'shipping_address' => 'Jl. Kebon Jeruk No. 5',
            'customer_phone' => '081234567890',
        ]);

        // 1. Admin tidak boleh lompat langsung dari pending ke shipped pada pesanan COD
        $invalidShippedResponse = $this->actingAs($admin)->patch(route('admin.orders.update-status', $order->id), [
            'status' => 'shipped',
        ]);
        $invalidShippedResponse->assertSessionHas('error');
        $this->assertEquals('pending', $order->fresh()->status);

        // 2. Admin ubah status COD ke 'processing' (Dikemas)
        $processResponse = $this->actingAs($admin)->patch(route('admin.orders.update-status', $order->id), [
            'status' => 'processing',
        ]);
        $processResponse->assertSessionHas('success');
        $order->refresh();
        $this->assertEquals('processing', $order->status);
        $this->assertEquals('unpaid', $order->payment_status); // Tetap unpaid selama dikemas
        $this->assertNull($order->tracking_number);

        // 3. Admin ubah status COD ke 'shipped' (Dikirim) tanpa resi manual -> auto-generate simulasi resi
        $shippedResponse = $this->actingAs($admin)->patch(route('admin.orders.update-status', $order->id), [
            'status' => 'shipped',
        ]);
        $shippedResponse->assertSessionHas('success');
        $order->refresh();
        $this->assertEquals('shipped', $order->status);
        $this->assertEquals('unpaid', $order->payment_status); // Tetap unpaid selama dikirim
        $this->assertNotNull($order->tracking_number);
        $this->assertStringStartsWith('BLADE-', $order->tracking_number);

        // 4. Admin ubah status ke 'completed' (Selesai & Lunas COD)
        $completeResponse = $this->actingAs($admin)->patch(route('admin.orders.update-status', $order->id), [
            'status' => 'completed',
        ]);
        $completeResponse->assertSessionHas('success');
        $order->refresh();
        $this->assertEquals('completed', $order->status);
        $this->assertEquals('paid', $order->payment_status); // Otomatis lunas
        $this->assertTrue($order->isPaid());

        // 5. Verifikasi pesanan COD dibatalkan tidak dianggap lunas
        $orderCancelled = Order::create([
            'order_code' => 'ORD-COD-CANCELLED-TEST',
            'user_id' => $customer->id,
            'total_price' => 150000,
            'payment_method' => 'Cash on Delivery (COD)',
            'status' => 'pending',
            'payment_status' => 'unpaid',
            'shipping_address' => 'Jl. Kebon Jeruk No. 5',
            'customer_phone' => '081234567890',
        ]);

        $cancelResponse = $this->actingAs($admin)->patch(route('admin.orders.update-status', $orderCancelled->id), [
            'status' => 'cancelled',
        ]);
        $cancelResponse->assertSessionHas('success');
        $orderCancelled->refresh();
        $this->assertEquals('cancelled', $orderCancelled->status);
        $this->assertEquals('cancelled', $orderCancelled->payment_status);
        $this->assertFalse($orderCancelled->isPaid());
    }
}
