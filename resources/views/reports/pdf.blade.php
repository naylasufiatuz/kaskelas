<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    body { font-family: sans-serif; color: #263A31; font-size: 12px; }
    h1 { font-size: 18px; margin-bottom: 2px; }
    .sub { color: #6B7C74; margin-bottom: 18px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #E2E8E4; padding: 6px 8px; text-align: left; }
    th { background: #E6F2EB; }
    .totals { margin-top: 14px; }
    .totals td { border: none; padding: 3px 0; }
    .totals .label { color: #6B7C74; width: 160px; }
    .totals .value { font-weight: bold; }
    .income { color: #5C9077; }
    .expense { color: #C96A5A; }
</style>
</head>
<body>
    <h1>LAPORAN KEUANGAN</h1>
    <div class="sub">{{ $className }} &middot; {{ $from->translatedFormat('d F Y') }} – {{ $to->translatedFormat('d F Y') }}</div>

    <table class="totals">
        <tr><td class="label">Total Pemasukan</td><td class="value income">{{ rupiah($totals['income']) }}</td></tr>
        <tr><td class="label">Total Pengeluaran</td><td class="value expense">{{ rupiah($totals['expense']) }}</td></tr>
        <tr><td class="label">Saldo</td><td class="value">{{ rupiah($totals['net']) }}</td></tr>
        <tr><td class="label">Jumlah Transaksi</td><td class="value">{{ $transactions->count() }}</td></tr>
    </table>

    @if ($expenseByCategory->isNotEmpty())
    <h3>Rekap Pengeluaran per Kategori</h3>
    <table>
        <thead><tr><th>Kategori</th><th>Total</th></tr></thead>
        <tbody>
        @foreach ($expenseByCategory as $category => $total)
            <tr><td>{{ $category }}</td><td>{{ rupiah($total) }}</td></tr>
        @endforeach
        </tbody>
    </table>
    @endif

    <h3>Rekap Transaksi</h3>
    <table>
        <thead><tr><th>Tanggal</th><th>Tipe</th><th>Kategori</th><th>Deskripsi</th><th>Nominal</th></tr></thead>
        <tbody>
        @foreach ($transactions as $t)
            <tr>
                <td>{{ $t->transaction_date->translatedFormat('d M Y') }}</td>
                <td>{{ $t->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                <td>{{ $t->category->name ?? '-' }}</td>
                <td>{{ $t->description }}</td>
                <td>{{ rupiah($t->amount) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
