<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    // Halaman Daftar Pengguna
    public function index()
    {
        $users = User::latest()->get();
        return view('users.index', compact('users'));
    }

    // Halaman Tambah Pengguna (Ada Combo Box nanti)
    public function create()
    {
        return view('users.create');
    }

    // Proses Simpan ke Database
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'string'], // Admin/Pimpinan/Kepala
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna baru berhasil ditambahkan!');
    }

    // Halaman Edit
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Proses Update
    // PROSES UPDATE PENGGUNA
    public function update(Request $request, User $user)
    {
        // Validasi input
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Email harus unik, tapi kecualikan (ignore) email milik user yang sedang diedit ini
            'email' => ['required', 'email', 'unique:users,email,'.$user->id],
            'role' => ['required', 'string'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Cek apakah kolom password diisi?
        if ($request->filled('password')) {
            // Kalau diisi, validasi konfirmasinya, lalu enkripsi password baru
            $request->validate(['password' => ['confirmed', Rules\Password::defaults()]]);
            $data['password'] = Hash::make($request->password);
        }

        // Update data ke database
        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui!');
    }

    // Hapus User
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'Pengguna dihapus.');
    }
}
