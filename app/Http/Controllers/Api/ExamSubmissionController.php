<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ExamSubmissionStoreRequest;
use App\Http\Requests\Api\ExamSubmissionUpdateRequest;
use App\Models\ExamSubmission;
use Illuminate\Http\JsonResponse;

class ExamSubmissionController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(ExamSubmission::query()->latest()->get());
    }

    public function store(ExamSubmissionStoreRequest $request): JsonResponse
    {
        $examSubmission = ExamSubmission::create($request->validated());

        return response()->json($examSubmission, 201);
    }

    public function show(ExamSubmission $examSubmission): JsonResponse
    {
        return response()->json($examSubmission);
    }

    public function update(ExamSubmissionUpdateRequest $request, ExamSubmission $examSubmission): JsonResponse
    {
        $examSubmission->update($request->validated());

        return response()->json($examSubmission->fresh());
    }

    public function destroy(ExamSubmission $examSubmission): \Illuminate\Http\Response
    {
        $examSubmission->delete();

        return response()->noContent();
    }
}
