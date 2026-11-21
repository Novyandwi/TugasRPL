@extends('layouts.app')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>⚙️ Panel Administrator</h1>
        <p>Kelola menu, data akun staf (Kasir &amp; Kitchen), dan laporan pendapatan</p>
    </div>

    <div class="tabs">
        <button class="tab-btn" onclick="window.location='{{ route('admin.menu') }}'">🍔 Kelola Menu &amp; Stok</button>
        <button class="tab-btn active" onclick="window.location='{{ route('admin.users') }}'">👥 Kelola Pengguna (Staf)</button>
        <button class="tab-btn" onclick="window.location='{{ route('admin.laporan') }}'">📊 Laporan Penjualan</button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div style="display:grid;grid-template-columns:320px 1fr;gap:1.5rem;align-items:start">
        <!-- FORM TAMBAH USER -->
        <div class="card">
            <h3 style="font-size:.95rem;font-weight:700;margin-bottom:1rem">👤 Tambah Akun Pengguna</h3>
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="form-group">
                    <label>Nama Lengkap Staf:</label>
                    <input type="text" name="nama_lengkap" class="form-control" placeholder="Contoh: Budi Kasir" required>
                </div>
                <div class="form-group">
                    <label>Username Login:</label>
                    <input type="text" name="username" class="form-control" placeholder="Contoh: kasir2" required>
                </div>
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
                <div class="form-group">
                    <label>Kategori / Role Akses:</label>
                    <select name="role" class="form-control">
                        <option value="kasir">🖥️ Kasir</option>
                        <option value="kitchen">👨‍🍳 Kitchen</option>
                        <option value="admin">⚙️ Admin</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success btn-block btn-lg">Tambah Akun</button>
            </form>
        </div>

        <!-- TABEL USERS -->
        <div class="card">
            <h3 style="font-size:.95rem;font-weight:700;margin-bottom:1.2rem">Daftar Pengguna Sistem POS</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Staf</th>
                        <th>Username</th>
                        <th>Kategori (Role)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td style="font-weight:600">{{ $user->nama_lengkap }}</td>
                        <td style="color:#94a3b8">{{ $user->username }}</td>
                        <td>
                            @php
                                $badgeColor = match($user->role) {
                                    'admin'   => '#fef9c3; color:#854d0e',
                                    'kasir'   => '#dcfce7; color:#166534',
                                    'kitchen' => '#dbeafe; color:#1e40af',
                                    default   => '#f1f5f9; color:#475569',
                                };
                            @endphp
                            <span style="background:{{ $badgeColor }}; padding:.2rem .6rem; border-radius:20px; font-size:.75rem; font-weight:600;">
                                {{ $user->getRoleLabel() }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-primary btn-sm" onclick="openEditUser({{ $user->id }}, '{{ addslashes($user->nama_lengkap) }}', '{{ $user->username }}', '{{ $user->role }}')">Edit</button>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline" onsubmit="return confirm('Hapus akun ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL EDIT USER -->
<div class="modal-overlay" id="modalEditUser">
    <div class="modal">
        <h3>✏️ Edit Akun</h3>
        <form id="formEditUser" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Nama Lengkap:</label>
                <input type="text" name="nama_lengkap" id="edit_nama_lengkap" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" id="edit_username" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password Baru (kosongkan jika tidak diubah):</label>
                <input type="password" name="password" class="form-control" placeholder="Isi untuk ubah password">
            </div>
            <div class="form-group">
                <label>Role:</label>
                <select name="role" id="edit_role" class="form-control">
                    <option value="kasir">🖥️ Kasir</option>
                    <option value="kitchen">👨‍🍳 Kitchen</option>
                    <option value="admin">⚙️ Admin</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditUser')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openEditUser(id, nama, username, role) {
    document.getElementById('formEditUser').action = '/admin/users/' + id;
    document.getElementById('edit_nama_lengkap').value = nama;
    document.getElementById('edit_username').value = username;
    document.getElementById('edit_role').value = role;
    document.getElementById('modalEditUser').classList.add('open');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('open');
}
</script>
@endpush
