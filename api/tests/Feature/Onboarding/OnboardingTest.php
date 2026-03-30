<?php

declare(strict_types=1);

use App\Models\Domain;
use App\Models\Tenant;

it('can create a new tenant with proper domain', function () {
    $response = $this->postJson('/api/onboarding', [
        'first_name' => 'Paolo',
        'last_name' => 'Rossi',
        'company_name' => null,
        'domain' => 'paolo-finance',
    ], [
        'X-User-Id' => FAKE_USER_ID,
        'X-User-Email' => FAKE_USER_EMAIL,
    ]);

    $response->assertCreated()
        ->assertJsonStructure([
            'data' => [
                'tenant_id',
                'domain',
                'user' => ['id', 'first_name', 'last_name', 'email'],
            ],
        ])
        ->assertJsonPath('data.domain', 'paolo-finance')
        ->assertJsonPath('data.user.id', FAKE_USER_ID)
        ->assertJsonPath('data.user.email', FAKE_USER_EMAIL);
});

it('fails when required fields are missing', function () {
    $response = $this->postJson('/api/onboarding', [], [
        'X-User-Id' => FAKE_USER_ID,
        'X-User-Email' => FAKE_USER_EMAIL,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['first_name', 'last_name', 'domain']);
});

it('fails when domain is already taken', function () {
    $tenant = Tenant::factory()->create();
    Domain::query()->create(['domain' => 'taken-domain', 'tenant_id' => $tenant->id]);

    $response = $this->postJson('/api/onboarding', [
        'first_name' => 'Paolo',
        'last_name' => 'Rossi',
        'company_name' => null,
        'domain' => 'taken-domain',
    ], [
        'X-User-Id' => FAKE_USER_ID,
        'X-User-Email' => FAKE_USER_EMAIL,
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['domain']);
});

it('slugifies the domain before validation', function () {
    $response = $this->postJson('/api/onboarding', [
        'first_name' => 'Paolo',
        'last_name' => 'Rossi',
        'company_name' => null,
        'domain' => 'My Workspace Name',
    ], [
        'X-User-Id' => FAKE_USER_ID,
        'X-User-Email' => FAKE_USER_EMAIL,
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.domain', 'my-workspace-name');
});
