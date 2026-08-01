<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function __construct(protected ActivityLogService $activityLog)
    {
        $this->authorizeResource(Student::class, 'student');
    }

    public function index(Request $request)
    {
        $query = Student::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('student_number', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('students.index', compact('students'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'student_number' => ['nullable', 'string', 'max:50'],
            'gender' => ['nullable', 'in:L,P'],
            'phone' => ['nullable', 'string', 'max:30'],
            'active' => ['sometimes', 'boolean'],
        ]);
        $data['active'] = $request->boolean('active', true);

        $student = Student::create($data);

        $this->activityLog->log('create', "Menambahkan siswa: {$student->name}", $student, null, $data);

        return back()->with('success', 'Data siswa berhasil disimpan.');
    }

    public function update(Request $request, Student $student)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'student_number' => ['nullable', 'string', 'max:50'],
            'gender' => ['nullable', 'in:L,P'],
            'phone' => ['nullable', 'string', 'max:30'],
            'active' => ['sometimes', 'boolean'],
        ]);
        $data['active'] = $request->boolean('active', true);

        $old = $student->only(array_keys($data));
        $student->update($data);

        $this->activityLog->log('update', "Memperbarui data siswa: {$student->name}", $student, $old, $data);

        return back()->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        $name = $student->name;
        $old = $student->toArray();
        $student->delete();

        $this->activityLog->log('delete', "Menghapus siswa: {$name}", $student, $old, null);

        return back()->with('success', 'Data siswa berhasil dihapus.');
    }
}
