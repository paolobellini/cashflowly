<?php

declare(strict_types=1);

use App\Actions\UpdateCategoryAction;
use App\Models\Category;

beforeEach(function () {
    $this->initializeTenancy();
});

it('updates a category', function () {
    $category = Category::factory()->create(['name' => 'Old Name']);

    resolve(UpdateCategoryAction::class)->handle($category, ['name' => 'New Name']);

    expect($category->fresh()->name)->toBe('New Name');
});
