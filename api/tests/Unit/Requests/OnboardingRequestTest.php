<?php

declare(strict_types=1);

use App\Http\Requests\OnboardingRequest;
use Illuminate\Support\Facades\Validator;

function validData(array $overrides = []): array
{
    return array_merge([
        'name' => 'Paolo',
        'surname' => 'Rossi',
        'company_name' => null,
        'domain' => 'paolo-finance',
    ], $overrides);
}

it('is authorized', function () {
    expect((new OnboardingRequest())->authorize())->toBeTrue();
});

it('passes with valid data', function () {
    $validator = Validator::make(validData(), (new OnboardingRequest())->rules());

    expect($validator->passes())->toBeTrue();
});

it('passes with a company name', function () {
    $validator = Validator::make(validData(['company_name' => 'Acme Corp']), (new OnboardingRequest())->rules());

    expect($validator->passes())->toBeTrue();
});

it('fails when required fields are missing', function (string $field) {
    $validator = Validator::make(validData([$field => null]), (new OnboardingRequest())->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has($field))->toBeTrue();
})->with(['name', 'surname', 'domain']);

it('fails when domain has invalid format', function (string $domain) {
    $validator = Validator::make(validData(['domain' => $domain]), (new OnboardingRequest())->rules());

    expect($validator->fails())->toBeTrue()
        ->and($validator->errors()->has('domain'))->toBeTrue();
})->with(['UPPER', 'has spaces', 'special!chars', '-leading-dash', 'trailing-dash-']);
