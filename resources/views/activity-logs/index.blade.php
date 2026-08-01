@extends('layouts.app')
@section('title', 'Activity Log')

@section('content')
    <div class="kk-table-wrap">
        @if ($logs->isEmpty())
            <div class="kk-empty"><p>Belum ada aktivitas</p></div>
        @else
        <table class="kk-table">
            <thead><tr><th>Waktu</th><th>User</th><th>Aktivitas</th><th>Detail</th></tr></thead>
            <tbody>
                @foreach ($logs as $log)
                    <tr>
                        <td>{{ $log->created_at->translatedFormat('d M Y H:i') }}</td>
                        <td>{{ $log->user->name ?? 'Sistem' }}</td>
                        <td>{{ $log->description }}</td>
                        <td style="font-size:12.5px; color:var(--kk-text-muted);">
                            @if ($log->old_values && $log->new_values)
                                @foreach ($log->new_values as $field => $newVal)
                                    @if (($log->old_values[$field] ?? null) != $newVal)
                                        {{ $field }}: {{ $log->old_values[$field] ?? '-' }} → {{ $newVal }}<br>
                                    @endif
                                @endforeach
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
    <div style="margin-top:16px;">{{ $logs->links() }}</div>
@endsection
