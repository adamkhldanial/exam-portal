<?php

namespace Database\Factories;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'exam_id' => Exam::factory(),
            'question_text' => fake()->sentence(),
            'type' => fake()->randomElement(['multiple_choice', 'open_text']),
            'marks' => fake()->numberBetween(1, 10),
            'order' => fake()->numberBetween(1, 30),
        ];
    }
}
