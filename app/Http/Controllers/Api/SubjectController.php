<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SubjectStoreRequest;
use App\Http\Requests\Api\SubjectUpdateRequest;
use App\Models\Subject;
use Illuminate\Http\JsonResponse;

class SubjectController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Subject::query()->latest()->get());
    }

    public function store(SubjectStoreRequest $request): JsonResponse
    {
        $subject = Subject::create($request->validated());

        return response()->json($subject, 201);
    }

    public function show(Subject $subject): JsonResponse
    {
        return response()->json($subject);
    }

    public function update(SubjectUpdateRequest $request, Subject $subject): JsonResponse
    {
        $subject->update($request->validated());

        return response()->json($subject->fresh());
    }

    public function destroy(Subject $subject): \Illuminate\Http\Response
    {
        $subject->delete();

        return response()->noContent();
    }
}
