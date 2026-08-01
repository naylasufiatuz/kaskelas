@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <p style="color:var(--kk-text-muted); margin-top:0;">Selamat datang, {{ auth()->user()->name }}</p>

    @if (! $hasStudents)
        <div class="kk-alert kk-alert-warning">
            Data siswa belum tersedia.
            @if (auth()->user()->isTreasurer())
                <a href="{{ route('students.index') }}" class="kk-btn kk-btn-sm" style="margin-left:10px;">+ Tambah Data Siswa</a>
            @endif
        </div>
    @endif

    <div class="kk-grid">
        <div class="kk-card">
            <div class="kk-stat-label">Saldo Sekarang</div>
            <div class="kk-stat-value">{{ rupiah($balance) }}</div>
        </div>
        <div class="kk-card">
            <div class="kk-stat-label">Total Pemasukan</div>
            <div class="kk-stat-value income">{{ rupiah($totalIncome) }}</div>
        </div>
        <div class="kk-card">
            <div class="kk-stat-label">Total Pengeluaran</div>
            <div class="kk-stat-value expense">{{ rupiah($totalExpense) }}</div>
        </div>
        <div class="kk-card">
            <div class="kk-stat-label">Total Siswa</div>
            <div class="kk-stat-value">{{ $totalStudents }} Siswa</div>
        </div>
    </div>

    <div class="kk-card" style="margin-bottom:20px;">
        <div class="kk-card-title">Kas Minggu Ini <span style="font-weight:400; color:var(--kk-text-muted);">({{ $weekLabel }})</span></div>
        <div style="display:flex; gap:32px; flex-wrap:wrap; margin-bottom:12px;">
            <div>
                <div class="kk-stat-label">Target</div>
                <div class="kk-stat-value" style="font-size:18px;">{{ rupiah($target) }}</div>
            </div>
            <div>
                <div class="kk-stat-label">Terkumpul</div>
                <div class="kk-stat-value income" style="font-size:18px;">{{ rupiah($collected) }}</div>
            </div>
            <div>
                <div class="kk-stat-label">Kekurangan</div>
                <div class="kk-stat-value expense" style="font-size:18px;">{{ rupiah($shortfall) }}</div>
            </div>
            <div>
                <div class="kk-stat-label">Pembayar</div>
                <div class="kk-stat-value" style="font-size:18px;">{{ $paidCount }} / {{ $totalStudents }} siswa</div>
            </div>
        </div>
        <div class="kk-progress"><div class="kk-progress-bar" style="width: {{ min($progress, 100) }}%;"></div></div>
        <div style="text-align:right; font-size:12.5px; color:var(--kk-text-muted); margin-top:4px;">{{ $progress }}%</div>
    </div>

    <div class="kk-grid" style="grid-template-columns: 1.3fr 1fr;">
        <div class="kk-card">
            <div class="kk-card-title">Income vs Expense</div>
            <canvas id="barChart" height="160"></canvas>
        </div>
        <div class="kk-card">
            <div class="kk-card-title">Pengeluaran Berdasarkan Kategori</div>
            @if ($expenseByCategory->isEmpty())
                <div class="kk-empty" style="padding:24px 0;"><p>Belum ada pengeluaran</p></div>
            @else
                <canvas id="donutChart" height="160"></canvas>
            @endif
        </div>
    </div>

    <div class="kk-section-title">Transaksi Terbaru</div>
    <div class="kk-table-wrap">
        @if ($recentTransactions->isEmpty())
            <div class="kk-empty"><p>Belum ada transaksi</p></div>
        @else
        <table class="kk-table">
            <thead>
                <tr><th>Tanggal</th><th>Deskripsi</th><th>Tipe</th><th>Nominal</th></tr>
            </thead>
            <tbody>
                @foreach ($recentTransactions as $t)
                    <tr>
                        <td>{{ $t->transaction_date->translatedFormat('d M Y') }}</td>
                        <td>{{ $t->description }}</td>
                        <td><span class="kk-badge {{ $t->type === 'income' ? 'kk-badge-income' : 'kk-badge-expense' }}">{{ $t->type === 'income' ? 'Income' : 'Expense' }}</span></td>
                        <td>{{ $t->type === 'income' ? '+ ' : '- ' }}{{ rupiah($t->amount) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.4/chart.umd.min.js"></script>
<script>
    const months = @json($monthlyChart->keys());
    const incomeData = @json($monthlyChart->map(fn($g) => $g->firstWhere('type', 'income')->total ?? 0)->values());
    const expenseData = @json($monthlyChart->map(fn($g) => $g->firstWhere('type', 'expense')->total ?? 0)->values());

    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                { label: 'Pemasukan', data: incomeData, backgroundColor: '#7BAE8F', borderRadius: 6 },
                { label: 'Pengeluaran', data: expenseData, backgroundColor: '#C96A5A', borderRadius: 6 },
            ]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });

    @if ($expenseByCategory->isNotEmpty())
    new Chart(document.getElementById('donutChart'), {
        type: 'doughnut',
        data: {
            labels: @json($expenseByCategory->pluck('category')),
            datasets: [{
                data: @json($expenseByCategory->pluck('total')),
                backgroundColor: ['#7BAE8F', '#A7C9B6', '#D9A441', '#C96A5A', '#5C9077', '#B7CFC1', '#8FB09E'],
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
    @endif
</script>
@endpush
