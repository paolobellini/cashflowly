<?php

declare(strict_types=1);

use App\Http\Requests\StoreCategoryRequest;
use Illuminate\Support\Facades\Validator;

function validCategoryData(array $overrides = []): array
{
    return array_merge([
        'name' => 'Food & Dining',
        'type' => 'expense',
        'icon' => 'utensils',
        'color' => '#4F46E5',
    ], $overrides);
}

it('is authorized', function () {
    expect((new StoreCategoryRequest())->authorize())->toBeTrue();
});

it('passes with valid data', function () {
    $validator = Validator::make(validCategoryData(), (new StoreCategoryRequest())->rules());

    expect($validator->passes())->toBeTrue();
});

it('fails when required fields are missing', function (string $field) {
    $validator = Validator::make(validCategoryData([$field => null]), (new StoreCategoryRequest())->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has($field))->toBeTrue();
})->with(['name', 'type', 'icon']);
