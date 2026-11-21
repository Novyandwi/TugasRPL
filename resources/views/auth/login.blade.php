<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — QuickOrder POS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #0f172a;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
        }
        .login-box {
            background: #fff;
            border-radius: 16px;
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 60px rgba(0,0,0,.3);
            text-align: center;
        }
        .logo { font-size: 2.5rem; margin-bottom: .75rem; }
        h1 { font-size: 1.3rem; font-weight: 700; margin-bottom: .3rem; }
        .subtitle { font-size: .82rem; color: #94a3b8; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1rem; text-align: left; }
        label { display: block; font-size: .8rem; font-weight: 600; color: #475569; margin-bottom: .35rem; }
        input {
            width: 100%; padding: .6rem .9rem;
            border: 1.5px solid #e2e8f0; border-radius: 10px;
            font-size: .9rem; font-family: inherit;
        }
        input:focus { outline: none; border-color: #f59e0b; }
        .btn-login {
            width: 100%; padding: .75rem;
            background: #f59e0b; color: #fff;
            border: none; border-radius: 10px;
            font-size: .95rem; font-weight: 700;
            cursor: pointer; margin-top: .5rem;
        }
        .btn-login:hover { background: #d97706; }
        .error-msg { background: #fee2e2; color: #991b1b; border-radius: 8px; padding: .5rem .9rem; font-size: .82rem; margin-bottom: 1rem; }
    </style>
</head>
<body>
<div class="login-box">
    <div class="logo">🍔</div>
    <h1>QuickOrder POS</h1>
    <p class="subtitle">Silakan masuk sesuai akun kategori Anda</p>

    @if($errors->has('login'))
        <div class="error-msg">{{ $errors->first('login') }}</div>
    @endif

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan username" value="{{ old('username') }}" required autofocus>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password" required>
        </div>
        <button type="submit" class="btn-login">Masuk Sistem</button>
    </form>
</div>
</body>
</html>
