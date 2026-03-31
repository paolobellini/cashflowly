<?php

declare(strict_types=1);

use App\Enums\CategoryType;
use App\Models\Category;
use App\Models\Transaction;

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

it('can filter by type', function () {
    Category::factory()->create(['type' => CategoryType::Income]);
    Category::factory()->create(['type' => CategoryType::Expense]);

    expect(Category::query()->ofType(CategoryType::Income)->count())->toBe(1);
});

it('has many transactions', function () {
    $category = Category::factory()->create();

    Transaction::factory()->for($category)->count(3)->create();

    expect($category->transactions->count())->toBe(3);
});
