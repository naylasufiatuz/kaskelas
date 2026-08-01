@extends('layouts.app')
@section('title', 'Laporan')

@section('content')
    @php $canExport = auth()->user()->isTreasurer(); @endphp

    <form method="GET" style="display:flex; gap:8px; flex-wrap:wrap; align-items:end; margin-bottom:20px;">
        <div>
            <label class="kk-label">Bulan</label>
            <input type="month" name="month" class="kk-input" value="{{ request('month') }}">
        </div>
        <div><label class="kk-label">Dari</label><input type="date" name="from" class="kk-input" value="{{ request('from') }}"></div>
        <div><label class="kk-label">Sampai</label><input type="date" name="to" class="kk-input" value="{{ request('to') }}"></div>
        <button class="kk-btn kk-btn-outline" type="submit">Terapkan</button>
        @if ($canExport)
            <a href="{{ route('reports.export-pdf', request()->query()) }}" class="kk-btn">Export PDF</a>
        @endif
    </form>

    <p style="color:var(--kk-text-muted); font-size:13.5px; margin-top:-8px;">
        Periode: {{ $from->translatedFormat('d M Y') }} – {{ $to->translatedFormat('d M Y') }}
    </p>

    <div class="kk-grid" style="grid-template-columns: repeat(4,1fr);">
        <div class="kk-card"><div class="kk-stat-label">Total Pemasukan</div><div class="kk-stat-value income">{{ rupiah($totals['income']) }}</div></div>
        <div class="kk-card"><div class="kk-stat-label">Total Pengeluaran</div><div class="kk-stat-value expense">{{ rupiah($totals['expense']) }}</div></div>
        <div class="kk-card"><div class="kk-stat-label">Saldo Periode</div><div class="kk-stat-value">{{ rupiah($totals['net']) }}</div></div>
        <div class="kk-card"><div class="kk-stat-label">Jumlah Transaksi</div><div class="kk-stat-value">{{ $transactions->count() }}</div></div>
    </div>

    @if ($expenseByCategory->isNotEmpty())
    <div class="kk-card" style="margin-bottom:20px;">
        <div class="kk-card-title">Kategori Pengeluaran Terbesar</div>
        @foreach ($expenseByCategory->take(5) as $category => $total)
            <div style="display:flex; justify-content:space-between; padding:6px 0; border-bottom:1px solid var(--kk-border); font-size:14px;">
                <span>{{ $category }}</span>
                <span style="font-weight:600;">{{ rupiah($total) }}</span>
            </div>
        @endforeach
    </div>
    @endif

    <div class="kk-table-wrap">
        @if ($transactions->isEmpty())
            <div class="kk-empty"><p>Belum ada transaksi pada periode ini</p></div>
        @else
        <table class="kk-table">
            <thead><tr><th>Tanggal</th><th>Tipe</th><th>Kategori</th><th>Deskripsi</th><th>Nominal</th></tr></thead>
            <tbody>
                @foreach ($transactions as $t)
                    <tr>
                        <td>{{ $t->transaction_date->translatedFormat('d M Y') }}</td>
                        <td><span class="kk-badge {{ $t->type === 'income' ? 'kk-badge-income' : 'kk-badge-expense' }}">{{ $t->type === 'income' ? 'Income' : 'Expense' }}</span></td>
                        <td>{{ $t->category->name ?? '-' }}</td>
                        <td>{{ $t->description }}</td>
                        <td>{{ $t->type === 'income' ? '+ ' : '- ' }}{{ rupiah($t->amount) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
@endsection
