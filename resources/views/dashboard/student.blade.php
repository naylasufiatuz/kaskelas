@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <p style="color:var(--kk-text-muted); margin-top:0;">Selamat datang, {{ auth()->user()->name }}</p>

    <div class="kk-grid" style="grid-template-columns: 1fr 1fr;">
        <div class="kk-card">
            <div class="kk-stat-label">Saldo Kelas</div>
            <div class="kk-stat-value">{{ rupiah($balance) }}</div>
        </div>
        <div class="kk-card">
            <div class="kk-stat-label">Kas Saya ({{ $weekLabel }})</div>
            <div class="kk-stat-value">{{ rupiah($paidAmount) }} / {{ rupiah($weeklyAmount) }}</div>
            <div style="margin-top:8px;">
                <span class="kk-badge {{ $isPaid ? 'kk-badge-paid' : 'kk-badge-unpaid' }}">
                    {{ $isPaid ? 'LUNAS' : 'BELUM LUNAS' }}
                </span>
            </div>
        </div>
    </div>

    <div class="kk-grid" style="grid-template-columns: 1fr 1fr; margin-top:4px;">
        <div class="kk-card">
            <div class="kk-stat-label">Total Pemasukan Kelas</div>
            <div class="kk-stat-value income">{{ rupiah($totalIncome) }}</div>
        </div>
        <div class="kk-card">
            <div class="kk-stat-label">Total Pengeluaran Kelas</div>
            <div class="kk-stat-value expense">{{ rupiah($totalExpense) }}</div>
        </div>
    </div>

    <p style="color:var(--kk-text-muted); font-size:13.5px;">
        Lihat riwayat lengkap kas Anda di menu <a href="{{ route('cash-payments.mine') }}" style="color:var(--kk-primary-dark); font-weight:600;">Kas Saya</a>,
        atau transaksi kelas di menu <a href="{{ route('transactions.index') }}" style="color:var(--kk-primary-dark); font-weight:600;">Transaksi</a>.
    </p>
@endsection
