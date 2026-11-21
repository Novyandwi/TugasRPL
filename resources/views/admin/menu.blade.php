@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>⚙️ Panel Administrator</h1>
        <p>Kelola menu, data akun staf (Kasir &amp; Kitchen), dan laporan pendapatan</p>
    </div>

    <div class="tabs">
        <button class="tab-btn active" onclick="window.location='{{ route('admin.menu') }}'">🍔 Kelola Menu &amp; Stok</button>
        <button class="tab-btn" onclick="window.location='{{ route('admin.users') }}'">👥 Kelola Pengguna (Staf)</button>
        <button class="tab-btn" onclick="window.location='{{ route('admin.laporan') }}'">📊 Laporan Penjualan</button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div style="display:grid;grid-template-columns:320px 1fr;gap:1.5rem;align-items:start">
        <!-- FORM TAMBAH -->
        <div class="card">
            <h3 style="font-size:.95rem;font-weight:700;margin-bottom:1rem">+ Tambah Menu Baru</h3>
            <form method="POST" action="{{ route('admin.menu.store') }}">
                @csrf
                <div class="form-group">
                    <label>Nama Menu:</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Kategori:</label>
                    <select name="kategori" class="form-control">
                        <option>Makanan</option>
                        <option>Minuman</option>
                        <option>Camilan</option>
                        <option>Dessert</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Harga (Rp):</label>
                    <input type="number" name="harga" class="form-control" min="0" required>
                </div>
                <div class="form-group">
                    <label>Stok Awal:</label>
                    <input type="number" name="stok" class="form-control" min="0" required>
                </div>
                <div class="form-group">
                    <label>Emoji/Ikon:</label>
                    <input type="text" name="emoji" class="form-control" value="🍽️" maxlength="10">
                </div>
                <button type="submit" class="btn btn-success btn-block btn-lg">Simpan Menu</button>
            </form>
        </div>

        <!-- TABEL MENU -->
        <div class="card">
            <h3 style="font-size:.95rem;font-weight:700;margin-bottom:1.2rem">Daftar Menu &amp; Stok</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Ikon</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($menus as $menu)
                    <tr>
                        <td style="font-size:1.3rem">{{ $menu->emoji }}</td>
                        <td style="font-weight:600">{{ $menu->nama }}</td>
                        <td style="color:#94a3b8;font-size:.82rem">{{ $menu->kategori }}</td>
                        <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                        <td class="{{ $menu->stok <= 0 ? 'stok-habis' : 'stok-ok' }}">{{ $menu->stok }}</td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="openEditMenu({{ $menu->id }}, '{{ addslashes($menu->nama) }}', '{{ $menu->kategori }}', {{ $menu->harga }}, {{ $menu->stok }}, '{{ $menu->emoji }}')">Edit</button>
                            <form method="POST" action="{{ route('admin.menu.destroy', $menu) }}" style="display:inline" onsubmit="return confirm('Hapus menu ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL EDIT MENU -->
<div class="modal-overlay" id="modalEditMenu">
    <div class="modal">
        <h3>✏️ Edit Menu</h3>
        <form id="formEditMenu" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Nama Menu:</label>
                <input type="text" name="nama" id="edit_nama" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Kategori:</label>
                <select name="kategori" id="edit_kategori" class="form-control">
                    <option>Makanan</option>
                    <option>Minuman</option>
                    <option>Camilan</option>
                    <option>Dessert</option>
                </select>
            </div>
            <div class="form-group">
                <label>Harga (Rp):</label>
                <input type="number" name="harga" id="edit_harga" class="form-control" min="0" required>
            </div>
            <div class="form-group">
                <label>Stok:</label>
                <input type="number" name="stok" id="edit_stok" class="form-control" min="0" required>
            </div>
            <div class="form-group">
                <label>Emoji:</label>
                <input type="text" name="emoji" id="edit_emoji" class="form-control" maxlength="10">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditMenu')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openEditMenu(id, nama, kategori, harga, stok, emoji) {
    document.getElementById('formEditMenu').action = '/admin/menu/' + id;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_kategori').value = kategori;
    document.getElementById('edit_harga').value = harga;
    document.getElementById('edit_stok').value = stok;
    document.getElementById('edit_emoji').value = emoji;
    document.getElementById('modalEditMenu').classList.add('open');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}
</script>
@endpush
