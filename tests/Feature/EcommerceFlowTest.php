<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class EcommerceFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_when_accessing_cart_or_checkout(): void
    {
        $this->get(route('cart.index'))->assertRedirect(route('login'));
        $this->get(route('checkout.index'))->assertRedirect(route('login'));
    }

    public function test_user_can_view_product_catalog_and_detail(): void
    {
        $product = Product::factory()->create([
            'name' => 'Kemeja Flannel Pria',
            'slug' => 'kemeja-flannel-pria',
            'price' => 150000,
            'stock' => 10,
        ]);

        $response = $this->get(route('products.index'));
        $response->assertStatus(200);
        $response->assertSee('Kemeja Flannel Pria');
        $response->assertSee('id="cart-badge-count"', false);

        $detailResponse = $this->get(route('products.show', $product->slug));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Kemeja Flannel Pria');
        $detailResponse->assertSee('Rp 150.000');
    }

    public function test_user_can_add_product_to_cart(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);

        $response = $this->actingAs($user)->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect(route('cart.index'));
        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        // Adding again should increment quantity
        $this->actingAs($user)->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 5,
        ]);
    }

    public function test_user_cannot_add_more_than_available_stock_to_cart(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 3]);

        $response = $this->actingAs($user)->from(route('products.show', $product->slug))->post(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 5,
        ]);

        $response->assertRedirect(route('products.show', $product->slug));
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('carts', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_user_can_update_and_delete_cart_item(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);
        $cart = Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        // Update quantity
        $response = $this->actingAs($user)->patch(route('cart.update', $cart->id), [
            'quantity' => 4,
        ]);

        $response->assertRedirect(route('cart.index'));
        $this->assertDatabaseHas('carts', [
            'id' => $cart->id,
            'quantity' => 4,
        ]);

        // Delete cart item
        $deleteResponse = $this->actingAs($user)->delete(route('cart.destroy', $cart->id));
        $deleteResponse->assertRedirect(route('cart.index'));
        $this->assertDatabaseMissing('carts', ['id' => $cart->id]);
    }

    public function test_user_can_add_product_to_cart_via_ajax_and_receives_sidebar_html(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'name' => 'Kemeja Flannel Pria Unik',
            'stock' => 10,
            'price' => 120000,
        ]);

        $response = $this->actingAs($user)->postJson(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'cart_count' => 1,
        ]);

        $responseData = $response->json();
        $this->assertArrayHasKey('sidebar_html', $responseData);
        $this->assertStringContainsString('Kemeja Flannel Pria Unik', $responseData['sidebar_html']);
    }

    public function test_guest_cannot_add_product_to_cart_via_ajax(): void
    {
        $product = Product::factory()->create(['stock' => 10]);

        $response = $this->postJson(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(401);
    }

    public function test_user_can_fetch_sidebar_html(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'name' => 'Sepatu Sneakers Kasual',
            'stock' => 10,
        ]);
        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)->getJson(route('cart.sidebar'));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'cart_count' => 2,
        ]);

        $sidebarHtml = (string) $response->json('sidebar_html');
        $this->assertStringContainsString('Sepatu Sneakers Kasual', $sidebarHtml);
        $this->assertStringContainsString('Lihat Keranjang Lengkap', $sidebarHtml);
        $this->assertStringContainsString(route('cart.index'), $sidebarHtml);
        $this->assertStringNotContainsString('Tujuan Pengiriman & WhatsApp', $sidebarHtml);
        $this->assertStringNotContainsString('Catatan Tambahan (Opsional)', $sidebarHtml);
        $this->assertStringNotContainsString('Bayar Sekarang', $sidebarHtml);
    }

    public function test_user_can_checkout_with_manual_bank_transfer(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'name' => 'Jaket Kulit',
            'price' => 200000,
            'stock' => 10,
        ]);

        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)->post(route('checkout.process'), [
            'payment_method' => 'Transfer Bank Manual',
            'shipping_address' => 'Jl. Sudirman No. 45, Jakarta Pusat, DKI Jakarta',
            'customer_phone' => '081234567890',
            'notes' => 'Tolong kirim sebelum jam 5 sore',
        ]);

        $order = Order::where('user_id', $user->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals(400000.00, (float) $order->total_price);
        $this->assertEquals('Transfer Bank Manual', $order->payment_method);
        $this->assertEquals('pending', $order->status);

        // Verify order items transferred
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 200000.00,
            'subtotal' => 400000.00,
        ]);

        // Verify stock decremented: 10 - 2 = 8
        $this->assertEquals(8, $product->fresh()->stock);

        // Verify cart emptied
        $this->assertDatabaseMissing('carts', ['user_id' => $user->id]);

        // Verify redirect to success page
        $response->assertRedirect(route('checkout.success', $order->id));

        // Check success page content
        $successResponse = $this->actingAs($user)->get(route('checkout.success', $order->id));
        $successResponse->assertStatus(200);
        $successResponse->assertSee($order->order_code);
        $successResponse->assertSee('Transfer Bank Manual');
        $successResponse->assertSee('123-456-7890'); // BCA account
    }

    public function test_user_can_checkout_with_cash_on_delivery(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'price' => 120000,
            'stock' => 5,
        ]);

        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->post(route('checkout.process'), [
            'payment_method' => 'Cash on Delivery (COD)',
            'shipping_address' => 'Jl. Merdeka Barat No. 12, Bandung, Jawa Barat',
            'customer_phone' => '08987654321',
        ]);

        $order = Order::where('user_id', $user->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals('Cash on Delivery (COD)', $order->payment_method);

        // Cart is cleared
        $this->assertDatabaseMissing('carts', ['user_id' => $user->id]);

        $response->assertRedirect(route('checkout.success', $order->id));
    }

    public function test_user_can_filter_products_by_category(): void
    {
        $electronics = Product::factory()->create([
            'name' => 'TWS Bluetooth Earbuds',
            'category' => 'Elektronik',
            'price' => 200000,
        ]);

        $fashion = Product::factory()->create([
            'name' => 'Kaos Katun Polos',
            'category' => 'Fashion',
            'price' => 75000,
        ]);

        $response = $this->get(route('products.index', ['category' => 'Elektronik']));
        $response->assertStatus(200);
        $response->assertSee('TWS Bluetooth Earbuds');
        $response->assertDontSee('Kaos Katun Polos');
    }

    public function test_user_can_search_products_across_categories(): void
    {
        $product = Product::factory()->create([
            'name' => 'Smartwatch AMOLED Display',
            'category' => 'Elektronik',
            'description' => 'Pelacak detak jantung dan olahraga',
        ]);

        $response = $this->get(route('products.index', ['search' => 'AMOLED']));
        $response->assertStatus(200);
        $response->assertSee('Smartwatch AMOLED Display');
    }

    public function test_user_can_sort_products_by_price(): void
    {
        $cheap = Product::factory()->create(['name' => 'Barang Termurah', 'price' => 10000]);
        $expensive = Product::factory()->create(['name' => 'Barang Termahal', 'price' => 500000]);

        $responseAsc = $this->get(route('products.index', ['sort' => 'price_asc']));
        $responseAsc->assertStatus(200);
        $productsInView = $responseAsc->viewData('products');
        $this->assertEquals($cheap->id, $productsInView->first()->id);

        $responseDesc = $this->get(route('products.index', ['sort' => 'price_desc']));
        $responseDesc->assertStatus(200);
        $productsInViewDesc = $responseDesc->viewData('products');
        $this->assertEquals($expensive->id, $productsInViewDesc->first()->id);
    }

    public function test_homepage_renders_all_marketplace_categories_and_mobile_bottom_nav(): void
    {
        $response = $this->get(route('products.index'));
        $response->assertStatus(200);

        // All 8 categories should be rendered
        $response->assertSee('Elektronik');
        $response->assertSee('Fashion');
        $response->assertSee('Kecantikan');
        $response->assertSee('Rumah Tangga');
        $response->assertSee('Makanan');
        $response->assertSee('Olahraga');
        $response->assertSee('Aksesori');
        $response->assertSee('Otomotif');

        // Mobile bottom nav should exist
        $response->assertSee('mobile-bottom-nav');
    }

    public function test_product_detail_shows_related_products_in_same_category(): void
    {
        $mainProduct = Product::factory()->create([
            'name' => 'Serum Niacinamide 10%',
            'category' => 'Kecantikan',
        ]);

        $relatedProduct = Product::factory()->create([
            'name' => 'Sunscreen Gel SPF 50',
            'category' => 'Kecantikan',
        ]);

        $response = $this->get(route('products.show', $mainProduct->slug));
        $response->assertStatus(200);
        $response->assertSee('Serum Niacinamide 10%');
        $response->assertSee('Sunscreen Gel SPF 50');
    }

    public function test_discounted_product_displays_badge_original_price_and_calculated_final_price(): void
    {
        $discountedProduct = Product::factory()->create([
            'name' => 'Headphone Bluetooth Bass',
            'slug' => 'headphone-bluetooth-bass',
            'price' => 200000,
            'discount_percentage' => 20, // 20% off -> Final price: 160000
            'stock' => 15,
        ]);

        // Catalog
        $catalogResponse = $this->get(route('products.index'));
        $catalogResponse->assertStatus(200);
        $catalogResponse->assertSee('-20%');
        $catalogResponse->assertSee('Rp 160.000');
        $catalogResponse->assertSee('Rp 200.000');

        // Product Detail
        $detailResponse = $this->get(route('products.show', $discountedProduct->slug));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('-20%');
        $detailResponse->assertSee('Rp 160.000');
        $detailResponse->assertSee('Rp 200.000');
    }

    public function test_non_discounted_product_hides_discount_badge_and_strike_through_price(): void
    {
        $normalProduct = Product::factory()->create([
            'name' => 'Sepatu Olahraga Running',
            'slug' => 'sepatu-olahraga-running',
            'price' => 300000,
            'discount_percentage' => 0,
            'stock' => 10,
        ]);

        $detailResponse = $this->get(route('products.show', $normalProduct->slug));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Rp 300.000');
        $detailResponse->assertDontSee('-0%');
        $detailResponse->assertDontSee('text-decoration-line-through');
    }

    public function test_cart_and_checkout_calculates_with_discounted_final_price(): void
    {
        $user = User::factory()->create();
        $discountedProduct = Product::factory()->create([
            'name' => 'Kemeja Formal Premium',
            'price' => 200000,
            'discount_percentage' => 25, // 25% off -> 150000
            'stock' => 10,
        ]);

        $cart = Cart::create([
            'user_id' => $user->id,
            'product_id' => $discountedProduct->id,
            'quantity' => 2, // 2 * 150000 = 300000
        ]);

        // Cart index
        $cartResponse = $this->actingAs($user)->get(route('cart.index'));
        $cartResponse->assertStatus(200);
        $cartResponse->assertSee('Rp 150.000');
        $cartResponse->assertSee('Rp 300.000');

        // Checkout process
        $checkoutResponse = $this->actingAs($user)->post(route('checkout.process'), [
            'payment_method' => 'Transfer Bank Manual',
            'shipping_address' => 'Jl. Pahlawan No. 10, Surabaya',
            'customer_phone' => '081234567899',
        ]);

        $order = Order::where('user_id', $user->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals(300000.00, (float) $order->total_price);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $discountedProduct->id,
            'quantity' => 2,
            'price' => 150000.00,
            'subtotal' => 300000.00,
        ]);
    }

    public function test_user_can_add_product_to_cart_via_ajax_and_receives_json_response(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 10]);

        $response = $this->actingAs($user)->postJson(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Berhasil ditambahkan ke keranjang',
            'cart_count' => 3,
        ]);

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 3,
        ]);
    }

    public function test_ajax_add_to_cart_handles_insufficient_stock(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['stock' => 2]);

        $response = $this->actingAs($user)->postJson(route('cart.store'), [
            'product_id' => $product->id,
            'quantity' => 5,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
    }

    public function test_cart_toast_elements_and_session_script_are_rendered_on_cart_added(): void
    {
        $user = User::factory()->create();

        // When session has cart_added, popup toast script should be triggered
        $response = $this->actingAs($user)
            ->withSession(['cart_added' => 'Produk berhasil ditambahkan ke keranjang'])
            ->get(route('cart.index'));

        $response->assertStatus(200);
        $response->assertSee('cart-toast');
        $response->assertSee('cart-toast-center');
        $response->assertSee('showCartToast');
        $response->assertSee('Produk berhasil ditambahkan ke keranjang');

        // When session has success (e.g. system notification like logout/login),
        // it shows normal alert and should NOT trigger the cart_added toast script
        $successResponse = $this->actingAs($user)
            ->withSession(['success' => 'Profil berhasil diperbarui.'])
            ->get(route('cart.index'));

        $successResponse->assertStatus(200);
        $successResponse->assertSee('alert-success');
        $successResponse->assertSee('Profil berhasil diperbarui.');
        $successResponse->assertDontSee('showCartToast("Profil berhasil diperbarui.")');
    }

    public function test_user_can_update_cart_quantity_via_ajax_and_receives_json_response(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'price' => 100000,
            'discount_percentage' => 0,
            'stock' => 20,
        ]);

        $cart = Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        // AJAX update quantity from 2 to 5
        $response = $this->actingAs($user)->patchJson(route('cart.update', $cart->id), [
            'quantity' => 5,
            'product_id' => $product->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Kuantitas produk berhasil diperbarui.',
            'item' => [
                'id' => $cart->id,
                'quantity' => 5,
                'stock' => 20,
                'subtotal' => 500000,
                'formatted_subtotal' => 'Rp 500.000',
            ],
            'total_price' => 500000,
            'formatted_total_price' => 'Rp 500.000',
            'total_quantity' => 5,
            'cart_count' => 5,
        ]);

        $this->assertDatabaseHas('carts', [
            'id' => $cart->id,
            'quantity' => 5,
        ]);
    }

    public function test_ajax_update_cart_quantity_validates_stock_limit(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'stock' => 5,
        ]);

        $cart = Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        // Try to update quantity to 10 (exceeds stock 5)
        $response = $this->actingAs($user)->patchJson(route('cart.update', $cart->id), [
            'quantity' => 10,
            'product_id' => $product->id,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'available_stock' => 5,
            'current_quantity' => 3,
        ]);

        // Database quantity must remain 3
        $this->assertDatabaseHas('carts', [
            'id' => $cart->id,
            'quantity' => 3,
        ]);
    }

    public function test_cart_page_renders_ajax_elements_and_realtime_selectors(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'name' => 'Kemeja Flannel',
            'price' => 150000,
            'stock' => 10,
        ]);

        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)->get(route('cart.index'));

        $response->assertStatus(200);
        $response->assertSee('btn-qty-change');
        $response->assertSee('data-delta="-1"', false);
        $response->assertSee('data-delta="1"', false);
        $response->assertSee('id="cart-total-price"', false);
        $response->assertSee('id="cart-total-quantity"', false);
        $response->assertSee('id="cart-header-count"', false);

        $html = $response->getContent();
        $this->assertStringContainsString('marketplace-header', $html);
        $this->assertTrue(
            strpos($html, 'marketplace-header') < strpos($html, 'id="cart-header-count"'),
            'Navbar must appear before cart content'
        );
    }

    public function test_checkout_can_generate_snap_token_from_cart(): void
    {
        Http::fake([
            config('midtrans.api_url') => Http::response([
                'token' => 'mock-midtrans-snap-token-777',
                'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/mock-midtrans-snap-token-777',
            ], 201),
        ]);

        $user = User::factory()->create();
        $product = Product::factory()->create([
            'price' => 200000,
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
            'shipping_address' => 'Jl. Gatot Subroto No. 12, Jakarta',
            'payment_method' => 'Midtrans Snap',
            'notes' => 'Tolong bungkus rapi',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'snap_token' => 'mock-midtrans-snap-token-777',
        ]);

        // Verify order created with pending status and snap_token
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total_price' => 400000,
            'status' => 'pending',
            'snap_token' => 'mock-midtrans-snap-token-777',
        ]);

        // Verify cart is emptied
        $this->assertDatabaseMissing('carts', [
            'user_id' => $user->id,
        ]);

        // Verify product stock decremented
        $this->assertEquals(8, $product->fresh()->stock);
    }

    public function test_midtrans_webhook_callback_updates_order_status_to_processing_on_settlement(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'order_code' => 'ORD-20261029-TEST01',
            'user_id' => $user->id,
            'total_price' => 150000,
            'payment_method' => 'Midtrans Snap',
            'status' => 'pending',
            'shipping_address' => 'Jl. Sudirman No. 1',
            'customer_phone' => '081299998888',
        ]);

        $response = $this->postJson('/api/midtrans/callback', [
            'order_id' => $order->order_code,
            'status_code' => '200',
            'gross_amount' => '150000.00',
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'status' => 'processing',
        ]);

        $this->assertEquals('processing', $order->fresh()->status);
    }

    public function test_midtrans_webhook_callback_updates_order_status_to_cancelled_on_deny_or_expire(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'order_code' => 'ORD-20261029-TEST02',
            'user_id' => $user->id,
            'total_price' => 250000,
            'payment_method' => 'Midtrans Snap',
            'status' => 'pending',
            'shipping_address' => 'Jl. Thamrin No. 2',
            'customer_phone' => '081299997777',
        ]);

        $response = $this->postJson('/api/midtrans/callback', [
            'order_id' => $order->order_code,
            'status_code' => '202',
            'gross_amount' => '250000.00',
            'transaction_status' => 'expire',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'status' => 'cancelled',
        ]);

        $this->assertEquals('cancelled', $order->fresh()->status);
    }

    public function test_midtrans_webhook_callback_verifies_signature_key_and_updates_on_capture_accept(): void
    {
        config(['midtrans.server_key' => 'test-server-key-12345']);

        $user = User::factory()->create();
        $order = Order::create([
            'order_code' => 'ORD-20261029-CAPTURE01',
            'user_id' => $user->id,
            'total_price' => 175000,
            'payment_method' => 'Midtrans Snap',
            'status' => 'pending',
            'shipping_address' => 'Jl. Pahlawan No. 45',
            'customer_phone' => '081234567890',
        ]);

        $orderId = $order->order_code;
        $statusCode = '200';
        $grossAmount = '175000.00';
        $validSignature = hash('sha512', $orderId.$statusCode.$grossAmount.'test-server-key-12345');

        $response = $this->postJson('/api/midtrans/callback', [
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $validSignature,
            'transaction_status' => 'capture',
            'fraud_status' => 'accept',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'status' => 'processing',
        ]);

        $order->refresh();
        $this->assertEquals('processing', $order->status);
        $this->assertEquals('Dikemas', $order->status_label);
    }

    public function test_midtrans_webhook_callback_rejects_invalid_signature_key_with_403(): void
    {
        config(['midtrans.server_key' => 'test-server-key-12345']);

        $user = User::factory()->create();
        $order = Order::create([
            'order_code' => 'ORD-20261029-FORGED01',
            'user_id' => $user->id,
            'total_price' => 175000,
            'payment_method' => 'Midtrans Snap',
            'status' => 'pending',
            'shipping_address' => 'Jl. Pahlawan No. 45',
            'customer_phone' => '081234567890',
        ]);

        $response = $this->postJson('/api/midtrans/callback', [
            'order_id' => $order->order_code,
            'status_code' => '200',
            'gross_amount' => '175000.00',
            'signature_key' => 'invalid-fake-signature-hash',
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
        ]);

        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'message' => 'Signature key tidak valid.',
        ]);

        // Order status must remain pending
        $this->assertEquals('pending', $order->fresh()->status);
    }

    public function test_cart_page_renders_midtrans_snap_sdk_script_and_pay_button(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('cart.index'));

        $response->assertStatus(200);
        $response->assertSee('snap.js');
        $response->assertSee('cart-checkout-form');
        $response->assertSee('pay-button');
        $response->assertSee('Informasi Pengiriman');
        $response->assertDontSee('Transfer Bank Manual');
        $response->assertDontSee('COD (Bayar di Tempat)');
    }

    public function test_unpaid_order_shows_bayar_sekarang_button_on_checkout_success_and_order_history(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $order = Order::create([
            'order_code' => 'ORD-UNPAID-PAY-NOW',
            'user_id' => $user->id,
            'total_price' => 120000,
            'payment_method' => 'Midtrans Snap',
            'status' => 'pending',
            'snap_token' => 'mock-snap-retry-token',
            'shipping_address' => 'Jl. Kebon Jeruk No. 5',
            'customer_phone' => '08123456789',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 120000,
            'subtotal' => 120000,
        ]);

        // 1. Checkout Success page
        $successResponse = $this->actingAs($user)->get(route('checkout.success', $order->id));
        $successResponse->assertStatus(200);
        $successResponse->assertSee('Bayar Sekarang');
        $successResponse->assertSee('mock-snap-retry-token');

        // 2. Orders History index page
        $historyResponse = $this->actingAs($user)->get(route('orders.index'));
        $historyResponse->assertStatus(200);
        $historyResponse->assertSee('Bayar Sekarang');
        $historyResponse->assertSee('btn-pay-snap');

        // 3. Order detail page
        $detailResponse = $this->actingAs($user)->get(route('orders.show', $order->id));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Bayar Sekarang');
        $detailResponse->assertSee('btn-pay-snap');
    }

    public function test_customer_can_retrieve_snap_token_for_unpaid_order(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'order_code' => 'ORD-SNAP-RETRIEVE',
            'user_id' => $user->id,
            'total_price' => 150000,
            'payment_method' => 'Midtrans Snap',
            'status' => 'pending',
            'snap_token' => 'active-saved-snap-token-123',
            'shipping_address' => 'Jl. Kebon Kacang No. 7',
            'customer_phone' => '08123456789',
        ]);

        $response = $this->actingAs($user)->getJson(route('orders.snap-token', $order->id));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'snap_token' => 'active-saved-snap-token-123',
            'order_code' => 'ORD-SNAP-RETRIEVE',
        ]);
    }

    public function test_unpaid_order_older_than_24_hours_is_auto_cancelled_and_hides_pay_button(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'order_code' => 'ORD-EXPIRED-TEST',
            'user_id' => $user->id,
            'total_price' => 200000,
            'payment_method' => 'Midtrans Snap',
            'status' => 'pending',
            'snap_token' => 'expired-token-456',
            'shipping_address' => 'Jl. Diponegoro No. 8',
            'customer_phone' => '08123456789',
        ]);

        // Set created_at to 25 hours ago
        $order->created_at = now()->subHours(25);
        $order->saveQuietly();

        $this->assertTrue($order->isExpired());
        $this->assertFalse($order->canBePaid());

        // Visiting orders index auto-cancels expired orders
        $response = $this->actingAs($user)->get(route('orders.index'));
        $response->assertStatus(200);

        $order->refresh();
        $this->assertEquals('cancelled', $order->status);
        $this->assertStringContainsString('Dibatalkan', $order->status_label);

        // Pay button should NOT be rendered for expired/cancelled orders
        $response->assertDontSee('Bayar Sekarang');
        $response->assertDontSee('data-order-id="'.$order->id.'"', false);
    }

    public function test_customer_can_sync_order_status_directly_from_midtrans(): void
    {
        config(['midtrans.server_key' => 'test-server-key-sync-123']);

        Http::fake([
            'https://api.sandbox.midtrans.com/v2/ORD-SYNC-TEST/status' => Http::response([
                'status_code' => '200',
                'order_id' => 'ORD-SYNC-TEST',
                'transaction_status' => 'settlement',
                'fraud_status' => 'accept',
            ], 200),
        ]);

        $user = User::factory()->create();
        $order = Order::create([
            'order_code' => 'ORD-SYNC-TEST',
            'user_id' => $user->id,
            'total_price' => 179000,
            'payment_method' => 'Midtrans Snap',
            'status' => 'pending',
            'snap_token' => 'sync-test-snap-token',
            'shipping_address' => 'Jl. Merdeka No. 10',
            'customer_phone' => '08123456789',
        ]);

        $response = $this->actingAs($user)->postJson(route('orders.sync-status', $order->id));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'order_code' => 'ORD-SYNC-TEST',
            'status' => 'processing',
            'status_label' => 'Dikemas',
            'updated' => true,
        ]);

        $this->assertEquals('processing', $order->fresh()->status);
        $this->assertEquals('Dikemas', $order->fresh()->status_label);
    }

    public function test_orders_sync_status_does_not_mark_order_paid_without_backend_midtrans_verification(): void
    {
        config(['midtrans.server_key' => 'test-server-key-sync-123']);

        // Midtrans returns pending or error instead of settlement
        Http::fake([
            'https://api.sandbox.midtrans.com/v2/ORD-UNVERIFIED-TEST/status' => Http::response([
                'status_code' => '201',
                'order_id' => 'ORD-UNVERIFIED-TEST',
                'transaction_status' => 'pending',
            ], 200),
        ]);

        $user = User::factory()->create();
        $order = Order::create([
            'order_code' => 'ORD-UNVERIFIED-TEST',
            'user_id' => $user->id,
            'total_price' => 125000,
            'payment_method' => 'Midtrans Snap',
            'status' => 'pending',
            'snap_token' => 'unverified-snap-token',
            'shipping_address' => 'Jl. Kebon Jeruk No. 20',
            'customer_phone' => '08123456789',
        ]);

        // Attempt sending spoofed client callback data
        $response = $this->actingAs($user)->postJson(route('orders.sync-status', $order->id), [
            'order_id' => 'ORD-UNVERIFIED-TEST',
            'status_code' => '200',
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
        ]);

        $response->assertStatus(200);
        // Order MUST stay pending because backend Midtrans query returned pending
        $this->assertEquals('pending', $order->fresh()->status);
        $this->assertEquals('Belum Bayar', $order->fresh()->status_label);
    }

    public function test_orders_history_page_filters_orders_by_status_and_displays_tabs(): void
    {
        $user = User::factory()->create();

        $pendingOrder = Order::create([
            'order_code' => 'ORD-HIST-PENDING',
            'user_id' => $user->id,
            'total_price' => 50000,
            'payment_method' => 'Midtrans Snap',
            'status' => 'pending',
            'shipping_address' => 'Jl. Sukarno No. 1',
            'customer_phone' => '08123456789',
        ]);

        $completedOrder = Order::create([
            'order_code' => 'ORD-HIST-COMPLETED',
            'user_id' => $user->id,
            'total_price' => 75000,
            'payment_method' => 'Midtrans Snap',
            'status' => 'completed',
            'shipping_address' => 'Jl. Sukarno No. 2',
            'customer_phone' => '08123456789',
        ]);

        // 1. All tab
        $responseAll = $this->actingAs($user)->get(route('orders.index'));
        $responseAll->assertStatus(200);
        $responseAll->assertSee('ORD-HIST-PENDING');
        $responseAll->assertSee('ORD-HIST-COMPLETED');
        $responseAll->assertSee('order-summary-row');
        $responseAll->assertSee('order-card-actions');

        // 2. Pending filter
        $responsePending = $this->actingAs($user)->get(route('orders.index', ['status' => 'pending']));
        $responsePending->assertStatus(200);
        $responsePending->assertSee('ORD-HIST-PENDING');
        $responsePending->assertDontSee('ORD-HIST-COMPLETED');

        // 3. Completed filter
        $responseCompleted = $this->actingAs($user)->get(route('orders.index', ['status' => 'completed']));
        $responseCompleted->assertStatus(200);
        $responseCompleted->assertDontSee('ORD-HIST-PENDING');
        $responseCompleted->assertSee('ORD-HIST-COMPLETED');
    }

    public function test_orders_history_page_displays_empty_state_for_filter_without_results(): void
    {
        $user = User::factory()->create();

        // User has only 1 completed order
        Order::create([
            'order_code' => 'ORD-ONLY-COMPLETED',
            'user_id' => $user->id,
            'total_price' => 90000,
            'payment_method' => 'Midtrans Snap',
            'status' => 'completed',
            'shipping_address' => 'Jl. Sudirman No. 9',
            'customer_phone' => '08123456789',
        ]);

        // Filter by dikemas (processing) which has 0 results
        $response = $this->actingAs($user)->get(route('orders.index', ['status' => 'processing']));
        $response->assertStatus(200);
        $response->assertSee('Tidak Ada Pesanan', false);
        $response->assertSee('Dikemas', false);
        $response->assertSee('Lihat Semua Pesanan');
        $response->assertDontSee('ORD-ONLY-COMPLETED');
    }

    public function test_checkout_success_page_displays_paid_confirmation_and_hides_pay_now_when_order_is_paid(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'order_code' => 'ORD-SUCCESS-PAID',
            'user_id' => $user->id,
            'total_price' => 95000,
            'payment_method' => 'QRIS Instant',
            'status' => 'processing',
            'shipping_address' => 'Jl. Merdeka No. 45',
            'customer_phone' => '081234567890',
        ]);

        $response = $this->actingAs($user)->get(route('checkout.success', $order->id));

        $response->assertStatus(200);
        $response->assertSee('Pembayaran Berhasil Diverifikasi');
        $response->assertSee('Pembayaran Telah Dikonfirmasi (Lunas)');
        $response->assertSee('Dikemas');
        $response->assertSee('order-stepper');
        $response->assertSee('Lihat Detail Pesanan');
        $response->assertSee('Lanjut Belanja');
        $response->assertSee(route('orders.show', $order->id));
        $response->assertSee(route('orders.index'));

        // Pay now button and payment action card must NOT be present
        $response->assertDontSee('Selesaikan Pembayaran Sekarang');
        $response->assertDontSee('btn-pay-now');
    }

    public function test_checkout_success_page_displays_pending_warning_and_shows_pay_now_when_order_is_unpaid(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'order_code' => 'ORD-SUCCESS-UNPAID',
            'user_id' => $user->id,
            'total_price' => 95000,
            'payment_method' => 'QRIS Instant',
            'status' => 'pending',
            'snap_token' => 'active-unpaid-snap-token',
            'shipping_address' => 'Jl. Merdeka No. 45',
            'customer_phone' => '081234567890',
        ]);

        $response = $this->actingAs($user)->get(route('checkout.success', $order->id));

        $response->assertStatus(200);
        $response->assertSee('Menunggu Pembayaran');
        $response->assertSee('Selesaikan Pembayaran Sekarang');
        $response->assertSee('Belum Bayar');
        $response->assertSee('btn-pay-now');
        $response->assertSee('order-stepper');
        $response->assertSee('Lihat Detail Pesanan');
        $response->assertSee('Lanjut Belanja');
    }

    public function test_checkout_success_page_does_not_mark_order_paid_via_spoofed_url_parameters(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'order_code' => 'ORD-SPOOF-TEST',
            'user_id' => $user->id,
            'total_price' => 150000,
            'payment_method' => 'QRIS Instant',
            'status' => 'pending',
            'snap_token' => 'spoof-token-123',
            'shipping_address' => 'Jl. Keamanan Siber No. 1',
            'customer_phone' => '081234567890',
        ]);

        // Attempt accessing success page with spoofed Midtrans callback parameters
        $response = $this->actingAs($user)->get(route('checkout.success', [
            'order' => $order->id,
            'order_id' => $order->order_code,
            'status_code' => '200',
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
        ]));

        $response->assertStatus(200);
        // Must still show pending/unpaid because database status has not been verified
        $this->assertEquals('pending', $order->fresh()->status);
        $response->assertSee('Menunggu Pembayaran');
        $response->assertDontSee('Pembayaran Berhasil Diverifikasi');
        $response->assertSee('btn-pay-now');
    }

    public function test_checkout_success_page_handles_cancelled_order(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'order_code' => 'ORD-CANCELLED-TEST',
            'user_id' => $user->id,
            'total_price' => 80000,
            'payment_method' => 'QRIS Instant',
            'status' => 'cancelled',
            'shipping_address' => 'Jl. Kenanga No. 8',
            'customer_phone' => '081234567890',
        ]);

        $response = $this->actingAs($user)->get(route('checkout.success', $order->id));

        $response->assertStatus(200);
        $response->assertSee('Pesanan Dibatalkan');
        $response->assertDontSee('btn-pay-now');
        $response->assertSee('Lihat Detail Pesanan');
        $response->assertSee('Lanjut Belanja');
    }

    public function test_cart_page_renders_product_checkboxes_and_check_all_header(): void
    {
        $user = User::factory()->create();
        $product1 = Product::factory()->create(['price' => 50000, 'stock' => 10]);
        $product2 = Product::factory()->create(['price' => 75000, 'stock' => 10]);

        $item1 = Cart::create([
            'user_id' => $user->id,
            'product_id' => $product1->id,
            'quantity' => 1,
        ]);
        $item2 = Cart::create([
            'user_id' => $user->id,
            'product_id' => $product2->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)->get(route('cart.index'));

        $response->assertStatus(200);
        $response->assertSee('id="check-all-items"', false);
        $response->assertSee('cart-item-checkbox', false);
        $response->assertSee('name="cart_ids[]"', false);
        $response->assertSee('value="'.$item1->id.'"', false);
        $response->assertSee('value="'.$item2->id.'"', false);
        $response->assertSee('data-price="50000"', false);
        $response->assertSee('data-price="75000"', false);
    }

    public function test_checkout_processes_only_selected_cart_items_and_retains_unselected_items_in_cart(): void
    {
        $user = User::factory()->create();
        $product1 = Product::factory()->create(['price' => 50000, 'stock' => 10, 'discount_percentage' => 0]);
        $product2 = Product::factory()->create(['price' => 100000, 'stock' => 10, 'discount_percentage' => 0]);

        $item1 = Cart::create([
            'user_id' => $user->id,
            'product_id' => $product1->id,
            'quantity' => 1,
        ]);
        $item2 = Cart::create([
            'user_id' => $user->id,
            'product_id' => $product2->id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)->post(route('checkout.process'), [
            'customer_phone' => '081234567890',
            'shipping_address' => 'Jl. Merdeka No. 12',
            'payment_method' => 'Midtrans Snap',
            'cart_ids' => [$item1->id],
        ]);

        $response->assertRedirect();

        // Verifikasi Order dibuat hanya dengan item1
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'total_price' => 50000,
        ]);

        $order = Order::where('user_id', $user->id)->first();
        $this->assertCount(1, $order->items);
        $this->assertEquals($product1->id, $order->items->first()->product_id);

        // Verifikasi item1 terhapus dari tabel carts, sedangkan item2 tetap tersimpan
        $this->assertDatabaseMissing('carts', ['id' => $item1->id]);
        $this->assertDatabaseHas('carts', [
            'id' => $item2->id,
            'user_id' => $user->id,
            'quantity' => 2,
        ]);
    }

    public function test_checkout_snap_token_processes_only_selected_cart_items_and_retains_unselected(): void
    {
        Http::fake([
            config('midtrans.api_url') => Http::response([
                'token' => 'mock-selective-snap-token',
                'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/mock-selective-snap-token',
            ], 201),
        ]);

        $user = User::factory()->create();
        $product1 = Product::factory()->create(['price' => 40000, 'stock' => 10, 'discount_percentage' => 0]);
        $product2 = Product::factory()->create(['price' => 60000, 'stock' => 10, 'discount_percentage' => 0]);

        $item1 = Cart::create([
            'user_id' => $user->id,
            'product_id' => $product1->id,
            'quantity' => 1,
        ]);
        $item2 = Cart::create([
            'user_id' => $user->id,
            'product_id' => $product2->id,
            'quantity' => 3,
        ]);

        // Checkout hanya memilih item2 (3 x 60.000 = 180.000)
        $response = $this->actingAs($user)->postJson(route('checkout.snap-token'), [
            'customer_phone' => '081234567890',
            'shipping_address' => 'Jl. Sudirman No. 8',
            'payment_method' => 'Midtrans Snap',
            'cart_ids' => [$item2->id],
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'snap_token' => 'mock-selective-snap-token',
        ]);

        $order = Order::where('user_id', $user->id)->first();
        $this->assertEquals(180000, $order->total_price);
        $this->assertCount(1, $order->items);
        $this->assertEquals($product2->id, $order->items->first()->product_id);

        // Hanya item2 yang terhapus dari keranjang, item1 masih ada
        $this->assertDatabaseMissing('carts', ['id' => $item2->id]);
        $this->assertDatabaseHas('carts', [
            'id' => $item1->id,
            'user_id' => $user->id,
            'quantity' => 1,
        ]);
    }

    public function test_checkout_snap_token_fails_when_selected_cart_is_empty_or_invalid(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['price' => 50000, 'stock' => 10]);
        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->postJson(route('checkout.snap-token'), [
            'customer_phone' => '081234567890',
            'shipping_address' => 'Jl. Sudirman No. 8',
            'payment_method' => 'Midtrans Snap',
            'cart_ids' => [99999], // non-existent ID
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Silakan pilih minimal satu produk di keranjang untuk checkout.',
        ]);
    }

    public function test_layout_renders_favicon_and_dynamic_browser_tab_title_format(): void
    {
        // 1. Beranda / Katalog Produk
        $homeResponse = $this->get(route('products.index'));
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('<title>BladeStore - Katalog Produk Pilihan</title>', false);
        $homeResponse->assertSee('images/logo.svg', false);
        $homeResponse->assertSee('favicon.ico', false);

        // 2. Auth Pages
        $loginResponse = $this->get(route('login'));
        $loginResponse->assertStatus(200);
        $loginResponse->assertSee('<title>BladeStore - Masuk ke Akun</title>', false);

        $registerResponse = $this->get(route('register'));
        $registerResponse->assertStatus(200);
        $registerResponse->assertSee('<title>BladeStore - Daftar Akun Baru</title>', false);

        // 3. User Authenticated Pages (Cart & Orders)
        $user = User::factory()->create();
        $cartResponse = $this->actingAs($user)->get(route('cart.index'));
        $cartResponse->assertStatus(200);
        $cartResponse->assertSee('<title>BladeStore - Keranjang Belanja</title>', false);

        $ordersResponse = $this->actingAs($user)->get(route('orders.index'));
        $ordersResponse->assertStatus(200);
        $ordersResponse->assertSee('<title>BladeStore - Pesanan Saya</title>', false);
    }

    public function test_navbar_renders_global_search_interaction_icons_and_profile_dropdown(): void
    {
        $customer = User::factory()->create(['role' => 'customer', 'name' => 'Budi Pelanggan']);

        $response = $this->actingAs($customer)->get(route('products.index'));

        $response->assertStatus(200);

        // 1. Global Search Bar
        $response->assertSee('global-search-form', false);
        $response->assertSee('name="search"', false);
        $response->assertSee('global-search-btn', false);
        $response->assertSee(route('products.index'));

        // 2. Ikon Interaksi (Wishlist, Notifikasi, Keranjang)
        $response->assertSee('bi-heart', false);
        $response->assertSee('bi-bell', false);
        $response->assertSee('notification-badge-count', false);
        $response->assertSee('bi-cart3', false);
        $response->assertSee('cart-badge-count', false);

        // 3. Dropdown Profil Pengguna (Customer)
        $response->assertSee('Pengaturan Akun');
        $response->assertSee('Pesanan Saya');
        $response->assertSee('Keluar / Logout');
        $response->assertSee(route('logout'));
        $response->assertDontSee('Dashboard Admin');

        // 4. Admin Navbar
        $admin = User::factory()->admin()->create(['name' => 'Admin Utama']);
        $adminResponse = $this->actingAs($admin)->get(route('products.index'));

        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('Dashboard Admin');
        $adminResponse->assertSee(route('admin.dashboard'));
    }

    public function test_user_can_filter_products_by_price_range(): void
    {
        $affordable = Product::factory()->create([
            'name' => 'Produk Terjangkau 50k',
            'price' => 50000,
        ]);

        $premium = Product::factory()->create([
            'name' => 'Produk Premium 500k',
            'price' => 500000,
        ]);

        $response = $this->get(route('products.index', [
            'min_price' => '30000',
            'max_price' => '100000',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Produk Terjangkau 50k');
        $response->assertDontSee('Produk Premium 500k');
    }

    public function test_user_can_filter_products_by_in_stock_only(): void
    {
        $inStock = Product::factory()->create([
            'name' => 'Produk Ready Stock',
            'stock' => 10,
        ]);

        $outOfStock = Product::factory()->create([
            'name' => 'Produk Habis Total',
            'stock' => 0,
        ]);

        $response = $this->get(route('products.index', ['in_stock' => '1']));

        $response->assertStatus(200);
        $response->assertSee('Produk Ready Stock');
        $response->assertDontSee('Produk Habis Total');
    }

    public function test_catalog_renders_wishlist_buttons_and_compact_add_to_cart(): void
    {
        $product = Product::factory()->create([
            'name' => 'Tas Ransel Laptop Modern',
            'category' => 'Fashion',
            'stock' => 5,
        ]);

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee('btn-product-wishlist', false);
        $response->assertSee('btn-add-cart-compact', false);
        $response->assertSee('+ Keranjang');
        $response->assertSee('data-product-id="'.$product->id.'"', false);
        $response->assertSee('id="nav-wishlist-trigger"', false);
        $response->assertSee('id="wishlist-badge-count"', false);
    }

    public function test_homepage_renders_clean_two_column_static_hero_without_carousel(): void
    {
        $response = $this->get(route('products.index'));

        $response->assertStatus(200);

        // Verify clean 2-column static hero layout elements
        $response->assertSee('hero-fullwidth-wrapper', false);
        $response->assertSee('hero-title', false);
        $response->assertSee('Belanja Cerdas & Mudah', false);
        $response->assertSee('Mulai Belanja');
        $response->assertSee('Jelajahi Kategori');
        $response->assertSee('100% Produk Terverifikasi');
        $response->assertSee('Layanan COD');
        $response->assertSee('Bebas Biaya Admin');

        // Verify single static hero showcase & floating badges
        $response->assertSee('hero-image-showcase', false);
        $response->assertSee('hero-static-img', false);
        $response->assertSee('100% Original');
        $response->assertSee('Garansi Resmi');

        // Verify carousel elements have been completely removed
        $response->assertDontSee('heroMarketplaceCarousel', false);
        $response->assertDontSee('carousel-indicators', false);
        $response->assertDontSee('carousel-control-prev', false);
        $response->assertDontSee('carousel-control-next', false);
        $response->assertDontSee('carousel-inner', false);
    }
}
