<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Store a newly created shipping address in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $validated = $request->validate([
            'label' => ['required', 'string', 'max:50'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'min:8', 'max:25'],
            'full_address' => ['required', 'string', 'min:5'],
            'city' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'is_primary' => ['nullable', 'boolean'],
        ], [
            'label.required' => 'Label alamat (misal: Rumah, Kantor) wajib diisi.',
            'recipient_name.required' => 'Nama penerima wajib diisi.',
            'phone.required' => 'Nomor telepon penerima wajib diisi.',
            'phone.min' => 'Nomor telepon minimal 8 karakter.',
            'full_address.required' => 'Alamat lengkap wajib diisi.',
            'full_address.min' => 'Alamat lengkap minimal 5 karakter.',
        ]);

        $isFirstAddress = ! $user->addresses()->exists();
        $isPrimary = $isFirstAddress || $request->boolean('is_primary');

        if ($isPrimary) {
            $user->addresses()->update(['is_primary' => false]);
        }

        $validated['is_primary'] = $isPrimary;

        $user->addresses()->create($validated);

        return redirect()
            ->route('profile.index', ['tab' => 'addresses'])
            ->with('success', 'Alamat pengiriman baru berhasil ditambahkan.');
    }

    /**
     * Update the specified shipping address in storage.
     */
    public function update(Request $request, Address $address): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($address->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah alamat ini.');
        }

        $validated = $request->validate([
            'label' => ['required', 'string', 'max:50'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'min:8', 'max:25'],
            'full_address' => ['required', 'string', 'min:5'],
            'city' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'is_primary' => ['nullable', 'boolean'],
        ], [
            'label.required' => 'Label alamat (misal: Rumah, Kantor) wajib diisi.',
            'recipient_name.required' => 'Nama penerima wajib diisi.',
            'phone.required' => 'Nomor telepon penerima wajib diisi.',
            'phone.min' => 'Nomor telepon minimal 8 karakter.',
            'full_address.required' => 'Alamat lengkap wajib diisi.',
            'full_address.min' => 'Alamat lengkap minimal 5 karakter.',
        ]);

        $isPrimary = $request->boolean('is_primary');

        if ($isPrimary) {
            $user->addresses()
                ->where('id', '!=', $address->id)
                ->update(['is_primary' => false]);
            $validated['is_primary'] = true;
        } else {
            // Jika alamat ini sebelumnya adalah satu-satunya alamat atau alamat utama, pastikan tetap ada alamat utama
            if ($address->is_primary && $user->addresses()->count() === 1) {
                $validated['is_primary'] = true;
            } else {
                $validated['is_primary'] = false;
            }
        }

        $address->update($validated);

        return redirect()
            ->route('profile.index', ['tab' => 'addresses'])
            ->with('success', 'Alamat pengiriman berhasil diperbarui.');
    }

    /**
     * Remove the specified shipping address from storage.
     */
    public function destroy(Request $request, Address $address): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($address->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk menghapus alamat ini.');
        }

        $wasPrimary = $address->is_primary;

        $address->delete();

        // Jika alamat yang dihapus adalah alamat utama dan masih ada alamat lain, jadikan salah satunya sebagai utama
        if ($wasPrimary) {
            $user->addresses()->latest()->first()?->update(['is_primary' => true]);
        }

        return redirect()
            ->route('profile.index', ['tab' => 'addresses'])
            ->with('success', 'Alamat pengiriman berhasil dihapus.');
    }

    /**
     * Set the specified shipping address as the primary address.
     */
    public function setPrimary(Request $request, Address $address): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($address->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah alamat ini.');
        }

        $address->markAsPrimary();

        return redirect()
            ->route('profile.index', ['tab' => 'addresses'])
            ->with('success', 'Alamat utama berhasil diperbarui.');
    }
}
