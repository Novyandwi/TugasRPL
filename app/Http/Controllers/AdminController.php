<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // ===================== DASHBOARD =====================
    public function dashboard()
    {
        return redirect()->route('admin.menu');
    }

    // ===================== MENU & STOK =====================
    public function menu()
    {
        $menus = Menu::orderBy('kategori')->orderBy('nama')->get();
        return view('admin.menu', compact('menus'));
    }

    public function menuStore(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'kategori' => 'required|in:Makanan,Minuman,Camilan,Dessert',
            'harga'    => 'required|integer|min:0',
            'stok'     => 'required|integer|min:0',
            'emoji'    => 'nullable|string|max:10',
        ]);

        Menu::create([
            'nama'     => $request->nama,
            'kategori' => $request->kategori,
            'harga'    => $request->harga,
            'stok'     => $request->stok,
            'emoji'    => $request->emoji ?: '🍽️',
        ]);

        return back()->with('success', 'Menu berhasil ditambahkan.');
    }

    public function menuUpdate(Request $request, Menu $menu)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'kategori' => 'required|in:Makanan,Minuman,Camilan,Dessert',
            'harga'    => 'required|integer|min:0',
            'stok'     => 'required|integer|min:0',
            'emoji'    => 'nullable|string|max:10',
        ]);

        $menu->update([
            'nama'     => $request->nama,
            'kategori' => $request->kategori,
            'harga'    => $request->harga,
            'stok'     => $request->stok,
            'emoji'    => $request->emoji ?: $menu->emoji,
        ]);

        return back()->with('success', 'Menu berhasil diperbarui.');
    }

    public function menuDestroy(Menu $menu)
    {
        $menu->delete();
        return back()->with('success', 'Menu berhasil dihapus.');
    }

    // ===================== USERS =====================
    public function users()
    {
        $users = User::orderBy('role')->orderBy('nama_lengkap')->get();
        return view('admin.users', compact('users'));
    }

    public function userStore(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username'     => 'required|string|unique:users,username|max:50',
            'password'     => 'required|string|min:6',
            'role'         => 'required|in:admin,kasir,kitchen',
        ]);

        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username'     => $request->username,
            'password'     => Hash::make($request->password),
            'role'         => $request->role,
        ]);

        return back()->with('success', 'Akun berhasil ditambahkan.');
    }

    public function userUpdate(Request $request, User $user)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'username'     => 'required|string|max:50|unique:users,username,' . $user->id,
            'role'         => 'required|in:admin,kasir,kitchen',
        ]);

        $data = [
            'nama_lengkap' => $request->nama_lengkap,
            'username'     => $request->username,
            'role'         => $request->role,
        ];

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return back()->with('success', 'Akun berhasil diperbarui.');
    }

    public function userDestroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun sendiri.');
        }
        $user->delete();
        return back()->with('success', 'Akun berhasil dihapus.');
    }

    // ===================== LAPORAN =====================
    public function laporan()
    {
        $harian = Transaksi::selectRaw('DATE(created_at) as tanggal, COUNT(*) as total_transaksi, SUM(total) as pendapatan')
            ->where('status', 'selesai')
            ->groupBy('tanggal')
            ->orderByDesc('tanggal')
            ->limit(30)
            ->get();

       $driver = Transaksi::query()->getConnection()->getDriverName();

$formatBulan = $driver === 'sqlite'
    ? "strftime('%Y-%m', created_at)"
    : "DATE_FORMAT(created_at, '%Y-%m')";

$bulanan = Transaksi::selectRaw("$formatBulan as bulan, COUNT(*) as total_transaksi, SUM(total) as pendapatan")
    ->where('status', 'selesai')
    ->groupBy('bulan')
    ->orderByDesc('bulan')
    ->limit(12)
    ->get();

        return view('admin.laporan', compact('harian', 'bulanan'));
    }
}
