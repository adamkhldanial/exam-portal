<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamSubmissionFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['in_progress', 'submitted', 'graded']);
        $startedAt = now()->subMinutes(fake()->numberBetween(5, 240));
        $submittedAt = $status === 'in_progress'
            ? null
            : (clone $startedAt)->addMinutes(fake()->numberBetween(1, 120));
        $totalMarks = fake()->numberBetween(10, 100);
        $score = $status === 'in_progress' ? null : fake()->numberBetween(0, $totalMarks);

        return [
            'exam_id' => Exam::factory(),
            'user_id' => User::factory()->student(),
            'started_at' => $startedAt,
            'submitted_at' => $submittedAt,
            'score' => $score,
            'total_marks' => $status === 'in_progress' ? null : $totalMarks,
            'status' => $status,
        ];
    }
}
