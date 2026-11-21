<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>QuickOrder POS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; color: #1e293b; }

        /* NAV */
        .navbar {
            background: #0f172a;
            color: #fff;
            padding: 0 1.5rem;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .navbar-brand { display: flex; align-items: center; gap: .5rem; font-weight: 700; font-size: 1rem; }
        .navbar-right { display: flex; align-items: center; gap: .75rem; }
        .badge-role {
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 20px;
            padding: .25rem .75rem;
            font-size: .75rem;
            font-weight: 600;
        }
        .badge-role.admin   { color: #fbbf24; }
        .badge-role.kasir   { color: #f97316; }
        .badge-role.kitchen { color: #34d399; }

        .btn-logout {
            background: #ef4444;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: .35rem .9rem;
            font-size: .8rem;
            font-weight: 600;
            cursor: pointer;
            display: flex; align-items: center; gap: .4rem;
        }
        .btn-logout:hover { background: #dc2626; }

        /* CONTAINER */
        .container { max-width: 1400px; margin: 0 auto; padding: 1.5rem; }

        /* CARDS */
        .card { background: #fff; border-radius: 12px; padding: 1.5rem; box-shadow: 0 1px 4px rgba(0,0,0,.07); }

        /* BUTTONS */
        .btn {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .45rem 1rem; border-radius: 8px; border: none;
            font-size: .82rem; font-weight: 600; cursor: pointer;
            transition: opacity .15s;
        }
        .btn:hover { opacity: .85; }
        .btn-primary   { background: #f59e0b; color: #fff; }
        .btn-success   { background: #22c55e; color: #fff; }
        .btn-danger    { background: #ef4444; color: #fff; }
        .btn-secondary { background: #e2e8f0; color: #475569; }
        .btn-dark      { background: #0f172a; color: #fff; }
        .btn-sm        { padding: .3rem .7rem; font-size: .75rem; }
        .btn-block     { width: 100%; justify-content: center; }
        .btn-lg        { padding: .7rem 1.5rem; font-size: 1rem; border-radius: 10px; }

        /* FORMS */
        .form-group { margin-bottom: 1rem; }
        .form-group label { display: block; font-size: .8rem; font-weight: 600; margin-bottom: .35rem; color: #475569; }
        .form-control {
            width: 100%; padding: .5rem .75rem;
            border: 1.5px solid #e2e8f0; border-radius: 8px;
            font-size: .88rem; transition: border-color .15s;
            font-family: inherit;
        }
        .form-control:focus { outline: none; border-color: #f59e0b; }
        select.form-control { cursor: pointer; }

        /* ALERTS */
        .alert { padding: .6rem 1rem; border-radius: 8px; margin-bottom: 1rem; font-size: .85rem; font-weight: 500; }
        .alert-success { background: #dcfce7; color: #166534; }
        .alert-danger  { background: #fee2e2; color: #991b1b; }

        /* TABLE */
        .table { width: 100%; border-collapse: collapse; }
        .table th { font-size: .78rem; color: #64748b; font-weight: 600; padding: .6rem .75rem; border-bottom: 2px solid #e2e8f0; text-align: left; }
        .table td { padding: .65rem .75rem; border-bottom: 1px solid #f1f5f9; font-size: .88rem; }
        .table tbody tr:hover { background: #f8fafc; }

        /* TABS */
        .tabs { display: flex; gap: .5rem; margin-bottom: 1.5rem; }
        .tab-btn {
            padding: .5rem 1.1rem; border-radius: 8px; border: 1.5px solid #e2e8f0;
            font-size: .82rem; font-weight: 600; cursor: pointer;
            background: #fff; color: #64748b; transition: all .15s;
            display: flex; align-items: center; gap: .4rem;
        }
        .tab-btn.active { background: #0f172a; color: #fff; border-color: #0f172a; }
        .tab-btn:hover:not(.active) { border-color: #94a3b8; }

        /* PAGE HEADER */
        .page-header { margin-bottom: 1.5rem; }
        .page-header h1 { font-size: 1.4rem; font-weight: 700; display: flex; align-items: center; gap: .5rem; }
        .page-header p  { font-size: .82rem; color: #94a3b8; margin-top: .2rem; }

        /* STOK */
        .stok-habis { color: #ef4444; font-weight: 700; }
        .stok-ok    { font-weight: 600; }

        /* MODAL */
        .modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,.45); z-index: 200;
            align-items: center; justify-content: center;
        }
        .modal-overlay.open { display: flex; }
        .modal {
            background: #fff; border-radius: 12px; padding: 1.5rem;
            width: 100%; max-width: 440px; max-height: 90vh; overflow-y: auto;
        }
        .modal h3 { font-size: 1rem; font-weight: 700; margin-bottom: 1rem; }
        .modal-footer { display: flex; justify-content: flex-end; gap: .5rem; margin-top: 1rem; }
    </style>
    @stack('styles')
</head>
<body>
<nav class="navbar">
    <div class="navbar-brand">
        🍔 QuickOrder POS
    </div>
    <div class="navbar-right">
        @auth
        <span class="badge-role {{ auth()->user()->role }}">
            {{ auth()->user()->nama_lengkap }} ({{ auth()->user()->getRoleLabel() }})
        </span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Keluar 🚪</button>
        </form>
        @endauth
    </div>
</nav>

@yield('content')

@stack('scripts')
</body>
</html>
