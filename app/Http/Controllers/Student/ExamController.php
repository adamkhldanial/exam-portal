<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Exam;
use App\Models\ExamSubmission;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $classIds = $user->classRooms()->pluck('class_rooms.id');

        $exams = Exam::whereHas('subject', fn ($q) => $q->whereIn('class_room_id', $classIds))
            ->where('is_published', true)
            ->with('subject.classRoom')
            ->withCount('questions')
            ->get();

        $submittedExamIds = $user->submissions()
            ->whereIn('status', ['submitted', 'graded'])
            ->pluck('exam_id');

        $inProgressSubmission = $user->submissions()
            ->where('status', 'in_progress')
            ->with('exam')
            ->first();

        return view('student.exams.index', compact('exams', 'submittedExamIds', 'inProgressSubmission'));
    }

    public function show(Exam $exam)
    {
        $this->checkAccess($exam);
        abort_unless($exam->isAvailable(), 403, 'This exam is not currently available.');

        // Check for existing in-progress submission
        $submission = ExamSubmission::firstOrCreate(
            ['exam_id' => $exam->id, 'user_id' => auth()->id()],
            ['started_at' => now(), 'status' => 'in_progress']
        );

        // If already submitted, redirect to result
        if (in_array($submission->status, ['submitted', 'graded'])) {
            return redirect()->route('student.exams.result', $exam);
        }

        // Check if timed out
        if ($submission->isTimedOut()) {
            $this->autoSubmit($submission);

            return redirect()->route('student.exams.result', $exam);
        }

        $exam->load('questions.options');
        $existingAnswers = $submission->answers()->pluck('option_id', 'question_id');
        $textAnswers = $submission->answers()->pluck('answer_text', 'question_id');
        $remainingSeconds = max(0, $exam->time_limit * 60 - now()->diffInSeconds($submission->started_at));

        return view('student.exams.show', compact('exam', 'submission', 'existingAnswers', 'textAnswers', 'remainingSeconds'));
    }

    public function submit(Request $request, Exam $exam)
    {
        $this->checkAccess($exam);

        $submission = ExamSubmission::where('exam_id', $exam->id)
            ->where('user_id', auth()->id())
            ->where('status', 'in_progress')
            ->firstOrFail();

        // Save all answers
        foreach ($exam->questions as $question) {
            $answerData = ['exam_submission_id' => $submission->id, 'question_id' => $question->id];

            if ($question->isMultipleChoice()) {
                $answerData['option_id'] = $request->input("answers.{$question->id}");
            } else {
                $answerData['answer_text'] = $request->input("answers.{$question->id}");
            }

            Answer::updateOrCreate(
                ['exam_submission_id' => $submission->id, 'question_id' => $question->id],
                $answerData
            );
        }

        $submission->update(['submitted_at' => now(), 'status' => 'submitted']);
        $submission->calculateScore();

        return redirect()->route('student.exams.result', $exam)->with('success', 'Exam submitted!');
    }

    public function result(Exam $exam)
    {
        $this->checkAccess($exam);
        $submission = ExamSubmission::where('exam_id', $exam->id)
            ->where('user_id', auth()->id())
            ->with('answers.question.options', 'answers.option')
            ->firstOrFail();

        return view('student.exams.result', compact('exam', 'submission'));
    }

    private function checkAccess(Exam $exam): void
    {
        $classIds = auth()->user()->classRooms()->pluck('class_rooms.id');
        abort_unless(
            $classIds->contains($exam->subject->class_room_id),
            403, 'You do not have access to this exam.'
        );
    }

    private function autoSubmit(ExamSubmission $submission): void
    {
        $submission->update(['submitted_at' => now(), 'status' => 'submitted']);
        $submission->calculateScore();
    }
}
