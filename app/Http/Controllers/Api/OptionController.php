<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\OptionStoreRequest;
use App\Http\Requests\Api\OptionUpdateRequest;
use App\Models\Option;
use Illuminate\Http\JsonResponse;

class OptionController extends Controller
{
    public function store(OptionStoreRequest $request): JsonResponse
    {
        $option = Option::create($request->validated());

        return response()->json($option, 201);
    }

    public function update(OptionUpdateRequest $request, Option $option): JsonResponse
    {
        $option->update($request->validated());

        return response()->json($option->fresh());
    }

    public function destroy(Option $option): \Illuminate\Http\Response
    {
        $option->delete();

        return response()->noContent();
    }
}
