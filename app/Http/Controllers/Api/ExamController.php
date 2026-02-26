<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ExamStoreRequest;
use App\Http\Requests\Api\ExamUpdateRequest;
use App\Models\Exam;
use Illuminate\Http\JsonResponse;

class ExamController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Exam::query()->latest()->get());
    }

    public function store(ExamStoreRequest $request): JsonResponse
    {
        $exam = Exam::create($request->validated());

        return response()->json($exam, 201);
    }

    public function show(Exam $exam): JsonResponse
    {
        return response()->json($exam);
    }

    public function update(ExamUpdateRequest $request, Exam $exam): JsonResponse
    {
        $exam->update($request->validated());

        return response()->json($exam->fresh());
    }

    public function destroy(Exam $exam): \Illuminate\Http\Response
    {
        $exam->delete();

        return response()->noContent();
    }
}
