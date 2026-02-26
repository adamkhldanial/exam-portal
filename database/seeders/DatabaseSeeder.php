<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\Exam;
use App\Models\Option;
use App\Models\Question;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Lecturer
        $lecturer = User::create([
            'name' => 'En. Adam Mikhail',
            'email' => 'adam.mikhaild@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'lecturer',
        ]);

        // Create Students
        $students = collect();
        for ($i = 1; $i <= 5; $i++) {
            $students->push(User::create([
                'name' => "Student {$i}",
                'email' => "adam.mikhaild{$i}@gmail.com",
                'password' => bcrypt('password'),
                'role' => 'student',
            ]));
        }

        // Create Class
        $class = ClassRoom::create([
            'name' => 'Computer Science Year 1',
            'code' => 'CS110',
            'description' => 'First year CS students',
            'created_by' => $lecturer->id,
        ]);

        // Enroll students
        $class->students()->attach($students->pluck('id'));

        // Create Subject
        $subject = Subject::create([
            'name' => 'Introduction to Computer and Programming',
            'code' => 'C110',
            'class_room_id' => $class->id,
            'created_by' => $lecturer->id,
        ]);

        // Create Exam
        $exam = Exam::create([
            'title' => 'Mid-Term Exam',
            'description' => 'Covers topics from Week 1-6',
            'subject_id' => $subject->id,
            'created_by' => $lecturer->id,
            'time_limit' => 30,
            'passing_score' => 60,
            'is_published' => true,
        ]);

        // MCQ Question
        $q1 = Question::create([
            'exam_id' => $exam->id,
            'question_text' => 'Which keyword is used to declare a variable in PHP?',
            'type' => 'multiple_choice',
            'marks' => 2,
            'order' => 1,
        ]);
        Option::insert([
            ['question_id' => $q1->id, 'option_text' => 'var',   'is_correct' => false, 'created_at' => now(), 'updated_at' => now()],
            ['question_id' => $q1->id, 'option_text' => '$',     'is_correct' => true,  'created_at' => now(), 'updated_at' => now()],
            ['question_id' => $q1->id, 'option_text' => 'let',   'is_correct' => false, 'created_at' => now(), 'updated_at' => now()],
            ['question_id' => $q1->id, 'option_text' => 'const', 'is_correct' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Open Text Question
        Question::create([
            'exam_id' => $exam->id,
            'question_text' => 'Explain what a loop is and give one example in PHP.',
            'type' => 'open_text',
            'marks' => 5,
            'order' => 2,
        ]);
    }
}
