@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>⚙️ Panel Administrator</h1>
        <p>Kelola menu, data akun staf (Kasir &amp; Kitchen), dan laporan pendapatan</p>
    </div>

    <div class="tabs">
        <button class="tab-btn" onclick="window.location='{{ route('admin.menu') }}'">🍔 Kelola Menu &amp; Stok</button>
        <button class="tab-btn" onclick="window.location='{{ route('admin.users') }}'">👥 Kelola Pengguna (Staf)</button>
        <button class="tab-btn active" onclick="window.location='{{ route('admin.laporan') }}'">📊 Laporan Penjualan</button>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">
        <!-- HARIAN -->
        <div class="card">
            <h3 style="font-size:.95rem;font-weight:700;margin-bottom:1.2rem">📅 Pendapatan Harian</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Total Transaksi</th>
                        <th>Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($harian as $row)
                    <tr>
                        <td style="font-weight:600">{{ $row->tanggal }}</td>
                        <td>
                            <a href="#" style="color:#3b82f6;text-decoration:none;font-weight:500">{{ $row->total_transaksi }} Transaksi</a>
                        </td>
                        <td style="color:#22c55e;font-weight:600">Rp {{ number_format($row->pendapatan, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;color:#94a3b8;padding:2rem">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- BULANAN -->
        <div class="card">
            <h3 style="font-size:.95rem;font-weight:700;margin-bottom:1.2rem">📅 Pendapatan Bulanan</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Total Transaksi</th>
                        <th>Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bulanan as $row)
                    <tr>
                        <td style="font-weight:600">{{ $row->bulan }}</td>
                        <td>
                            <a href="#" style="color:#3b82f6;text-decoration:none;font-weight:500">{{ $row->total_transaksi }} Transaksi</a>
                        </td>
                        <td style="color:#22c55e;font-weight:600">Rp {{ number_format($row->pendapatan, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;color:#94a3b8;padding:2rem">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
