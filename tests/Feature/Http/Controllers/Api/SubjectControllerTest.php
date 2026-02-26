<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\ClassRoom;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\SubjectController
 */
final class SubjectControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_returns_subjects(): void
    {
        $subjects = Subject::factory()->count(3)->create();

        $response = $this->getJson(route('subjects.index'));

        $response->assertOk();
        $response->assertJsonCount(3);
        $response->assertJsonFragment(['id' => $subjects->first()->id]);
    }

    #[Test]
    public function store_creates_subject(): void
    {
        $classRoom = ClassRoom::factory()->create();
        $payload = [
            'name' => fake()->words(2, true),
            'code' => strtoupper(fake()->unique()->bothify('SUB###')),
            'description' => fake()->sentence(),
            'class_room_id' => $classRoom->id,
            'created_by' => $classRoom->created_by,
        ];

        $response = $this->postJson(route('subjects.store'), $payload);

        $response->assertCreated();
        $this->assertDatabaseHas('subjects', [
            'code' => $payload['code'],
            'class_room_id' => $classRoom->id,
        ]);
    }

    #[Test]
    public function show_returns_subject(): void
    {
        $subject = Subject::factory()->create();

        $response = $this->getJson(route('subjects.show', $subject));

        $response->assertOk();
        $response->assertJsonFragment(['id' => $subject->id]);
    }

    #[Test]
    public function update_updates_subject(): void
    {
        $subject = Subject::factory()->create();
        $classRoom = ClassRoom::factory()->create();

        $response = $this->putJson(route('subjects.update', $subject), [
            'name' => 'Updated Subject',
            'code' => strtoupper(fake()->unique()->bothify('SUB###')),
            'description' => 'Updated description',
            'class_room_id' => $classRoom->id,
        ]);

        $subject->refresh();

        $response->assertOk();
        $this->assertSame('Updated Subject', $subject->name);
        $this->assertSame('Updated description', $subject->description);
        $this->assertSame($classRoom->id, $subject->class_room_id);
    }

    #[Test]
    public function destroy_deletes_subject(): void
    {
        $subject = Subject::factory()->create();

        $response = $this->deleteJson(route('subjects.destroy', $subject));

        $response->assertNoContent();
        $this->assertDatabaseMissing('subjects', ['id' => $subject->id]);
    }
}
