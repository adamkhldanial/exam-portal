<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\QuestionStoreRequest;
use App\Http\Requests\Api\QuestionUpdateRequest;
use App\Models\Question;
use Illuminate\Http\JsonResponse;

class QuestionController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Question::query()->latest()->get());
    }

    public function store(QuestionStoreRequest $request): JsonResponse
    {
        $question = Question::create($request->validated());

        return response()->json($question, 201);
    }

    public function show(Question $question): JsonResponse
    {
        return response()->json($question);
    }

    public function update(QuestionUpdateRequest $request, Question $question): JsonResponse
    {
        $question->update($request->validated());

        return response()->json($question->fresh());
    }

    public function destroy(Question $question): \Illuminate\Http\Response
    {
        $question->delete();

        return response()->noContent();
    }
}
