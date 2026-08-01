@extends('layouts.app')
@section('title', 'Data Siswa')

@section('content')
    @php $canManage = auth()->user()->isTreasurer(); @endphp

    <div style="display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; margin-bottom:16px;">
        <form method="GET" style="display:flex; gap:8px;">
            <input type="text" name="search" class="kk-input" placeholder="Cari nama atau NIS..." value="{{ request('search') }}" style="width:240px;">
            <button class="kk-btn kk-btn-outline" type="submit">Cari</button>
        </form>
        @if ($canManage)
            <button class="kk-btn" type="button" onclick="document.getElementById('addStudentModal').style.display='flex'">+ Tambah Siswa</button>
        @endif
    </div>

    <div class="kk-table-wrap">
        @if ($students->isEmpty())
            <div class="kk-empty">
                <p>Belum ada data siswa</p>
                @if ($canManage)
                    <button class="kk-btn" type="button" onclick="document.getElementById('addStudentModal').style.display='flex'">+ Tambah Siswa</button>
                @endif
            </div>
        @else
        <table class="kk-table">
            <thead>
                <tr>
                    <th>No</th><th>Nama</th><th>NIS</th><th>Status</th>
                    @if ($canManage)<th>Aksi</th>@endif
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $i => $student)
                    <tr>
                        <td>{{ $students->firstItem() + $i }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->student_number ?? '-' }}</td>
                        <td><span class="kk-badge {{ $student->active ? 'kk-badge-paid' : 'kk-badge-unpaid' }}">{{ $student->active ? 'Aktif' : 'Nonaktif' }}</span></td>
                        @if ($canManage)
                        <td style="display:flex; gap:6px;">
                            <button class="kk-btn kk-btn-sm kk-btn-outline" type="button" onclick="document.getElementById('editStudentModal{{ $student->id }}').style.display='flex'">Edit</button>
                            <form method="POST" action="{{ route('students.destroy', $student) }}" data-confirm="Apakah kamu yakin ingin menghapus siswa ini?">
                                @csrf @method('DELETE')
                                <button class="kk-btn kk-btn-sm kk-btn-danger" type="submit">Hapus</button>
                            </form>
                        </td>
                        @endif
                    </tr>

                    @if ($canManage)
                    <div class="kk-modal-overlay" id="editStudentModal{{ $student->id }}" style="display:none;">
                        <div class="kk-modal">
                            <div class="kk-card-title">Edit Siswa</div>
                            <form method="POST" action="{{ route('students.update', $student) }}">
                                @csrf @method('PUT')
                                <div class="kk-field"><label class="kk-label">Nama</label><input class="kk-input" name="name" value="{{ $student->name }}" required></div>
                                <div class="kk-field"><label class="kk-label">NIS</label><input class="kk-input" name="student_number" value="{{ $student->student_number }}"></div>
                                <div class="kk-field"><label class="kk-label">No. HP</label><input class="kk-input" name="phone" value="{{ $student->phone }}"></div>
                                <div class="kk-field">
                                    <label class="kk-label">
                                        <input type="checkbox" name="active" value="1" {{ $student->active ? 'checked' : '' }}> Aktif
                                    </label>
                                </div>
                                <div style="display:flex; gap:8px; justify-content:flex-end;">
                                    <button type="button" class="kk-btn kk-btn-outline" onclick="document.getElementById('editStudentModal{{ $student->id }}').style.display='none'">Batal</button>
                                    <button type="submit" class="kk-btn">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    <div style="margin-top:16px;">{{ $students->links() }}</div>

    @if ($canManage)
    <div class="kk-modal-overlay" id="addStudentModal" style="display:none;">
        <div class="kk-modal">
            <div class="kk-card-title">Tambah Siswa</div>
            <form method="POST" action="{{ route('students.store') }}">
                @csrf
                <div class="kk-field"><label class="kk-label">Nama</label><input class="kk-input" name="name" required></div>
                <div class="kk-field"><label class="kk-label">NIS</label><input class="kk-input" name="student_number"></div>
                <div class="kk-field"><label class="kk-label">No. HP</label><input class="kk-input" name="phone"></div>
                <div class="kk-field"><label class="kk-label"><input type="checkbox" name="active" value="1" checked> Aktif</label></div>
                <div style="display:flex; gap:8px; justify-content:flex-end;">
                    <button type="button" class="kk-btn kk-btn-outline" onclick="document.getElementById('addStudentModal').style.display='none'">Batal</button>
                    <button type="submit" class="kk-btn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    @endif
@endsection

@push('head')
<style>
.kk-modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.35); z-index: 50; align-items: center; justify-content: center; }
.kk-modal { background: #fff; border-radius: 14px; padding: 24px; width: 100%; max-width: 380px; max-height: 90vh; overflow-y: auto; }
</style>
@endpush
