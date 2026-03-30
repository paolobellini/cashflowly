<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Category;
use Illuminate\Support\Facades\Log;

final readonly class DestroyCategoryAction
{
    public function handle(Category $category): void
    {
        $category->delete();

        Log::info('Category deleted', ['category_id' => $category->id]);
    }
}
