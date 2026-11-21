<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KasirController extends Controller
{
    public function index()
    {
        $menus = Menu::orderBy('kategori')->orderBy('nama')->get();
        $antreanAktif = Transaksi::where('status', 'pending')
            ->with('items')
            ->orderBy('nomor_antrean')
            ->get();
        return view('kasir.index', compact('menus', 'antreanAktif'));
    }

    public function transaksi(Request $request)
    {
        $request->validate([
            'nama_pelanggan' => 'required|string|max:100',
            'items'          => 'required|array|min:1',
            'items.*.id'     => 'required|exists:menus,id',
            'items.*.qty'    => 'required|integer|min:1',
            'uang_dibayar'   => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $total = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $menu = Menu::lockForUpdate()->find($item['id']);
                if (!$menu || $menu->stok < $item['qty']) {
                    DB::rollBack();
                    return response()->json(['error' => "Stok {$menu->nama} tidak cukup."], 422);
                }
                $subtotal = $menu->harga * $item['qty'];
                $total += $subtotal;
                $itemsData[] = [
                    'menu'     => $menu,
                    'qty'      => $item['qty'],
                    'subtotal' => $subtotal,
                ];
            }

            if ($request->uang_dibayar < $total) {
                DB::rollBack();
                return response()->json(['error' => 'Uang dibayar kurang dari total.'], 422);
            }

            // Nomor antrean harian
            $nomor = Transaksi::whereDate('created_at', today())->count() + 1;

            $transaksi = Transaksi::create([
                'nomor_antrean'  => $nomor,
                'nama_pelanggan' => $request->nama_pelanggan,
                'total'          => $total,
                'uang_dibayar'   => $request->uang_dibayar,
                'kembalian'      => $request->uang_dibayar - $total,
                'status'         => 'pending',
                'kasir_id'       => auth()->id(),
            ]);

            foreach ($itemsData as $i) {
                TransaksiItem::create([
                    'transaksi_id' => $transaksi->id,
                    'menu_id'      => $i['menu']->id,
                    'nama_menu'    => $i['menu']->nama,
                    'harga'        => $i['menu']->harga,
                    'qty'          => $i['qty'],
                    'subtotal'     => $i['subtotal'],
                ]);
                $i['menu']->decrement('stok', $i['qty']);
            }

            DB::commit();

            return response()->json([
                'success'       => true,
                'nomor_antrean' => $nomor,
                'kembalian'     => $request->uang_dibayar - $total,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => 'Terjadi kesalahan sistem.'], 500);
        }
    }

    public function getAntrean()
    {
        $antrean = Transaksi::where('status', 'pending')
            ->with('items')
            ->orderBy('nomor_antrean')
            ->get()
            ->map(fn($t) => [
                'id'             => $t->id,
                'nomor_antrean'  => $t->nomor_antrean,
                'nama_pelanggan' => $t->nama_pelanggan,
                'created_at'     => $t->created_at->format('H:i'),
                'items'          => $t->items->map(fn($i) => [
                    'nama_menu' => $i->nama_menu,
                    'qty'       => $i->qty,
                ]),
            ]);

        return response()->json($antrean);
    }
}
