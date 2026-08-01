<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - KasKelas</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<div class="kk-auth-wrap">
    <div class="kk-auth-card">
        <div class="kk-logo" style="padding:0 0 6px; justify-content:center;">
            <span class="kk-logo-badge">K</span>
            <span>KasKelas</span>
        </div>
        <p style="text-align:center; color:var(--kk-text-muted); font-size:13px; margin:0 0 24px;">
            Kelola Kas Kelas dengan Mudah dan Transparan
        </p>

        @if ($errors->any())
            <div class="kk-alert kk-alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="kk-field">
                <label class="kk-label">Email</label>
                <input type="email" name="email" class="kk-input" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="kk-field">
                <label class="kk-label">Password</label>
                <input type="password" name="password" class="kk-input" required>
            </div>
            <div class="kk-field" style="display:flex; align-items:center; gap:8px;">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember" style="font-size:13px;">Ingat saya</label>
            </div>
            <button type="submit" class="kk-btn" style="width:100%; justify-content:center;">Masuk</button>
        </form>
    </div>
</div>
</body>
</html>
