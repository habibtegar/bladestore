<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle user login.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->isAdmin() && ! $request->session()->has('url.intended')) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Selamat datang di Panel Admin, '.Auth::user()->name.'!');
            }

            return redirect()->intended(route('products.index'))
                ->with('success', 'Selamat datang kembali, '.Auth::user()->name.'!');
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    /**
     * Show registration form.
     */
    public function showRegisterForm(): View
    {
        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('products.index')
            ->with('success', 'Pendaftaran berhasil! Selamat datang di toko kami.');
    }

    /**
     * Quick 1-click login for demo / local testing as Demo Customer.
     */
    public function demoLogin(Request $request): RedirectResponse
    {
        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Demo Customer',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ]
        );

        if ($user->role !== 'customer') {
            $user->update(['role' => 'customer']);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('products.index'))
            ->with('success', 'Berhasil login sebagai Demo Customer (user@example.com)!');
    }

    /**
     * Quick 1-click login for demo / local testing as Administrator.
     */
    public function demoAdminLogin(Request $request): RedirectResponse
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin BladeStore',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        if ($user->role !== 'admin') {
            $user->update(['role' => 'admin']);
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'))
            ->with('success', 'Berhasil login sebagai Administrator (admin@example.com)!');
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('products.index')
            ->with('success', 'Anda telah berhasil keluar.');
    }
}
