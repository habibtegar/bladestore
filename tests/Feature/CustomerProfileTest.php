<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile_page_and_is_redirected_to_login(): void
    {
        $response = $this->get(route('profile.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_customer_can_view_profile_page(): void
    {
        $user = User::factory()->create([
            'name' => 'Ahmad Pelanggan',
            'email' => 'ahmad@example.com',
            'phone' => '081234567890',
            'role' => 'customer',
        ]);

        Order::create([
            'order_code' => 'ORD-TEST-001',
            'user_id' => $user->id,
            'total_price' => 250000,
            'payment_method' => 'Midtrans Payment Gateway',
            'status' => 'diproses',
            'shipping_address' => 'Jl. Kenanga No. 12, Jakarta',
            'customer_phone' => '081234567890',
        ]);

        $response = $this->actingAs($user)->get(route('profile.index'));

        $response->assertStatus(200);
        $response->assertSee('Ahmad Pelanggan');
        $response->assertSee('ahmad@example.com');
        $response->assertSee('081234567890');
        $response->assertSee('Ringkasan Akun');
        $response->assertSee('Informasi Pribadi');
        $response->assertSee('Buku Alamat');
        $response->assertSee('Kata Sandi');
        $response->assertSee('Pesanan Saya');
        $response->assertSee('Keluar / Logout');
        $response->assertSee('ORD-TEST-001');
    }

    public function test_customer_can_update_profile_information_successfully(): void
    {
        $user = User::factory()->create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'phone' => '081111111111',
        ]);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'Budi Prakoso',
            'email' => 'budi.baru@example.com',
            'phone' => '082222222222',
        ]);

        $response->assertRedirect(route('profile.index', ['tab' => 'personal']));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Budi Prakoso',
            'email' => 'budi.baru@example.com',
            'phone' => '082222222222',
        ]);
    }

    public function test_customer_cannot_update_profile_with_duplicate_email(): void
    {
        User::factory()->create([
            'email' => 'existing@example.com',
        ]);

        $user = User::factory()->create([
            'name' => 'User Kedua',
            'email' => 'user2@example.com',
        ]);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'User Kedua Update',
            'email' => 'existing@example.com',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertEquals('user2@example.com', $user->fresh()->email);
    }

    public function test_customer_can_change_password_with_correct_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('old-password-123'),
        ]);

        $response = $this->actingAs($user)->patch(route('profile.password.update'), [
            'current_password' => 'old-password-123',
            'password' => 'new-secret-456',
            'password_confirmation' => 'new-secret-456',
        ]);

        $response->assertRedirect(route('profile.index', ['tab' => 'security']));
        $response->assertSessionHas('success');

        $this->assertTrue(Hash::check('new-secret-456', $user->fresh()->password));
    }

    public function test_customer_cannot_change_password_with_wrong_current_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('correct-password'),
        ]);

        $response = $this->actingAs($user)->patch(route('profile.password.update'), [
            'current_password' => 'wrong-password',
            'password' => 'new-secret-456',
            'password_confirmation' => 'new-secret-456',
        ]);

        $response->assertSessionHasErrors('current_password');
        $this->assertTrue(Hash::check('correct-password', $user->fresh()->password));
    }

    public function test_customer_can_add_new_shipping_address(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('addresses.store'), [
            'label' => 'Rumah',
            'recipient_name' => 'Budi Santoso',
            'phone' => '081234567890',
            'full_address' => 'Jl. Sudirman No. 45 RT 02/05',
            'city' => 'Jakarta Pusat',
            'postal_code' => '10220',
            'is_primary' => '1',
        ]);

        $response->assertRedirect(route('profile.index', ['tab' => 'addresses']));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'label' => 'Rumah',
            'recipient_name' => 'Budi Santoso',
            'phone' => '081234567890',
            'full_address' => 'Jl. Sudirman No. 45 RT 02/05',
            'city' => 'Jakarta Pusat',
            'postal_code' => '10220',
            'is_primary' => true,
        ]);
    }

    public function test_first_address_added_automatically_becomes_primary(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post(route('addresses.store'), [
            'label' => 'Kantor',
            'recipient_name' => 'Rina Wijaya',
            'phone' => '089876543210',
            'full_address' => 'Gedung Cyber Lt. 5, Kuningan',
            'city' => 'Jakarta Selatan',
            'postal_code' => '12950',
            // is_primary tidak dicentang
        ]);

        $address = Address::where('user_id', $user->id)->first();
        $this->assertNotNull($address);
        $this->assertTrue($address->is_primary);
    }

    public function test_customer_can_update_shipping_address(): void
    {
        $user = User::factory()->create();
        $address = Address::factory()->create([
            'user_id' => $user->id,
            'label' => 'Rumah Lama',
            'full_address' => 'Alamat lama no 1',
        ]);

        $response = $this->actingAs($user)->patch(route('addresses.update', $address), [
            'label' => 'Rumah Baru',
            'recipient_name' => $address->recipient_name,
            'phone' => $address->phone,
            'full_address' => 'Alamat baru no 99',
            'city' => 'Bandung',
            'postal_code' => '40111',
            'is_primary' => '1',
        ]);

        $response->assertRedirect(route('profile.index', ['tab' => 'addresses']));

        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'label' => 'Rumah Baru',
            'full_address' => 'Alamat baru no 99',
            'city' => 'Bandung',
        ]);
    }

    public function test_customer_can_set_address_as_primary(): void
    {
        $user = User::factory()->create();
        $address1 = Address::factory()->create([
            'user_id' => $user->id,
            'is_primary' => true,
        ]);
        $address2 = Address::factory()->create([
            'user_id' => $user->id,
            'is_primary' => false,
        ]);

        $response = $this->actingAs($user)->patch(route('addresses.set-primary', $address2));

        $response->assertRedirect(route('profile.index', ['tab' => 'addresses']));
        $this->assertFalse($address1->fresh()->is_primary);
        $this->assertTrue($address2->fresh()->is_primary);
    }

    public function test_customer_can_delete_shipping_address(): void
    {
        $user = User::factory()->create();
        $address = Address::factory()->create([
            'user_id' => $user->id,
            'label' => 'Alamat Hapus',
        ]);

        $response = $this->actingAs($user)->delete(route('addresses.destroy', $address));

        $response->assertRedirect(route('profile.index', ['tab' => 'addresses']));
        $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
    }

    public function test_customer_cannot_update_or_delete_another_customers_address(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $addressOfUser2 = Address::factory()->create([
            'user_id' => $user2->id,
            'label' => 'Alamat Milik User 2',
        ]);

        // Attempt update by User 1
        $updateResponse = $this->actingAs($user1)->patch(route('addresses.update', $addressOfUser2), [
            'label' => 'Hacked Label',
            'recipient_name' => 'Hacker',
            'phone' => '081234567890',
            'full_address' => 'Jl. Ilegal No. 1',
        ]);
        $updateResponse->assertStatus(403);

        // Attempt delete by User 1
        $deleteResponse = $this->actingAs($user1)->delete(route('addresses.destroy', $addressOfUser2));
        $deleteResponse->assertStatus(403);

        // Attempt set primary by User 1
        $primaryResponse = $this->actingAs($user1)->patch(route('addresses.set-primary', $addressOfUser2));
        $primaryResponse->assertStatus(403);

        // Verify address remains untouched
        $this->assertEquals('Alamat Milik User 2', $addressOfUser2->fresh()->label);
    }

    public function test_navbar_profile_links_navigate_to_profile_page(): void
    {
        $user = User::factory()->create(['name' => 'Siti Khadijah']);

        $response = $this->actingAs($user)->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee(route('profile.index'));
        $response->assertSee('My Profile');
        $response->assertSee('Pengaturan Akun');
    }
}
