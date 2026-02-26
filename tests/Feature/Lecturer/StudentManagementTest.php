<?php

use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('lecturer can create a student and assign them to their classes', function () {
    $lecturer = User::factory()->lecturer()->create();
    $classA = ClassRoom::factory()->create(['created_by' => $lecturer->id]);
    $classB = ClassRoom::factory()->create(['created_by' => $lecturer->id]);

    $response = $this->actingAs($lecturer)->post(route('lecturer.students.store'), [
        'name' => 'Student Alpha',
        'email' => 'student.alpha@example.test',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'class_room_ids' => [$classA->id, $classB->id],
    ]);

    $response->assertRedirect(route('lecturer.students.index'));
    $response->assertSessionHas('success');

    $student = User::where('email', 'student.alpha@example.test')->first();
    expect($student)->not->toBeNull();
    expect($student->role)->toBe('student');
    expect(Hash::check('password123', $student->password))->toBeTrue();

    $assignedClassIds = $student->classRooms()->pluck('class_rooms.id')->sort()->values()->all();
    expect($assignedClassIds)->toBe([$classA->id, $classB->id]);
});

it('rejects enrollment into classes not owned by the lecturer', function () {
    $lecturer = User::factory()->lecturer()->create();
    $otherLecturer = User::factory()->lecturer()->create();
    $otherClass = ClassRoom::factory()->create(['created_by' => $otherLecturer->id]);

    $response = $this->actingAs($lecturer)->from(route('lecturer.students.create'))->post(route('lecturer.students.store'), [
        'name' => 'Student Beta',
        'email' => 'student.beta@example.test',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'class_room_ids' => [$otherClass->id],
    ]);

    $response->assertRedirect(route('lecturer.students.create'));
    $response->assertSessionHasErrors('class_room_ids.0');
    $this->assertDatabaseMissing('users', ['email' => 'student.beta@example.test']);
});

it('updates only the logged-in lecturers class assignments for a student', function () {
    $lecturerA = User::factory()->lecturer()->create();
    $lecturerB = User::factory()->lecturer()->create();

    $classA = ClassRoom::factory()->create(['created_by' => $lecturerA->id]);
    $classB = ClassRoom::factory()->create(['created_by' => $lecturerB->id]);

    $student = User::factory()->student()->create();
    $student->classRooms()->sync([$classA->id, $classB->id]);

    $response = $this->actingAs($lecturerA)->put(route('lecturer.students.update', $student), [
        'name' => 'Student Updated',
        'email' => $student->email,
        'class_room_ids' => [],
    ]);

    $response->assertRedirect(route('lecturer.students.index'));
    $response->assertSessionHas('success');

    $student->refresh();
    expect($student->name)->toBe('Student Updated');

    $assignedClassIds = $student->classRooms()->pluck('class_rooms.id')->sort()->values()->all();
    expect($assignedClassIds)->toBe([$classB->id]);
});

it('returns 404 when trying to manage a non-student account', function () {
    $lecturer = User::factory()->lecturer()->create();
    $anotherLecturer = User::factory()->lecturer()->create();

    $this->actingAs($lecturer)
        ->get(route('lecturer.students.edit', $anotherLecturer))
        ->assertNotFound();
});
