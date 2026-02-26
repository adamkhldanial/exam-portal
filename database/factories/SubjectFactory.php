<?php

namespace Database\Factories;

use App\Models\ClassRoom;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'code' => strtoupper(fake()->unique()->bothify('SUB###')),
            'description' => fake()->sentence(),
            'class_room_id' => ClassRoom::factory(),
            'created_by' => function (array $attributes) {
                return ClassRoom::query()->findOrFail($attributes['class_room_id'])->created_by;
            },
        ];
    }
}
