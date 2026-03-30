<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Category;
use Illuminate\Support\Facades\Log;

final readonly class UpdateCategoryAction
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(Category $category, array $attributes): Category
    {
        $category->update($attributes);

        Log::info('Category updated', ['category_id' => $category->id]);

        return $category;
    }
}
