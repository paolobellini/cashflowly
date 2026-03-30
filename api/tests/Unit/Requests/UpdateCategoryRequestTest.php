<?php

declare(strict_types=1);

use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Facades\Validator;

it('is authorized', function () {
    expect((new UpdateCategoryRequest())->authorize())->toBeTrue();
});

it('passes with valid data', function () {
    $validator = Validator::make(validCategoryData(), (new UpdateCategoryRequest())->rules());

    expect($validator->passes())->toBeTrue();
});

it('fails when required fields are missing', function (string $field) {
    $validator = Validator::make(validCategoryData([$field => null]), (new UpdateCategoryRequest())->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has($field))->toBeTrue();
})->with(['name', 'type', 'icon']);
