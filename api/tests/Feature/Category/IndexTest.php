<?php

declare(strict_types=1);

use App\Enums\CategoryType;
use App\Models\Category;

beforeEach(function () {
    $this->initializeTenancy();
});

it('can list all categories', function () {
    Category::factory()->count(3)->create();

    $response = $this->getJson($this->tenantUrl('/categories'), $this->tenantHeaders());

    $response->assertOk()
        ->assertJsonCount(3, 'data');
});

it('can filter categories by type', function () {
    Category::factory()->count(2)->create(['type' => CategoryType::Income]);
    Category::factory()->count(3)->create(['type' => CategoryType::Expense]);

    $response = $this->getJson($this->tenantUrl('/categories?type=income'), $this->tenantHeaders());

    $response->assertOk()
        ->assertJsonCount(2, 'data');
});

it('returns an empty list when no categories exist', function () {
    $response = $this->getJson($this->tenantUrl('/categories'), $this->tenantHeaders());

    $response->assertOk()
        ->assertJsonCount(0, 'data');
});
