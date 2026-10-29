<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
