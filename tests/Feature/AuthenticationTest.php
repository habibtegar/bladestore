<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders_clean_split_screen_layout_without_hardcoded_credentials(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('Masuk ke Akun');
        $response->assertSee('auth-container');
        $response->assertSee('auth-brand-panel');
        $response->assertSee('auth-form-panel');
        $response->assertSee('name="email"', false);
        $response->assertSee('name="password"', false);
        $response->assertSee('name="remember"', false);
        $response->assertSee('Masuk Sekarang');
        $response->assertSee('Login Customer');
        $response->assertSee('Login Admin');
        $response->assertSee('togglePasswordVisibility');

        // Pastikan tidak ada credential contoh yang di-hardcode sebagai nilai awal input
        $response->assertDontSee('value="user@example.com"', false);
        $response->assertDontSee('value="password"', false);
    }

    public function test_login_validation_fails_with_invalid_credentials_and_displays_error(): void
    {
        $user = User::factory()->create([
            'email' => 'validuser@example.com',
            'password' => Hash::make('secret123'),
        ]);

        $response = $this->from(route('login'))->post(route('login'), [
            'email' => 'validuser@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_user_can_login_successfully(): void
    {
        $user = User::factory()->create([
            'email' => 'userlogin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'customer',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'userlogin@example.com',
            'password' => 'password123',
            'remember' => '1',
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_register_page_renders_clean_form_with_matching_styles(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
        $response->assertSee('Daftar Akun Baru');
        $response->assertSee('auth-container');
        $response->assertSee('auth-brand-panel');
        $response->assertSee('auth-form-panel');
        $response->assertSee('name="name"', false);
        $response->assertSee('name="email"', false);
        $response->assertSee('name="password"', false);
        $response->assertSee('name="password_confirmation"', false);
        $response->assertSee('Daftar Akun Sekarang');
        $response->assertSee(route('login'));
    }

    public function test_user_can_register_successfully_as_customer(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Budi Susanto',
            'email' => 'budisusanto@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('products.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'Budi Susanto',
            'email' => 'budisusanto@example.com',
            'role' => 'customer',
        ]);

        $createdUser = User::where('email', 'budisusanto@example.com')->first();
        $this->assertNotNull($createdUser);
        $this->assertAuthenticatedAs($createdUser);
    }

    public function test_register_validation_fails_when_password_confirmation_does_not_match(): void
    {
        $response = $this->from(route('register'))->post(route('register'), [
            'name' => 'Budi Susanto',
            'email' => 'budisusanto@example.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
        ]);

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('password');
        $this->assertGuest();
    }

    public function test_demo_login_and_demo_admin_login_routes_work_properly(): void
    {
        // 1. Demo Customer Login
        $customerResponse = $this->get(route('demo.login'));
        $customerResponse->assertRedirect(route('products.index'));
        $this->assertAuthenticated();
        $this->assertEquals('customer', auth()->user()->role);

        // Logout
        $this->post(route('logout'));
        $this->assertGuest();

        // 2. Demo Admin Login
        $adminResponse = $this->get(route('demo.admin-login'));
        $adminResponse->assertRedirect(route('admin.orders.index'));
        $this->assertAuthenticated();
        $this->assertEquals('admin', auth()->user()->role);
    }
}
