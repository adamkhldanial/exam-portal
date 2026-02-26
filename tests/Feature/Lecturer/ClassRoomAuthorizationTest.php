<?php

use App\Models\ClassRoom;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows the owner lecturer to view and edit their class', function () {
    $lecturer = User::factory()->lecturer()->create();
    $classRoom = ClassRoom::factory()->create(['created_by' => $lecturer->id]);

    $this->actingAs($lecturer)
        ->get(route('lecturer.classes.show', $classRoom))
        ->assertOk();

    $this->actingAs($lecturer)
        ->get(route('lecturer.classes.edit', $classRoom))
        ->assertOk();
});

it('forbids other lecturers from accessing classes they do not own', function () {
    $owner = User::factory()->lecturer()->create();
    $otherLecturer = User::factory()->lecturer()->create();
    $classRoom = ClassRoom::factory()->create(['created_by' => $owner->id]);

    $this->actingAs($otherLecturer)
        ->get(route('lecturer.classes.show', $classRoom))
        ->assertForbidden();

    $this->actingAs($otherLecturer)
        ->get(route('lecturer.classes.edit', $classRoom))
        ->assertForbidden();
});
