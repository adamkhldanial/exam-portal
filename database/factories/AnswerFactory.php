<?php

namespace Database\Factories;

use App\Models\ExamSubmission;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'exam_submission_id' => ExamSubmission::factory(),
            'question_id' => Question::factory(),
            'option_id' => null,
            'answer_text' => fake()->sentence(),
            'marks_awarded' => null,
        ];
    }
}
