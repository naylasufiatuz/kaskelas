@extends('layouts.app')
@section('title', 'Edit Transaksi')

@section('content')
    <div class="kk-card" style="max-width:520px;">
        <div class="kk-card-title">Edit {{ $transaction->type === 'income' ? 'Pemasukan' : 'Pengeluaran' }}</div>
        <form method="POST" action="{{ route('transactions.update', $transaction) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div class="kk-field">
                <label class="kk-label">Tanggal</label>
                <input type="date" name="transaction_date" class="kk-input" value="{{ old('transaction_date', $transaction->transaction_date->toDateString()) }}" required>
            </div>

            <div class="kk-field">
                <label class="kk-label">Kategori</label>
                <select name="category_id" class="kk-select" required>
                    @foreach ($categories as $c)
                        <option value="{{ $c->id }}" {{ old('category_id', $transaction->category_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="kk-field">
                <label class="kk-label">Nominal (Rp)</label>
                <input type="number" name="amount" class="kk-input" min="1" value="{{ old('amount', $transaction->amount) }}" required>
            </div>

            <div class="kk-field">
                <label class="kk-label">Deskripsi</label>
                <input type="text" name="description" class="kk-input" value="{{ old('description', $transaction->description) }}" required>
            </div>

            <div class="kk-field">
                <label class="kk-label">Catatan</label>
                <textarea name="notes" class="kk-input" rows="3">{{ old('notes', $transaction->notes) }}</textarea>
            </div>

            @if ($transaction->type === 'expense')
            <div class="kk-field">
                <label class="kk-label">Bukti/Nota</label>
                @if ($transaction->receipt_path)
                    <div style="margin-bottom:6px;"><a href="{{ route('transactions.receipt', $transaction) }}" target="_blank" style="color:var(--kk-primary-dark);">Lihat bukti saat ini</a></div>
                @endif
                <input type="file" name="receipt" class="kk-input" accept=".jpg,.jpeg,.png,.pdf">
                <div class="kk-help">Kosongkan jika tidak ingin mengganti bukti.</div>
            </div>
            @endif

            <div style="display:flex; gap:8px; justify-content:flex-end;">
                <a href="{{ route('transactions.index') }}" class="kk-btn kk-btn-outline">Batal</a>
                <button type="submit" class="kk-btn">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection
