<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Lecturer;
use App\Http\Controllers\Student;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ─── Lecturer Routes ───────────────────────────────────────────────
    Route::middleware('role:lecturer')->prefix('lecturer')->name('lecturer.')->group(function () {

        // Classes
        Route::resource('classes', Lecturer\ClassRoomController::class)
            ->parameters(['classes' => 'classRoom']);
        Route::post('classes/{classRoom}/students', [Lecturer\ClassRoomController::class, 'assignStudents'])
            ->name('classes.assign-students');

        // Students
        Route::resource('students', Lecturer\StudentController::class)->only([
            'index', 'create', 'store', 'edit', 'update',
        ]);

        // Subjects
        Route::resource('subjects', Lecturer\SubjectController::class)->except('show');

        // Exams
        Route::resource('exams', Lecturer\ExamController::class);
        Route::post('exams/{exam}/toggle-publish', [Lecturer\ExamController::class, 'togglePublish'])
            ->name('exams.toggle-publish');

        // Questions (nested under exams)
        Route::get('exams/{exam}/questions/create', [Lecturer\QuestionController::class, 'create'])
            ->name('questions.create');
        Route::post('exams/{exam}/questions', [Lecturer\QuestionController::class, 'store'])
            ->name('questions.store');
        Route::get('exams/{exam}/questions/{question}/edit', [Lecturer\QuestionController::class, 'edit'])
            ->name('questions.edit');
        Route::put('exams/{exam}/questions/{question}', [Lecturer\QuestionController::class, 'update'])
            ->name('questions.update');
        Route::delete('exams/{exam}/questions/{question}', [Lecturer\QuestionController::class, 'destroy'])
            ->name('questions.destroy');

        // Results
        Route::get('exams/{exam}/results', [Lecturer\ResultController::class, 'index'])
            ->name('results.index');
        Route::get('exams/{exam}/results/{submission}', [Lecturer\ResultController::class, 'show'])
            ->name('results.show');
        Route::post('exams/{exam}/results/{submission}/grade', [Lecturer\ResultController::class, 'grade'])
            ->name('results.grade');
    });

    // ─── Student Routes ────────────────────────────────────────────────
    Route::middleware('role:student')->prefix('student')->name('student.')->group(function () {
        Route::get('exams', [Student\ExamController::class, 'index'])->name('exams.index');
        Route::get('exams/{exam}', [Student\ExamController::class, 'show'])->name('exams.show');
        Route::post('exams/{exam}/submit', [Student\ExamController::class, 'submit'])->name('exams.submit');
        Route::get('exams/{exam}/result', [Student\ExamController::class, 'result'])->name('exams.result');
    });
});

require __DIR__.'/auth.php';
