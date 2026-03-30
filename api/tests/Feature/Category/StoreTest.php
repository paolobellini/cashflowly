<?php

declare(strict_types=1);

use App\Models\Category;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    $this->initializeTenancy();
});

it('can store a category', function () {
    Log::spy();

    $attributes = Category::factory()->make()->toArray();

    $response = $this->postJson($this->tenantUrl('/categories'), $attributes, $this->tenantHeaders());

    $response->assertCreated();

    $this->assertDatabaseCount('categories', 1)
        ->assertDatabaseHas('categories', ['name' => $attributes['name']]);

    Log::shouldHaveReceived('info')->once();
});

it('fails when required fields are missing', function () {
    $response = $this->postJson($this->tenantUrl('/categories'), [], $this->tenantHeaders());

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'type', 'icon']);
});
