<?php

declare(strict_types=1);

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

beforeEach(function () {
    $this->initializeTenancy();
});

it('returns the correct structure', function () {
    $category = Category::factory()->create();

    $resource = new CategoryResource($category)->toArray(new Request());

    expect($resource)
        ->toHaveKeys(['id', 'name', 'type', 'icon', 'color'])
        ->not->toHaveKey('created_at')
        ->not->toHaveKey('updated_at');
});
