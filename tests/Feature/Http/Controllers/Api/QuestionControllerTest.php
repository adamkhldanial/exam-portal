<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\QuestionController
 */
final class QuestionControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_returns_questions(): void
    {
        $questions = Question::factory()->count(3)->create();

        $response = $this->getJson(route('questions.index'));

        $response->assertOk();
        $response->assertJsonCount(3);
        $response->assertJsonFragment(['id' => $questions->first()->id]);
    }

    #[Test]
    public function store_creates_question(): void
    {
        $exam = Exam::factory()->create();
        $response = $this->postJson(route('questions.store'), [
            'exam_id' => $exam->id,
            'question_text' => 'What is PHP?',
            'type' => 'open_text',
            'marks' => 5,
            'order' => 1,
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('questions', [
            'exam_id' => $exam->id,
            'question_text' => 'What is PHP?',
        ]);
    }

    #[Test]
    public function show_returns_question(): void
    {
        $question = Question::factory()->create();

        $response = $this->getJson(route('questions.show', $question));

        $response->assertOk();
        $response->assertJsonFragment(['id' => $question->id]);
    }

    #[Test]
    public function update_updates_question(): void
    {
        $question = Question::factory()->create();

        $response = $this->putJson(route('questions.update', $question), [
            'question_text' => 'Updated question text',
            'type' => 'multiple_choice',
            'marks' => 3,
            'order' => 2,
        ]);

        $question->refresh();

        $response->assertOk();
        $this->assertSame('Updated question text', $question->question_text);
        $this->assertSame('multiple_choice', $question->type);
        $this->assertSame(3, $question->marks);
        $this->assertSame(2, $question->order);
    }

    #[Test]
    public function destroy_deletes_question(): void
    {
        $question = Question::factory()->create();

        $response = $this->deleteJson(route('questions.destroy', $question));

        $response->assertNoContent();
        $this->assertDatabaseMissing('questions', ['id' => $question->id]);
    }
}
