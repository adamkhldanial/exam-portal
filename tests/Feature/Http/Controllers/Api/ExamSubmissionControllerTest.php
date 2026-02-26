<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Exam;
use App\Models\ExamSubmission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\ExamSubmissionController
 */
final class ExamSubmissionControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_returns_exam_submissions(): void
    {
        $examSubmissions = ExamSubmission::factory()->count(3)->create();

        $response = $this->getJson(route('exam-submissions.index'));

        $response->assertOk();
        $response->assertJsonCount(3);
        $response->assertJsonFragment(['id' => $examSubmissions->first()->id]);
    }

    #[Test]
    public function store_creates_exam_submission(): void
    {
        $exam = Exam::factory()->create();
        $user = User::factory()->student()->create();
        $startedAt = now()->subMinutes(30)->toDateTimeString();

        $response = $this->postJson(route('exam-submissions.store'), [
            'exam_id' => $exam->id,
            'user_id' => $user->id,
            'started_at' => $startedAt,
            'status' => 'in_progress',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('exam_submissions', [
            'exam_id' => $exam->id,
            'user_id' => $user->id,
        ]);
    }

    #[Test]
    public function show_returns_exam_submission(): void
    {
        $examSubmission = ExamSubmission::factory()->create();

        $response = $this->getJson(route('exam-submissions.show', $examSubmission));

        $response->assertOk();
        $response->assertJsonFragment(['id' => $examSubmission->id]);
    }

    #[Test]
    public function update_updates_exam_submission(): void
    {
        $examSubmission = ExamSubmission::factory()->create();

        $response = $this->putJson(route('exam-submissions.update', $examSubmission), [
            'submitted_at' => now()->toDateTimeString(),
            'score' => 8,
            'total_marks' => 10,
            'status' => 'graded',
        ]);

        $examSubmission->refresh();

        $response->assertOk();
        $this->assertSame(8, $examSubmission->score);
        $this->assertSame(10, $examSubmission->total_marks);
        $this->assertSame('graded', $examSubmission->status);
    }

    #[Test]
    public function destroy_deletes_exam_submission(): void
    {
        $examSubmission = ExamSubmission::factory()->create();

        $response = $this->deleteJson(route('exam-submissions.destroy', $examSubmission));

        $response->assertNoContent();
        $this->assertDatabaseMissing('exam_submissions', ['id' => $examSubmission->id]);
    }
}
