<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ganti Password - KasKelas</title>
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
            Untuk keamanan, silakan ganti password sementara Anda.
        </p>

        @if ($errors->any())
            <div class="kk-alert kk-alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.change') }}">
            @csrf
            <div class="kk-field">
                <label class="kk-label">Password Saat Ini</label>
                <input type="password" name="current_password" class="kk-input" required>
            </div>
            <div class="kk-field">
                <label class="kk-label">Password Baru</label>
                <input type="password" name="password" class="kk-input" required minlength="8">
                <div class="kk-help">Minimal 8 karakter.</div>
            </div>
            <div class="kk-field">
                <label class="kk-label">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" class="kk-input" required minlength="8">
            </div>
            <button type="submit" class="kk-btn" style="width:100%; justify-content:center;">Ganti Password</button>
        </form>
    </div>
</div>
</body>
</html>
