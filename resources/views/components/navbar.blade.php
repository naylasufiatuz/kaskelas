@php
    $user = auth()->user();
    $roleLabels = ['treasurer' => 'Bendahara', 'class_leader' => 'Ketua Kelas', 'student' => 'Siswa'];
    $initials = collect(explode(' ', $user->name))->map(fn($w) => mb_substr($w, 0, 1))->take(2)->implode('');
@endphp
<header class="kk-navbar">
    <div style="display:flex; align-items:center; gap:12px;">
        <button class="kk-btn kk-btn-outline kk-mobile-toggle kk-btn-sm" data-sidebar-toggle type="button" aria-label="Menu">☰</button>
        <div class="kk-navbar-title">@yield('title', 'Dashboard')</div>
    </div>

    <div class="kk-user-chip">
        <div class="kk-user-meta">
            <div class="kk-user-name">{{ $user->name }}</div>
            <div class="kk-user-role">{{ $roleLabels[$user->role] ?? $user->role }}</div>
        </div>
        <div class="kk-avatar">{{ strtoupper($initials) }}</div>
    </div>
</header>
