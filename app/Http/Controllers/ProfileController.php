<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil akun.
     */
    public function index()
    {
        $users = User::where('id', '!=', auth()->id())->orderBy('name')->get();

        return view('profile.index', compact('users'));
    }

    /**
     * Update data profil (nama & email) user yang sedang login.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update($validated);

        return back()->with('success', 'Informasi profil berhasil diperbarui.');
    }

    /**
     * Update password user yang sedang login.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password'      => ['required', 'current_password'],
            'password'              => ['required', 'confirmed', Password::min(8)],
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui. Silakan login ulang jika diperlukan.');
    }

    /**
     * Tambahkan admin baru.
     */
    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', "Admin '{$validated['name']}' berhasil ditambahkan.");
    }

    /**
     * Hapus akun admin lain (tidak bisa hapus diri sendiri).
     */
    public function destroyAdmin(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $name = $user->name;
        $user->delete();

        return back()->with('success', "Akun admin '{$name}' berhasil dihapus.");
    }
}
