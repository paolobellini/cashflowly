<?php

declare(strict_types=1);

use App\Http\Requests\UpdateWalletRequest;
use Illuminate\Support\Facades\Validator;

function validUpdateWalletData(array $overrides = []): array
{
    return array_merge([
        'name' => 'Personal',
        'type' => 'checking',
        'initial_balance' => 1000.00,
        'is_default' => false,
        'color' => '#4F46E5',
        'description' => 'My main wallet',
    ], $overrides);
}

it('is authorized', function () {
    expect((new UpdateWalletRequest())->authorize())->toBeTrue();
});

it('passes with valid data', function () {
    $validator = Validator::make(validUpdateWalletData(), (new UpdateWalletRequest())->rules());

    expect($validator->passes())->toBeTrue();
});

it('fails when required fields are missing', function (string $field) {
    $validator = Validator::make(validUpdateWalletData([$field => null]), (new UpdateWalletRequest())->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has($field))->toBeTrue();
})->with(['name', 'type']);

it('does not accept currency', function () {
    $rules = (new UpdateWalletRequest())->rules();

    expect($rules)->not->toHaveKey('currency');
});
