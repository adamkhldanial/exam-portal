<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Answer;
use App\Models\ExamSubmission;
use App\Models\Option;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\AnswerController
 */
final class AnswerControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function store_creates_answer(): void
    {
        $examSubmission = ExamSubmission::factory()->create();
        $question = Question::factory()->create();
        $option = Option::factory()->create();

        $response = $this->postJson(route('answers.store'), [
            'exam_submission_id' => $examSubmission->id,
            'question_id' => $question->id,
            'option_id' => $option->id,
            'answer_text' => 'Sample answer',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('answers', [
            'exam_submission_id' => $examSubmission->id,
            'question_id' => $question->id,
            'answer_text' => 'Sample answer',
        ]);
    }

    #[Test]
    public function update_updates_answer(): void
    {
        $answer = Answer::factory()->create();
        $option = Option::factory()->create();

        $response = $this->putJson(route('answers.update', $answer), [
            'option_id' => $option->id,
            'answer_text' => 'Updated answer',
            'marks_awarded' => 2,
        ]);

        $answer->refresh();

        $response->assertOk();
        $this->assertEquals($option->id, $answer->option_id);
        $this->assertEquals('Updated answer', $answer->answer_text);
        $this->assertEquals(2, $answer->marks_awarded);
    }
}
