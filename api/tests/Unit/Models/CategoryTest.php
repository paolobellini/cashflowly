<?php

declare(strict_types=1);

use App\Models\Category;

beforeEach(function () {
    $this->initializeTenancy();
});

it('uses the correct keys', function () {
    $category = Category::factory()->create()->fresh();

    expect(array_keys($category->toArray()))->toBe([
        'id',
        'name',
        'type',
        'icon',
        'color',
        'created_at',
        'updated_at',
    ]);
});
