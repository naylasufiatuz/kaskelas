@extends('layouts.app')
@section('title', $type === 'income' ? 'Tambah Pemasukan' : 'Tambah Pengeluaran')

@section('content')
    <div class="kk-card" style="max-width:520px;">
        <div class="kk-card-title">{{ $type === 'income' ? 'Tambah Pemasukan' : 'Tambah Pengeluaran' }}</div>
        <form method="POST" action="{{ route('transactions.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">

            <div class="kk-field">
                <label class="kk-label">Tanggal</label>
                <input type="date" name="transaction_date" class="kk-input" value="{{ old('transaction_date', now()->toDateString()) }}" required>
            </div>

            <div class="kk-field">
                <label class="kk-label">Kategori</label>
                <select name="category_id" class="kk-select" required>
                    <option value="">Pilih kategori</option>
                    @foreach ($categories as $c)
                        <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="kk-field">
                <label class="kk-label">Nominal (Rp)</label>
                <input type="number" name="amount" class="kk-input" min="1" value="{{ old('amount') }}" required>
            </div>

            <div class="kk-field">
                <label class="kk-label">Deskripsi</label>
                <input type="text" name="description" class="kk-input" value="{{ old('description') }}" required>
            </div>

            <div class="kk-field">
                <label class="kk-label">Catatan</label>
                <textarea name="notes" class="kk-input" rows="3">{{ old('notes') }}</textarea>
            </div>

            @if ($type === 'expense')
            <div class="kk-field">
                <label class="kk-label">Upload Bukti/Nota (JPG, PNG, PDF)</label>
                <input type="file" name="receipt" class="kk-input" accept=".jpg,.jpeg,.png,.pdf">
            </div>
            @endif

            <div style="display:flex; gap:8px; justify-content:flex-end;">
                <a href="{{ route('transactions.index') }}" class="kk-btn kk-btn-outline">Batal</a>
                <button type="submit" class="kk-btn">Simpan</button>
            </div>
        </form>
    </div>
@endsection
