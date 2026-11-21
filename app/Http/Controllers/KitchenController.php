<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    public function index()
    {
        $antrean = Transaksi::where('status', 'pending')
            ->with('items')
            ->orderBy('nomor_antrean')
            ->get();
        return view('kitchen.index', compact('antrean'));
    }

    public function selesai(Transaksi $transaksi)
    {
        $transaksi->update(['status' => 'selesai']);
        return response()->json(['success' => true]);
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
