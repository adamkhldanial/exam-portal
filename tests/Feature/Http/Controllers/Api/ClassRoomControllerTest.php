<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\ClassRoomController
 */
final class ClassRoomControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_returns_class_rooms(): void
    {
        $classRooms = ClassRoom::factory()->count(3)->create();

        $response = $this->getJson(route('class-rooms.index'));

        $response->assertOk();
        $response->assertJsonCount(3);
        $response->assertJsonFragment(['id' => $classRooms->first()->id]);
    }

    #[Test]
    public function store_creates_class_room(): void
    {
        $lecturer = User::factory()->lecturer()->create();
        $payload = [
            'name' => fake()->words(2, true),
            'code' => strtoupper(fake()->unique()->bothify('CLS###')),
            'description' => fake()->sentence(),
            'created_by' => $lecturer->id,
        ];

        $response = $this->postJson(route('class-rooms.store'), $payload);

        $response->assertCreated();
        $this->assertDatabaseHas('class_rooms', [
            'code' => $payload['code'],
            'created_by' => $lecturer->id,
        ]);
    }

    #[Test]
    public function show_returns_class_room(): void
    {
        $classRoom = ClassRoom::factory()->create();

        $response = $this->getJson(route('class-rooms.show', $classRoom));

        $response->assertOk();
        $response->assertJsonFragment(['id' => $classRoom->id]);
    }

    #[Test]
    public function update_updates_class_room(): void
    {
        $classRoom = ClassRoom::factory()->create();
        $response = $this->putJson(route('class-rooms.update', $classRoom), [
            'name' => 'Updated Class',
            'code' => strtoupper(fake()->unique()->bothify('CLS###')),
            'description' => 'Updated description',
        ]);

        $classRoom->refresh();

        $response->assertOk();
        $this->assertSame('Updated Class', $classRoom->name);
        $this->assertSame('Updated description', $classRoom->description);
    }

    #[Test]
    public function destroy_deletes_class_room(): void
    {
        $classRoom = ClassRoom::factory()->create();

        $response = $this->deleteJson(route('class-rooms.destroy', $classRoom));

        $response->assertNoContent();
        $this->assertDatabaseMissing('class_rooms', ['id' => $classRoom->id]);
    }
}
