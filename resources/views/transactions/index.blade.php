@extends('layouts.app')
@section('title', 'Transaksi')

@section('content')
    @php $canManage = auth()->user()->isTreasurer(); @endphp

    <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:16px;">
        <form method="GET" style="display:flex; gap:8px; flex-wrap:wrap;">
            <input type="text" name="search" class="kk-input" placeholder="Cari deskripsi..." value="{{ request('search') }}" style="width:180px;">
            <select name="type" class="kk-select" style="width:140px;">
                <option value="">Semua Tipe</option>
                <option value="income" {{ request('type') === 'income' ? 'selected' : '' }}>Pemasukan</option>
                <option value="expense" {{ request('type') === 'expense' ? 'selected' : '' }}>Pengeluaran</option>
            </select>
            <select name="category_id" class="kk-select" style="width:160px;">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $c)
                    <option value="{{ $c->id }}" {{ request('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                @endforeach
            </select>
            <input type="date" name="from" class="kk-input" value="{{ request('from') }}" style="width:150px;">
            <input type="date" name="to" class="kk-input" value="{{ request('to') }}" style="width:150px;">
            <button class="kk-btn kk-btn-outline" type="submit">Filter</button>
        </form>
        @if ($canManage)
            <div style="display:flex; gap:8px;">
                <a href="{{ route('transactions.create', ['type' => 'income']) }}" class="kk-btn kk-btn-outline">+ Pemasukan</a>
                <a href="{{ route('transactions.create', ['type' => 'expense']) }}" class="kk-btn">+ Pengeluaran</a>
            </div>
        @endif
    </div>

    <div class="kk-table-wrap">
        @if ($transactions->isEmpty())
            <div class="kk-empty"><p>Belum ada transaksi</p></div>
        @else
        <table class="kk-table">
            <thead>
                <tr><th>Tanggal</th><th>Tipe</th><th>Kategori</th><th>Deskripsi</th><th>Nominal</th><th>Dibuat Oleh</th>@if($canManage)<th>Aksi</th>@endif</tr>
            </thead>
            <tbody>
                @foreach ($transactions as $t)
                    <tr>
                        <td>{{ $t->transaction_date->translatedFormat('d M Y') }}</td>
                        <td><span class="kk-badge {{ $t->type === 'income' ? 'kk-badge-income' : 'kk-badge-expense' }}">{{ $t->type === 'income' ? 'Income' : 'Expense' }}</span></td>
                        <td>{{ $t->category->name ?? '-' }}</td>
                        <td>
                            {{ $t->description }}
                            @if ($t->receipt_path)
                                <a href="{{ route('transactions.receipt', $t) }}" target="_blank" style="font-size:12px; color:var(--kk-primary-dark); margin-left:6px;">[bukti]</a>
                            @endif
                        </td>
                        <td>{{ $t->type === 'income' ? '+ ' : '- ' }}{{ rupiah($t->amount) }}</td>
                        <td>{{ $t->creator->name ?? '-' }}</td>
                        @if ($canManage)
                        <td style="display:flex; gap:6px;">
                            <a href="{{ route('transactions.edit', $t) }}" class="kk-btn kk-btn-sm kk-btn-outline">Edit</a>
                            <form method="POST" action="{{ route('transactions.destroy', $t) }}" data-confirm="Apakah kamu yakin ingin menghapus transaksi ini?">
                                @csrf @method('DELETE')
                                <button class="kk-btn kk-btn-sm kk-btn-danger" type="submit">Hapus</button>
                            </form>
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    <div style="margin-top:16px;">{{ $transactions->links() }}</div>
@endsection
