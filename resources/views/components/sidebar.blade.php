@php
    $user = auth()->user();
@endphp
<aside class="kk-sidebar">
    <div class="kk-logo">
        <span class="kk-logo-badge">K</span>
        <span>KasKelas</span>
    </div>

    <nav>
        <div class="kk-nav-group">
            <a href="{{ route('dashboard') }}" class="kk-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                Dashboard
            </a>
        </div>

        <div class="kk-nav-group">
            <div class="kk-nav-title">Keuangan</div>
            @if ($user->isStudent())
                <a href="{{ route('cash-payments.mine') }}" class="kk-nav-link {{ request()->routeIs('cash-payments.mine') ? 'active' : '' }}">Kas Saya</a>
            @else
                <a href="{{ route('cash-payments.index') }}" class="kk-nav-link {{ request()->routeIs('cash-payments.index') ? 'active' : '' }}">Pembayaran Kas</a>
            @endif
            <a href="{{ route('transactions.index') }}" class="kk-nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">Transaksi</a>
        </div>

        @unless ($user->isStudent())
        <div class="kk-nav-group">
            <div class="kk-nav-title">Data</div>
            <a href="{{ route('students.index') }}" class="kk-nav-link {{ request()->routeIs('students.*') ? 'active' : '' }}">Data Siswa</a>
        </div>
        @endunless

        <div class="kk-nav-group">
            <a href="{{ route('reports.index') }}" class="kk-nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">Laporan</a>
            @if ($user->isTreasurer())
                <a href="{{ route('activity-logs.index') }}" class="kk-nav-link {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">Activity Log</a>
                <a href="{{ route('settings.index') }}" class="kk-nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">Settings</a>
            @endif
        </div>

        <div class="kk-nav-group">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="kk-nav-link" style="width:100%; text-align:left; background:none; border:none; cursor:pointer; font-family:inherit;">Logout</button>
            </form>
        </div>
    </nav>
</aside>
