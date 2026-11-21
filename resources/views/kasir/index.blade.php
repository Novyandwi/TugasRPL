@extends('layouts.app')

@push('styles')
<style>
.pos-layout { display: grid; grid-template-columns: 1fr 360px; gap: 1.5rem; padding: 1.5rem; min-height: calc(100vh - 56px); }
.pos-main h2 { font-size: 1.3rem; font-weight: 700; margin-bottom: .3rem; }
.pos-main p  { font-size: .82rem; color: #94a3b8; margin-bottom: 1.2rem; }

/* Filter tabs */
.filter-tabs { display: flex; gap: .5rem; margin-bottom: 1.5rem; flex-wrap: wrap; }
.filter-btn {
    padding: .4rem 1rem; border-radius: 20px; border: 1.5px solid #e2e8f0;
    font-size: .82rem; font-weight: 600; cursor: pointer;
    background: #fff; color: #64748b; transition: all .15s;
}
.filter-btn.active { background: #f59e0b; color: #fff; border-color: #f59e0b; }

/* Menu grid */
.menu-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(190px, 1fr)); gap: 1rem; }
.menu-card {
    background: #fff; border-radius: 12px; padding: 1rem;
    text-align: center; box-shadow: 0 1px 4px rgba(0,0,0,.07);
    transition: transform .15s, box-shadow .15s;
}
.menu-card:not(.habis):hover { transform: translateY(-2px); box-shadow: 0 4px 16px rgba(0,0,0,.1); }
.menu-card.habis { opacity: .6; }
.menu-emoji { font-size: 2.5rem; margin-bottom: .5rem; }
.menu-nama  { font-weight: 700; font-size: .9rem; margin-bottom: .2rem; }
.menu-kategori { font-size: .7rem; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; margin-bottom: .4rem; }
.menu-harga  { color: #ef4444; font-weight: 700; font-size: .95rem; margin-bottom: .3rem; }
.menu-stok   { font-size: .75rem; color: #64748b; margin-bottom: .75rem; }
.btn-tambah  {
    width: 100%; padding: .4rem; border-radius: 8px; border: none;
    font-size: .82rem; font-weight: 600; cursor: pointer;
    background: #f59e0b; color: #fff;
}
.btn-tambah:hover:not(:disabled) { background: #d97706; }
.btn-tambah:disabled { background: #e2e8f0; color: #94a3b8; cursor: not-allowed; }

/* POS sidebar */
.pos-sidebar {}
.sidebar-card { background: #fff; border-radius: 12px; padding: 1.2rem; box-shadow: 0 1px 4px rgba(0,0,0,.07); }
.sidebar-card + .sidebar-card { margin-top: 1rem; }
.sidebar-title { font-size: .88rem; font-weight: 700; margin-bottom: 1rem; display: flex; align-items: center; gap: .4rem; }

/* Cart item */
.cart-item { display: flex; align-items: center; gap: .5rem; padding: .5rem 0; border-bottom: 1px solid #f1f5f9; }
.cart-item:last-child { border-bottom: none; }
.cart-nama  { flex: 1; font-size: .85rem; font-weight: 600; }
.cart-harga { font-size: .78rem; color: #94a3b8; }
.qty-ctrl   { display: flex; align-items: center; gap: .4rem; }
.qty-btn    {
    width: 24px; height: 24px; border-radius: 6px; border: 1.5px solid #e2e8f0;
    background: #f8fafc; cursor: pointer; font-size: .9rem; font-weight: 700; display: flex; align-items: center; justify-content: center;
}
.qty-num    { font-weight: 700; font-size: .9rem; min-width: 24px; text-align: center; }

/* Total section */
.total-row { display: flex; justify-content: space-between; align-items: center; padding: .3rem 0; font-size: .88rem; }
.total-row.big { font-size: 1rem; font-weight: 700; color: #22c55e; padding: .5rem 0; border-top: 2px solid #e2e8f0; margin-top: .3rem; }
.kembalian-box { background: #dcfce7; border-radius: 8px; padding: .5rem .75rem; font-size: .88rem; font-weight: 600; color: #166534; margin-top: .5rem; }

/* Antrean aktif */
.antrean-item {
    background: #f8fafc; border-radius: 8px; padding: .6rem .75rem;
    font-size: .82rem; color: #64748b;
    margin-bottom: .5rem;
}
.antrean-num { font-weight: 700; color: #0f172a; }
</style>
@endpush

@section('content')
<div class="pos-layout">
    <!-- KIRI: Menu -->
    <div class="pos-main">
        <h2>🖥️ Sistem Kasir (Point of Sale)</h2>
        <p>Pilih menu yang dipesan oleh pelanggan</p>

        <div class="filter-tabs">
            <button class="filter-btn active" data-filter="semua">Semua</button>
            <button class="filter-btn" data-filter="Makanan">Makanan</button>
            <button class="filter-btn" data-filter="Minuman">Minuman</button>
            <button class="filter-btn" data-filter="Camilan">Camilan</button>
            <button class="filter-btn" data-filter="Dessert">Dessert</button>
        </div>

        <div class="menu-grid" id="menuGrid">
            @foreach($menus as $menu)
            <div class="menu-card {{ $menu->isHabis() ? 'habis' : '' }}" data-kategori="{{ $menu->kategori }}">
                <div class="menu-emoji">{{ $menu->emoji }}</div>
                <div class="menu-nama">{{ $menu->nama }}</div>
                <div class="menu-kategori">{{ $menu->kategori }}</div>
                <div class="menu-harga">Rp {{ number_format($menu->harga, 0, ',', '.') }}</div>
                <div class="menu-stok">{{ $menu->isHabis() ? 'Stok Habis' : 'Sisa Stok: ' . $menu->stok }}</div>
                <button class="btn-tambah"
                    {{ $menu->isHabis() ? 'disabled' : '' }}
                    onclick="addToCart({{ $menu->id }}, '{{ addslashes($menu->nama) }}', {{ $menu->harga }}, {{ $menu->stok }})">
                    {{ $menu->isHabis() ? 'Habis' : '+ Tambah' }}
                </button>
            </div>
            @endforeach
        </div>
    </div>

    <!-- KANAN: Sidebar POS -->
    <div class="pos-sidebar">
        <div class="sidebar-card">
            <div class="sidebar-title">🛒 Form Pembayaran Kasir</div>

            <div class="form-group">
                <label style="font-size:.8rem;font-weight:600;color:#475569">Nama Pelanggan:</label>
                <input type="text" id="namaPelanggan" class="form-control" placeholder="Contoh: Budi">
            </div>

            <div id="cartItems" style="margin:1rem 0;min-height:40px">
                <p style="color:#94a3b8;font-size:.82rem;text-align:center;padding:.5rem">Belum ada item dipilih</p>
            </div>

            <div id="totalSection" style="display:none">
                <div class="total-row big">
                    <span>Total:</span>
                    <span id="totalDisplay">Rp 0</span>
                </div>

                <div class="form-group" style="margin-top:.75rem">
                    <label style="font-size:.8rem;font-weight:600;color:#475569">Uang Dibayar (Rp):</label>
                    <input type="number" id="uangDibayar" class="form-control" placeholder="Masukkan nominal uang" oninput="hitungKembalian()">
                </div>

                <div class="kembalian-box" id="kembalianBox" style="display:none">Kembalian: Rp 0</div>

                <button class="btn btn-success btn-block btn-lg" style="margin-top:1rem" onclick="prosesTransaksi()">Proses Transaksi</button>
            </div>
        </div>

        <!-- Antrean Aktif -->
        <div class="sidebar-card">
            <div class="sidebar-title">🧾 Antrean Dapur Aktif</div>
            <div id="antreanList">
                <p style="color:#94a3b8;font-size:.82rem">Tidak ada antrean aktif saat ini.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Cart state
let cart = {};
let totalAmount = 0;

function addToCart(id, nama, harga, stok) {
    if (cart[id]) {
        if (cart[id].qty >= stok) { alert('Stok tidak mencukupi!'); return; }
        cart[id].qty++;
    } else {
        cart[id] = { nama, harga, qty: 1, stok };
    }
    renderCart();
}

function changeQty(id, delta) {
    if (!cart[id]) return;
    cart[id].qty += delta;
    if (cart[id].qty <= 0) delete cart[id];
    renderCart();
}

function renderCart() {
    const container = document.getElementById('cartItems');
    const totalSec  = document.getElementById('totalSection');
    const keys = Object.keys(cart);

    if (keys.length === 0) {
        container.innerHTML = '<p style="color:#94a3b8;font-size:.82rem;text-align:center;padding:.5rem">Belum ada item dipilih</p>';
        totalSec.style.display = 'none';
        totalAmount = 0;
        return;
    }

    totalAmount = 0;
    let html = '';
    keys.forEach(id => {
        const item = cart[id];
        const sub  = item.harga * item.qty;
        totalAmount += sub;
        html += `<div class="cart-item">
            <div>
                <div class="cart-nama">${item.nama}</div>
                <div class="cart-harga">Rp ${fmt(item.harga)} × ${item.qty} = Rp ${fmt(sub)}</div>
            </div>
            <div class="qty-ctrl">
                <button class="qty-btn" onclick="changeQty(${id}, -1)">−</button>
                <span class="qty-num">${item.qty}</span>
                <button class="qty-btn" onclick="changeQty(${id}, 1)">+</button>
            </div>
        </div>`;
    });
    container.innerHTML = html;
    document.getElementById('totalDisplay').textContent = 'Rp ' + fmt(totalAmount);
    totalSec.style.display = 'block';
    hitungKembalian();
}

function hitungKembalian() {
    const uang = parseInt(document.getElementById('uangDibayar').value) || 0;
    const box  = document.getElementById('kembalianBox');
    if (uang > 0) {
        box.style.display = 'block';
        const kem = uang - totalAmount;
        box.textContent = 'Kembalian: Rp ' + fmt(Math.max(0, kem));
        box.style.background = kem < 0 ? '#fee2e2' : '#dcfce7';
        box.style.color      = kem < 0 ? '#991b1b' : '#166534';
    } else {
        box.style.display = 'none';
    }
}

function fmt(n) {
    return n.toLocaleString('id-ID');
}

async function prosesTransaksi() {
    const nama = document.getElementById('namaPelanggan').value.trim();
    const uang = parseInt(document.getElementById('uangDibayar').value) || 0;
    const keys = Object.keys(cart);

    if (!nama)       { alert('Masukkan nama pelanggan!'); return; }
    if (keys.length === 0) { alert('Tambahkan item ke keranjang!'); return; }
    if (uang < totalAmount) { alert('Uang dibayar kurang!'); return; }

    const items = keys.map(id => ({ id: parseInt(id), qty: cart[id].qty }));

    const res = await fetch('{{ route("kasir.transaksi") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ nama_pelanggan: nama, items, uang_dibayar: uang }),
    });

    const data = await res.json();
    if (data.success) {
        alert(`✅ Transaksi Berhasil!\nNomor Antrean: #${data.nomor_antrean}\nKembalian: Rp ${fmt(data.kembalian)}`);
        cart = {};
        document.getElementById('namaPelanggan').value = '';
        document.getElementById('uangDibayar').value   = '';
        renderCart();
        location.reload();
    } else {
        alert('❌ ' + (data.error || 'Gagal memproses transaksi.'));
    }
}

// Filter
document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const filter = btn.dataset.filter;
        document.querySelectorAll('.menu-card').forEach(card => {
            card.style.display = (filter === 'semua' || card.dataset.kategori === filter) ? '' : 'none';
        });
    });
});

// Poll antrean
async function loadAntrean() {
    const res  = await fetch('{{ route("kasir.antrean") }}');
    const data = await res.json();
    const el   = document.getElementById('antreanList');
    if (data.length === 0) {
        el.innerHTML = '<p style="color:#94a3b8;font-size:.82rem">Tidak ada antrean aktif saat ini.</p>';
        return;
    }
    el.innerHTML = data.map(t =>
        `<div class="antrean-item">
            <span class="antrean-num">#${t.nomor_antrean}</span> — ${t.nama_pelanggan}
            <span style="float:right;color:#94a3b8">${t.created_at}</span>
            <div>${t.items.map(i => `${i.nama_menu} ×${i.qty}`).join(', ')}</div>
        </div>`
    ).join('');
}
loadAntrean();
setInterval(loadAntrean, 5000);
</script>
@endpush
