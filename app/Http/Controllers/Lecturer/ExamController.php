<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::where('created_by', auth()->id())
            ->with('subject.classRoom')
            ->withCount('questions', 'submissions')
            ->latest()->get();

        return view('lecturer.exams.index', compact('exams'));
    }

    public function create()
    {
        $subjects = Subject::where('created_by', auth()->id())->with('classRoom')->get();

        return view('lecturer.exams.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => [
                'required',
                Rule::exists('subjects', 'id')
                    ->where(fn ($query) => $query->where('created_by', auth()->id())),
            ],
            'time_limit' => 'required|integer|min:1|max:300',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'passing_score' => 'required|integer|min:0|max:100',
        ]);
        $data['created_by'] = auth()->id();
        $exam = Exam::create($data);

        return redirect()->route('lecturer.exams.show', $exam)->with('success', 'Exam created. Now add questions.');
    }

    public function show(Exam $exam)
    {
        $this->authorizeOwner($exam);
        $exam->load('questions.options', 'subject.classRoom');

        return view('lecturer.exams.show', compact('exam'));
    }

    public function edit(Exam $exam)
    {
        $this->authorizeOwner($exam);
        $subjects = Subject::where('created_by', auth()->id())->with('classRoom')->get();

        return view('lecturer.exams.edit', compact('exam', 'subjects'));
    }

    public function update(Request $request, Exam $exam)
    {
        $this->authorizeOwner($exam);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject_id' => [
                'required',
                Rule::exists('subjects', 'id')
                    ->where(fn ($query) => $query->where('created_by', auth()->id())),
            ],
            'time_limit' => 'required|integer|min:1|max:300',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'passing_score' => 'required|integer|min:0|max:100',
        ]);
        $exam->update($data);

        return redirect()->route('lecturer.exams.show', $exam)->with('success', 'Exam updated.');
    }

    public function destroy(Exam $exam)
    {
        $this->authorizeOwner($exam);
        $exam->delete();

        return redirect()->route('lecturer.exams.index')->with('success', 'Exam deleted.');
    }

    public function togglePublish(Exam $exam)
    {
        $this->authorizeOwner($exam);
        $exam->update(['is_published' => ! $exam->is_published]);
        $status = $exam->is_published ? 'published' : 'unpublished';

        return back()->with('success', "Exam {$status}.");
    }

    private function authorizeOwner(Exam $exam): void
    {
        abort_unless((int) $exam->created_by === (int) auth()->id(), 403);
    }
}
