<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClassRoomController extends Controller
{
    public function index()
    {
        $classes = auth()->user()->createdClasses()->withCount('students', 'subjects')->get();

        return view('lecturer.classes.index', compact('classes'));
    }

    public function create()
    {
        return view('lecturer.classes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:class_rooms',
            'description' => 'nullable|string',
        ]);
        $data['created_by'] = auth()->id();
        ClassRoom::create($data);

        return redirect()->route('lecturer.classes.index')->with('success', 'Class created successfully.');
    }

    public function show(ClassRoom $classRoom)
    {
        $this->authorizeOwner($classRoom);
        $classRoom->load('students', 'subjects.exams');
        $allStudents = User::where('role', 'student')->get();

        return view('lecturer.classes.show', compact('classRoom', 'allStudents'));
    }

    public function edit(ClassRoom $classRoom)
    {
        $this->authorizeOwner($classRoom);

        return view('lecturer.classes.edit', compact('classRoom'));
    }

    public function update(Request $request, ClassRoom $classRoom)
    {
        $this->authorizeOwner($classRoom);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:class_rooms,code,'.$classRoom->id,
            'description' => 'nullable|string',
        ]);
        $classRoom->update($data);

        return redirect()->route('lecturer.classes.index')->with('success', 'Class updated.');
    }

    public function destroy(ClassRoom $classRoom)
    {
        $this->authorizeOwner($classRoom);
        $classRoom->delete();

        return redirect()->route('lecturer.classes.index')->with('success', 'Class deleted.');
    }

    public function assignStudents(Request $request, ClassRoom $classRoom)
    {
        $this->authorizeOwner($classRoom);
        $request->validate([
            'student_ids' => ['array'],
            'student_ids.*' => [
                'integer',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'student')),
            ],
        ]);
        $classRoom->students()->sync($request->student_ids ?? []);

        return back()->with('success', 'Students updated.');
    }

    private function authorizeOwner(ClassRoom $classRoom): void
    {
        abort_unless((int) $classRoom->created_by === (int) auth()->id(), 403);
    }
}
