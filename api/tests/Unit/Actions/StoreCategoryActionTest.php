<?php

declare(strict_types=1);

use App\Actions\StoreCategoryAction;
use App\Models\Category;

beforeEach(function () {
    $this->initializeTenancy();
});

it('creates a category', function () {
    $attributes = Category::factory()->make()->toArray();

    $category = resolve(StoreCategoryAction::class)->handle($attributes);

    expect(Category::query()->count())->toBe(1)
        ->and($category->id)->toBe(Category::query()->first()->id);
});
