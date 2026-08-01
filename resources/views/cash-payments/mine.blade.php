@extends('layouts.app')
@section('title', 'Kas Saya')

@section('content')
    <div class="kk-table-wrap">
        @if ($payments->isEmpty())
            <div class="kk-empty"><p>Belum ada riwayat pembayaran kas</p></div>
        @else
        <table class="kk-table">
            <thead>
                <tr><th>Minggu</th><th>Nominal</th><th>Status</th><th>Tanggal Bayar</th></tr>
            </thead>
            <tbody>
                @foreach ($payments as $p)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($p->week_start)->translatedFormat('d M Y') }}</td>
                        <td>{{ rupiah($p->amount) }}</td>
                        <td><span class="kk-badge {{ $p->status === 'paid' ? 'kk-badge-paid' : 'kk-badge-unpaid' }}">{{ $p->status === 'paid' ? 'Lunas' : 'Belum' }}</span></td>
                        <td>{{ $p->paid_at?->translatedFormat('d M Y H:i') ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    <div style="margin-top:16px;">{{ $payments->links() }}</div>
@endsection
