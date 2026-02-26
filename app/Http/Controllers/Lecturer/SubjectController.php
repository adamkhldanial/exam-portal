<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::where('created_by', auth()->id())
            ->with('classRoom')->withCount('exams')->get();

        return view('lecturer.subjects.index', compact('subjects'));
    }

    public function create()
    {
        $classes = auth()->user()->createdClasses()->get();

        return view('lecturer.subjects.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects',
            'description' => 'nullable|string',
            'class_room_id' => [
                'required',
                Rule::exists('class_rooms', 'id')
                    ->where(fn ($query) => $query->where('created_by', auth()->id())),
            ],
        ]);
        $data['created_by'] = auth()->id();
        Subject::create($data);

        return redirect()->route('lecturer.subjects.index')->with('success', 'Subject created.');
    }

    public function edit(Subject $subject)
    {
        $this->authorizeOwner($subject);
        $classes = auth()->user()->createdClasses()->get();

        return view('lecturer.subjects.edit', compact('subject', 'classes'));
    }

    public function update(Request $request, Subject $subject)
    {
        $this->authorizeOwner($subject);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code,'.$subject->id,
            'description' => 'nullable|string',
            'class_room_id' => [
                'required',
                Rule::exists('class_rooms', 'id')
                    ->where(fn ($query) => $query->where('created_by', auth()->id())),
            ],
        ]);
        $subject->update($data);

        return redirect()->route('lecturer.subjects.index')->with('success', 'Subject updated.');
    }

    public function destroy(Subject $subject)
    {
        $this->authorizeOwner($subject);
        $subject->delete();

        return redirect()->route('lecturer.subjects.index')->with('success', 'Subject deleted.');
    }

    private function authorizeOwner(Subject $subject): void
    {
        abort_unless((int) $subject->created_by === (int) auth()->id(), 403);
    }
}
