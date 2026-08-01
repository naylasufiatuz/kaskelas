@extends('layouts.app')
@section('title', 'Settings')

@section('content')
    <div class="kk-card" style="max-width:520px;">
        <div class="kk-card-title">Pengaturan Kelas</div>
        <form method="POST" action="{{ route('settings.update') }}">
            @csrf @method('PUT')

            <div class="kk-field">
                <label class="kk-label">Nama Kelas</label>
                <input type="text" name="class_name" class="kk-input" value="{{ old('class_name', $settings['class_name']) }}" required>
            </div>

            <div class="kk-field">
                <label class="kk-label">Nominal Kas Mingguan (Rp)</label>
                <input type="number" name="weekly_amount" class="kk-input" min="500" value="{{ old('weekly_amount', $settings['weekly_amount']) }}" required>
                <div class="kk-help">Berlaku untuk minggu berjalan dan selanjutnya. Riwayat pembayaran yang sudah tercatat tidak berubah.</div>
            </div>

            <div class="kk-field">
                <label class="kk-label">Tanggal Mulai Kas</label>
                <input type="date" name="cash_start_date" class="kk-input" value="{{ old('cash_start_date', $settings['cash_start_date']) }}" required>
                <div class="kk-help">Menentukan Week 1. Sebaiknya tidak diubah setelah ada data pembayaran.</div>
            </div>

            <div class="kk-field">
                <label class="kk-label">Nama Bendahara</label>
                <input type="text" name="treasurer_name" class="kk-input" value="{{ old('treasurer_name', $settings['treasurer_name']) }}">
            </div>

            <div class="kk-field">
                <label class="kk-label">Nama Ketua Kelas</label>
                <input type="text" name="class_leader_name" class="kk-input" value="{{ old('class_leader_name', $settings['class_leader_name']) }}">
            </div>

            <div class="kk-field">
                <label class="kk-label">Nama Sekolah</label>
                <input type="text" name="school_name" class="kk-input" value="{{ old('school_name', $settings['school_name']) }}">
            </div>

            <button type="submit" class="kk-btn">Simpan Pengaturan</button>
        </form>
    </div>

    <div class="kk-card" style="max-width:520px; margin-top:20px;">
        <div class="kk-card-title">Logo Kelas (opsional)</div>
        <form method="POST" action="{{ route('settings.logo') }}" enctype="multipart/form-data">
            @csrf
            <div class="kk-field">
                <input type="file" name="logo" class="kk-input" accept="image/*">
            </div>
            <button type="submit" class="kk-btn kk-btn-outline">Unggah Logo</button>
        </form>
    </div>
@endsection
