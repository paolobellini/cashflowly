<?php

declare(strict_types=1);

use App\Models\Category;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    $this->initializeTenancy();
});

it('can update a category', function () {
    Log::spy();

    $category = Category::factory()->create(['name' => 'Old Name']);

    $response = $this->putJson($this->tenantUrl("/categories/{$category->id}"), [
        'name' => 'New Name',
        'type' => 'income',
        'icon' => 'briefcase',
        'color' => '#FF5733',
    ], $this->tenantHeaders());

    $response->assertOk();

    $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'New Name']);

    Log::shouldHaveReceived('info')->once();
});

it('fails when required fields are missing', function () {
    $category = Category::factory()->create();

    $response = $this->putJson($this->tenantUrl("/categories/{$category->id}"), [], $this->tenantHeaders());

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'type', 'icon']);
});
