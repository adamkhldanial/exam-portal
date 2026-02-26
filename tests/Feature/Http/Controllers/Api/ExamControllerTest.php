<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\ExamController
 */
final class ExamControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_returns_exams(): void
    {
        $exams = Exam::factory()->count(3)->create();

        $response = $this->getJson(route('exams.index'));

        $response->assertOk();
        $response->assertJsonCount(3);
        $response->assertJsonFragment(['id' => $exams->first()->id]);
    }

    #[Test]
    public function store_creates_exam(): void
    {
        $subject = Subject::factory()->create();
        $payload = [
            'title' => 'Midterm',
            'description' => 'Exam description',
            'subject_id' => $subject->id,
            'created_by' => $subject->created_by,
            'time_limit' => 60,
            'passing_score' => 50,
            'is_published' => false,
        ];

        $response = $this->postJson(route('exams.store'), $payload);

        $response->assertCreated();
        $this->assertDatabaseHas('exams', [
            'title' => 'Midterm',
            'subject_id' => $subject->id,
            'created_by' => $subject->created_by,
        ]);
    }

    #[Test]
    public function show_returns_exam(): void
    {
        $exam = Exam::factory()->create();

        $response = $this->getJson(route('exams.show', $exam));

        $response->assertOk();
        $response->assertJsonFragment(['id' => $exam->id]);
    }

    #[Test]
    public function update_updates_exam(): void
    {
        $exam = Exam::factory()->create();
        $subject = Subject::factory()->create(['created_by' => $exam->created_by]);

        $response = $this->putJson(route('exams.update', $exam), [
            'title' => 'Updated Exam',
            'description' => 'Updated exam description',
            'subject_id' => $subject->id,
            'time_limit' => 45,
            'starts_at' => now()->addHour()->toDateTimeString(),
            'ends_at' => now()->addHours(2)->toDateTimeString(),
            'is_published' => true,
            'passing_score' => 65,
        ]);

        $exam->refresh();

        $response->assertOk();
        $this->assertSame('Updated Exam', $exam->title);
        $this->assertSame(45, $exam->time_limit);
        $this->assertTrue($exam->is_published);
        $this->assertSame(65, $exam->passing_score);
    }

    #[Test]
    public function destroy_deletes_exam(): void
    {
        $exam = Exam::factory()->create();

        $response = $this->deleteJson(route('exams.destroy', $exam));

        $response->assertNoContent();
        $this->assertDatabaseMissing('exams', ['id' => $exam->id]);
    }
}
