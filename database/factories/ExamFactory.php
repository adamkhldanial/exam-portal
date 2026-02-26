<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $startsAt = fake()->optional()->dateTimeBetween('-2 days', '+2 days');
        $endsAt = $startsAt
            ? fake()->optional()->dateTimeBetween($startsAt, '+7 days')
            : null;

        return [
            'title' => fake()->sentence(4),
            'description' => fake()->sentence(),
            'subject_id' => Subject::factory(),
            'created_by' => function (array $attributes) {
                return Subject::query()->findOrFail($attributes['subject_id'])->created_by;
            },
            'time_limit' => fake()->numberBetween(10, 180),
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'is_published' => fake()->boolean(),
            'passing_score' => fake()->numberBetween(40, 80),
        ];
    }
}
