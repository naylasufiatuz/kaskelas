@extends('layouts.app')
@section('title', 'Pembayaran Kas')

@section('content')
    @php $canManage = auth()->user()->isTreasurer(); @endphp

    <form method="GET" style="margin-bottom:16px;">
        <label class="kk-label">Pilih Minggu</label>
        <select name="week" class="kk-select" style="max-width:320px;" onchange="this.form.submit()">
            @foreach ($weeks as $w)
                <option value="{{ $w['start']->toDateString() }}" {{ $w['start']->toDateString() === $weekStart->toDateString() ? 'selected' : '' }}>
                    {{ $w['label'] }}
                </option>
            @endforeach
        </select>
    </form>

    <div class="kk-grid" style="grid-template-columns: repeat(3, 1fr);">
        <div class="kk-card">
            <div class="kk-stat-label">Target</div>
            <div class="kk-stat-value" style="font-size:18px;">{{ rupiah($target) }}</div>
        </div>
        <div class="kk-card">
            <div class="kk-stat-label">Terkumpul</div>
            <div class="kk-stat-value income" style="font-size:18px;">{{ rupiah($collected) }}</div>
        </div>
        <div class="kk-card">
            <div class="kk-stat-label">Pembayar</div>
            <div class="kk-stat-value" style="font-size:18px;">{{ $paidCount }} / {{ $totalStudents }} siswa</div>
        </div>
    </div>

    <div class="kk-table-wrap">
        @if ($rows->isEmpty())
            <div class="kk-empty"><p>Belum ada data siswa</p></div>
        @else
        <table class="kk-table">
            <thead>
                <tr><th>No</th><th>Nama</th><th>Nominal</th><th>Status</th><th>Tanggal</th>@if($canManage)<th>Aksi</th>@endif</tr>
            </thead>
            <tbody>
                @foreach ($rows as $i => $row)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $row['student']->name }}</td>
                        <td>{{ rupiah($weeklyAmount) }}</td>
                        <td><span class="kk-badge {{ $row['status'] === 'paid' ? 'kk-badge-paid' : 'kk-badge-unpaid' }}">{{ $row['status'] === 'paid' ? 'Lunas' : 'Belum' }}</span></td>
                        <td>{{ $row['payment']?->paid_at?->translatedFormat('d M') ?? '-' }}</td>
                        @if ($canManage)
                        <td style="display:flex; gap:6px;">
                            @if ($row['status'] === 'paid')
                                <form method="POST" action="{{ route('cash-payments.mark-unpaid', $row['student']) }}">
                                    @csrf
                                    <input type="hidden" name="week_start" value="{{ $weekStart->toDateString() }}">
                                    <button class="kk-btn kk-btn-sm kk-btn-outline" type="submit">Tandai Belum</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('cash-payments.mark-paid', $row['student']) }}">
                                    @csrf
                                    <input type="hidden" name="week_start" value="{{ $weekStart->toDateString() }}">
                                    <button class="kk-btn kk-btn-sm" type="submit">Tandai Lunas</button>
                                </form>
                            @endif
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
@endsection
