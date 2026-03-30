<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\DestroyCategoryAction;
use App\Actions\StoreCategoryAction;
use App\Actions\UpdateCategoryAction;
use App\Enums\CategoryType;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $categories = Category::query()
            ->when($request->has('type'), fn ($query) => $query->ofType(CategoryType::from($request->string('type')->toString())))
            ->get();

        return CategoryResource::collection($categories)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function store(StoreCategoryRequest $request, StoreCategoryAction $action): JsonResponse
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();
        $category = $action->handle($validated);

        return new CategoryResource($category)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdateCategoryRequest $request, Category $category, UpdateCategoryAction $action): JsonResponse
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();

        return new CategoryResource($action->handle($category, $validated))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(Category $category, DestroyCategoryAction $action): JsonResponse
    {
        $action->handle($category);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
