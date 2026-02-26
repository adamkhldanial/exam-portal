<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ClassRoomStoreRequest;
use App\Http\Requests\Api\ClassRoomUpdateRequest;
use App\Models\ClassRoom;
use Illuminate\Http\JsonResponse;

class ClassRoomController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(ClassRoom::query()->latest()->get());
    }

    public function store(ClassRoomStoreRequest $request): JsonResponse
    {
        $classRoom = ClassRoom::create($request->validated());

        return response()->json($classRoom, 201);
    }

    public function show(ClassRoom $classRoom): JsonResponse
    {
        return response()->json($classRoom);
    }

    public function update(ClassRoomUpdateRequest $request, ClassRoom $classRoom): JsonResponse
    {
        $classRoom->update($request->validated());

        return response()->json($classRoom->fresh());
    }

    public function destroy(ClassRoom $classRoom): \Illuminate\Http\Response
    {
        $classRoom->delete();

        return response()->noContent();
    }
}
