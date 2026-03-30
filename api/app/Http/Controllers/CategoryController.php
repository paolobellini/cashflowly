<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\StoreCategoryAction;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CategoryController extends Controller
{
    public function store(StoreCategoryRequest $request, StoreCategoryAction $action): JsonResponse
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();
        $category = $action->handle($validated);

        return new CategoryResource($category)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
