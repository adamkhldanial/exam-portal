<?php

use App\Http\Controllers\Api\AnswerController;
use App\Http\Controllers\Api\ClassRoomController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\ExamSubmissionController;
use App\Http\Controllers\Api\OptionController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\SubjectController;
use Illuminate\Support\Facades\Route;

Route::apiResource('class-rooms', ClassRoomController::class);
Route::apiResource('subjects', SubjectController::class);
Route::apiResource('exams', ExamController::class);
Route::apiResource('questions', QuestionController::class);
Route::apiResource('exam-submissions', ExamSubmissionController::class);
Route::apiResource('options', OptionController::class)->only(['store', 'update', 'destroy']);
Route::apiResource('answers', AnswerController::class)->only(['store', 'update']);
