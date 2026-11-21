@extends('layouts.app')

@push('styles')
<style>
.kitchen-layout { padding: 1.5rem; }
.kitchen-layout h2 { font-size: 1.3rem; font-weight: 700; margin-bottom: .3rem; display: flex; align-items: center; gap: .5rem; }
.kitchen-layout > p { font-size: .82rem; color: #94a3b8; margin-bottom: 1.5rem; }

.antrean-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.2rem; }

.antrean-card {
    background: #fff;
    border-radius: 12px;
    padding: 1.2rem;
    box-shadow: 0 1px 4px rgba(0,0,0,.07);
    border-top: 4px solid #f59e0b;
}
.antrean-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: .5rem; }
.antrean-num    { font-size: 1rem; font-weight: 700; color: #f59e0b; }
.antrean-time   { font-size: .78rem; color: #94a3b8; }
.antrean-pelanggan { font-size: .85rem; color: #475569; margin-bottom: .75rem; }
.antrean-items  { margin-bottom: 1rem; }
.antrean-item-row { display: flex; justify-content: space-between; padding: .3rem 0; font-size: .88rem; border-bottom: 1px solid #f1f5f9; }
.antrean-item-row:last-child { border-bottom: none; }
.qty-badge { background: #f1f5f9; border-radius: 6px; padding: .15rem .5rem; font-weight: 700; font-size: .82rem; }

.btn-selesai {
    width: 100%; padding: .6rem; background: #22c55e; color: #fff;
    border: none; border-radius: 10px; font-size: .88rem; font-weight: 700;
    cursor: pointer; display: flex; align-items: center; justify-content: center; gap: .4rem;
}
.btn-selesai:hover { background: #16a34a; }

.empty-state {
    background: #fff; border-radius: 12px; padding: 3rem;
    text-align: center; color: #94a3b8; font-size: .9rem;
}
</style>
@endpush

@section('content')
<div class="kitchen-layout">
    <h2>👨‍🍳 Layar Dapur (Kitchen Display)</h2>
    <p>Daftar pesanan masuk yang perlu disiapkan</p>

    <div id="antreanGrid">
        @if($antrean->isEmpty())
        <div class="empty-state">Belum ada pesanan masuk saat ini.</div>
        @else
        <div class="antrean-grid">
            @foreach($antrean as $t)
            <div class="antrean-card" id="card-{{ $t->id }}">
                <div class="antrean-header">
                    <span class="antrean-num">Antrean #{{ $t->nomor_antrean }}</span>
                    <span class="antrean-time">🕐 {{ $t->created_at->format('H.i') }}</span>
                </div>
                <div class="antrean-pelanggan">Pelanggan: <strong>{{ $t->nama_pelanggan }}</strong></div>
                <div class="antrean-items">
                    @foreach($t->items as $item)
                    <div class="antrean-item-row">
                        <span>{{ $item->nama_menu }}</span>
                        <span class="qty-badge">×{{ $item->qty }}</span>
                    </div>
                    @endforeach
                </div>
                <button class="btn-selesai" onclick="selesai({{ $t->id }})">✓ Selesai &amp; Sajikan</button>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
async function selesai(id) {
    const res = await fetch(`/kitchen/selesai/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        }
    });
    const data = await res.json();
    if (data.success) {
        const card = document.getElementById('card-' + id);
        if (card) card.remove();
        checkEmpty();
    }
}

function checkEmpty() {
    const grid = document.querySelector('.antrean-grid');
    if (grid && grid.children.length === 0) {
        document.getElementById('antreanGrid').innerHTML =
            '<div class="empty-state">Belum ada pesanan masuk saat ini.</div>';
    }
}

// Auto-refresh every 5 seconds
async function refreshAntrean() {
    const res  = await fetch('{{ route("kitchen.antrean") }}');
    const data = await res.json();

    const grid = document.getElementById('antreanGrid');
    if (data.length === 0) {
        grid.innerHTML = '<div class="empty-state">Belum ada pesanan masuk saat ini.</div>';
        return;
    }

    // Add new cards that aren't already shown
    let container = grid.querySelector('.antrean-grid');
    if (!container) {
        container = document.createElement('div');
        container.className = 'antrean-grid';
        grid.innerHTML = '';
        grid.appendChild(container);
    }

    // Remove cards no longer in antrean
    const ids = data.map(t => t.id);
    container.querySelectorAll('[id^="card-"]').forEach(card => {
        const cardId = parseInt(card.id.replace('card-', ''));
        if (!ids.includes(cardId)) card.remove();
    });

    // Add new cards
    data.forEach(t => {
        if (!document.getElementById('card-' + t.id)) {
            const div = document.createElement('div');
            div.className = 'antrean-card';
            div.id = 'card-' + t.id;
            div.innerHTML = `
                <div class="antrean-header">
                    <span class="antrean-num">Antrean #${t.nomor_antrean}</span>
                    <span class="antrean-time">🕐 ${t.created_at}</span>
                </div>
                <div class="antrean-pelanggan">Pelanggan: <strong>${t.nama_pelanggan}</strong></div>
                <div class="antrean-items">
                    ${t.items.map(i => `<div class="antrean-item-row"><span>${i.nama_menu}</span><span class="qty-badge">×${i.qty}</span></div>`).join('')}
                </div>
                <button class="btn-selesai" onclick="selesai(${t.id})">✓ Selesai & Sajikan</button>
            `;
            container.appendChild(div);
        }
    });
}

setInterval(refreshAntrean, 5000);
</script>
@endpush
