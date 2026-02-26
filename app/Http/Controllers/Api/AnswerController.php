<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AnswerStoreRequest;
use App\Http\Requests\Api\AnswerUpdateRequest;
use App\Models\Answer;
use Illuminate\Http\JsonResponse;

class AnswerController extends Controller
{
    public function store(AnswerStoreRequest $request): JsonResponse
    {
        $answer = Answer::create($request->validated());

        return response()->json($answer, 201);
    }

    public function update(AnswerUpdateRequest $request, Answer $answer): JsonResponse
    {
        $answer->update($request->validated());

        return response()->json($answer->fresh());
    }
}
