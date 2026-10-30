<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the customer profile dashboard page.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();

        $addresses = $user->addresses()
            ->orderByDesc('is_primary')
            ->latest()
            ->get();

        $ordersCount = $user->orders()->count();
        $activeOrdersCount = $user->orders()
            ->whereIn('status', ['menunggu_pembayaran', 'diproses', 'dikirim', 'pending', 'belum_bayar', 'processing', 'dikemas', 'shipped'])
            ->count();
        $completedOrdersCount = $user->orders()
            ->whereIn('status', ['selesai', 'completed'])
            ->count();

        $recentOrders = $user->orders()
            ->with('items.product')
            ->latest()
            ->take(3)
            ->get();

        $tab = $request->query('tab', 'overview');

        return view('profile.index', compact(
            'user',
            'addresses',
            'ordersCount',
            'activeOrdersCount',
            'completedOrdersCount',
            'recentOrders',
            'tab'
        ));
    }

    /**
     * Update the customer's personal profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => ['nullable', 'string', 'min:8', 'max:25'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'phone.min' => 'Nomor telepon minimal 8 karakter.',
            'phone.max' => 'Nomor telepon maksimal 25 karakter.',
        ]);

        $user->update($validated);

        return redirect()
            ->route('profile.index', ['tab' => 'personal'])
            ->with('success', 'Informasi profil pribadi Anda berhasil diperbarui.');
    }

    /**
     * Update the customer's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini yang Anda masukkan tidak sesuai.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('profile.index', ['tab' => 'security'])
            ->with('success', 'Kata sandi Anda berhasil diperbarui. Silakan gunakan kata sandi baru untuk login berikutnya.');
    }
}
