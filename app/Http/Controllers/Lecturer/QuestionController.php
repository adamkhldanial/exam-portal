<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function create(Exam $exam)
    {
        $this->authorizeExamOwner($exam);

        return view('lecturer.questions.create', compact('exam'));
    }

    public function store(Request $request, Exam $exam)
    {
        $this->authorizeExamOwner($exam);

        $data = $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:multiple_choice,open_text',
            'marks' => 'required|integer|min:1',
            'order' => 'nullable|integer',
            'options' => 'required_if:type,multiple_choice|array|min:2',
            'options.*.text' => 'required_if:type,multiple_choice|string',
            'correct_option' => 'required_if:type,multiple_choice|integer',
        ]);

        $question = $exam->questions()->create([
            'question_text' => $data['question_text'],
            'type' => $data['type'],
            'marks' => $data['marks'],
            'order' => $data['order'] ?? ($exam->questions()->count() + 1),
        ]);

        if ($data['type'] === 'multiple_choice' && isset($data['options'])) {
            foreach ($data['options'] as $idx => $opt) {
                $question->options()->create([
                    'option_text' => $opt['text'],
                    'is_correct' => ($idx == $request->correct_option),
                ]);
            }
        }

        return redirect()->route('lecturer.exams.show', $exam)->with('success', 'Question added.');
    }

    public function edit(Exam $exam, Question $question)
    {
        $this->authorizeExamOwner($exam);
        $this->assertQuestionBelongsToExam($question, $exam);
        $question->load('options');

        return view('lecturer.questions.edit', compact('exam', 'question'));
    }

    public function update(Request $request, Exam $exam, Question $question)
    {
        $this->authorizeExamOwner($exam);
        $this->assertQuestionBelongsToExam($question, $exam);

        $data = $request->validate([
            'question_text' => 'required|string',
            'marks' => 'required|integer|min:1',
            'order' => 'nullable|integer',
            'options' => 'required_if:type,multiple_choice|array|min:2',
            'options.*.id' => 'nullable|exists:options,id',
            'options.*.text' => 'required_if:type,multiple_choice|string',
            'correct_option' => 'required_if:type,multiple_choice',
        ]);

        $question->update([
            'question_text' => $data['question_text'],
            'marks' => $data['marks'],
            'order' => $data['order'] ?? $question->order,
        ]);

        if ($question->type === 'multiple_choice' && isset($data['options'])) {
            $question->options()->delete();
            foreach ($data['options'] as $idx => $opt) {
                $question->options()->create([
                    'option_text' => $opt['text'],
                    'is_correct' => ($idx == $request->correct_option),
                ]);
            }
        }

        return redirect()->route('lecturer.exams.show', $exam)->with('success', 'Question updated.');
    }

    public function destroy(Exam $exam, Question $question)
    {
        $this->authorizeExamOwner($exam);
        $this->assertQuestionBelongsToExam($question, $exam);
        $question->delete();

        return back()->with('success', 'Question deleted.');
    }

    private function authorizeExamOwner(Exam $exam): void
    {
        abort_unless((int) $exam->created_by === (int) auth()->id(), 403);
    }

    private function assertQuestionBelongsToExam(Question $question, Exam $exam): void
    {
        abort_unless((int) $question->exam_id === (int) $exam->id, 404);
    }
}
