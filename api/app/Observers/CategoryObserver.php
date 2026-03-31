<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

final class CategoryObserver
{
    public function created(Category $category): void
    {
        Cache::tags(['categories'])->flush();
    }

    public function updated(Category $category): void
    {
        Cache::tags(['categories'])->flush();
    }

    public function deleted(Category $category): void
    {
        Cache::tags(['categories'])->flush();
    }
}
