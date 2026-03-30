<?php

declare(strict_types=1);

use App\Actions\DestroyCategoryAction;
use App\Models\Category;

beforeEach(function () {
    $this->initializeTenancy();
});

it('deletes a category', function () {
    $category = Category::factory()->create();

    resolve(DestroyCategoryAction::class)->handle($category);

    expect(Category::query()->count())->toBe(0);
});
