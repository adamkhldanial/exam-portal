<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index()
    {
        $ownedClassIds = auth()->user()->createdClasses()->pluck('id');

        $students = User::query()
            ->where('role', 'student')
            ->withCount('classRooms')
            ->withCount([
                'classRooms as my_classes_count' => fn ($query) => $query->whereIn('class_rooms.id', $ownedClassIds),
            ])
            ->orderBy('name')
            ->get();

        return view('lecturer.students.index', compact('students'));
    }

    public function create(Request $request)
    {
        $classes = auth()->user()->createdClasses()->orderBy('name')->get();
        $preselectedClassId = $request->integer('class_room_id');

        if (! $classes->contains('id', $preselectedClassId)) {
            $preselectedClassId = null;
        }

        return view('lecturer.students.create', compact('classes', 'preselectedClassId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'class_room_ids' => ['nullable', 'array'],
            'class_room_ids.*' => [
                'integer',
                Rule::exists('class_rooms', 'id')->where(fn ($query) => $query->where('created_by', auth()->id())),
            ],
        ]);

        $student = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => 'student',
        ]);

        if (! empty($data['class_room_ids'])) {
            $student->classRooms()->sync($data['class_room_ids']);
        }

        return redirect()
            ->route('lecturer.students.index')
            ->with('success', 'Student created and class enrollment saved.');
    }

    public function edit(User $student)
    {
        $this->assertStudent($student);
        $classes = auth()->user()->createdClasses()->orderBy('name')->get();
        $assignedClassIds = $student->classRooms()
            ->whereIn('class_rooms.id', $classes->pluck('id'))
            ->pluck('class_rooms.id')
            ->all();

        return view('lecturer.students.edit', compact('student', 'classes', 'assignedClassIds'));
    }

    public function update(Request $request, User $student)
    {
        $this->assertStudent($student);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$student->id,
            'password' => 'nullable|string|min:8|confirmed',
            'class_room_ids' => ['nullable', 'array'],
            'class_room_ids.*' => [
                'integer',
                Rule::exists('class_rooms', 'id')->where(fn ($query) => $query->where('created_by', auth()->id())),
            ],
        ]);

        $updateData = Arr::only($data, ['name', 'email']);
        if (! empty($data['password'])) {
            $updateData['password'] = $data['password'];
        }
        $student->update($updateData);

        $this->syncOwnedClassAssignments($student, $data['class_room_ids'] ?? []);

        return redirect()
            ->route('lecturer.students.index')
            ->with('success', 'Student details and class enrollment updated.');
    }

    private function assertStudent(User $student): void
    {
        abort_unless($student->role === 'student', 404);
    }

    private function syncOwnedClassAssignments(User $student, array $selectedClassIds): void
    {
        $ownedClassIds = auth()->user()->createdClasses()->pluck('id');
        $selectedInOwnedScope = collect($selectedClassIds)
            ->map(fn ($id) => (int) $id)
            ->filter(fn ($id) => $ownedClassIds->contains($id))
            ->unique()
            ->values();

        $currentInOwnedScope = $student->classRooms()
            ->whereIn('class_rooms.id', $ownedClassIds)
            ->pluck('class_rooms.id');

        $toDetach = $currentInOwnedScope->diff($selectedInOwnedScope);
        $toAttach = $selectedInOwnedScope->diff($currentInOwnedScope);

        if ($toDetach->isNotEmpty()) {
            $student->classRooms()->detach($toDetach->all());
        }

        if ($toAttach->isNotEmpty()) {
            $student->classRooms()->attach($toAttach->all());
        }
    }
}
