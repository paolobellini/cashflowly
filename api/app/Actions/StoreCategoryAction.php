<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Category;
use Illuminate\Support\Facades\Log;

final readonly class StoreCategoryAction
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(array $attributes): Category
    {
        $category = Category::query()->create($attributes);

        Log::info('Category created', ['category_id' => $category->id]);

        return $category;
    }
}
