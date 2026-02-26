<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\ExamSubmission;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index(Exam $exam)
    {
        $this->authorizeExamOwner($exam);
        $submissions = $exam->submissions()->with('student')->latest()->get();

        return view('lecturer.results.index', compact('exam', 'submissions'));
    }

    public function show(Exam $exam, ExamSubmission $submission)
    {
        $this->authorizeExamOwner($exam);
        $this->assertSubmissionBelongsToExam($submission, $exam);
        $submission->load('student', 'answers.question.options', 'answers.option');

        return view('lecturer.results.show', compact('exam', 'submission'));
    }

    // Grade open text answers
    public function grade(Request $request, Exam $exam, ExamSubmission $submission)
    {
        $this->authorizeExamOwner($exam);
        $this->assertSubmissionBelongsToExam($submission, $exam);
        $request->validate(['marks' => 'required|array', 'marks.*' => 'nullable|integer|min:0']);

        foreach ($request->marks as $answerId => $marks) {
            $answer = $submission->answers()->find($answerId);
            if ($answer && ! $answer->question->isMultipleChoice()) {
                $maxMarks = $answer->question->marks;
                $answer->update(['marks_awarded' => min($marks, $maxMarks)]);
            }
        }

        $submission->calculateScore();
        $submission->update(['status' => 'graded']);

        return back()->with('success', 'Submission graded.');
    }

    private function authorizeExamOwner(Exam $exam): void
    {
        abort_unless((int) $exam->created_by === (int) auth()->id(), 403);
    }

    private function assertSubmissionBelongsToExam(ExamSubmission $submission, Exam $exam): void
    {
        abort_unless((int) $submission->exam_id === (int) $exam->id, 404);
    }
}
