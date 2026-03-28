<?php

declare(strict_types=1);

use App\Models\Wallet;
use App\Rules\HasDefaultWallet;
use Illuminate\Support\Facades\Validator;

beforeEach(function () {
    $this->initializeTenancy();
});

it('passes when no default wallet exists', function () {
    $validator = Validator::make(['is_default' => true], ['is_default' => ['boolean']]);
    $validator->after(new HasDefaultWallet());

    expect($validator->passes())->toBeTrue();
});

it('fails when a default wallet already exists', function () {
    Wallet::factory()->default()->create();

    $validator = Validator::make(['is_default' => true], ['is_default' => ['boolean']]);
    $validator->after(new HasDefaultWallet());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('is_default'))->toBeTrue();
});

it('passes when is_default is false', function () {
    Wallet::factory()->default()->create();

    $validator = Validator::make(['is_default' => false], ['is_default' => ['boolean']]);
    $validator->after(new HasDefaultWallet());

    expect($validator->passes())->toBeTrue();
});

it('excludes the current wallet when updating', function () {
    $wallet = Wallet::factory()->default()->create();

    $validator = Validator::make(['is_default' => true], ['is_default' => ['boolean']]);
    $validator->after(new HasDefaultWallet($wallet->id));

    expect($validator->passes())->toBeTrue();
});

it('fails when another default wallet exists during update', function () {
    Wallet::factory()->default()->create();
    $wallet = Wallet::factory()->create();

    $validator = Validator::make(['is_default' => true], ['is_default' => ['boolean']]);
    $validator->after(new HasDefaultWallet($wallet->id));

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('is_default'))->toBeTrue();
});
