<?php

declare(strict_types=1);

use App\Actions\StoreTenantAction;
use App\Http\ValueObjects\ApiGatewayHeaders;
use App\Models\Domain;
use App\Models\Tenant;
use App\Models\User;

it('creates a tenant with domain and user', function () {
    $headers = new ApiGatewayHeaders(userId: FAKE_USER_ID, userEmail: FAKE_USER_EMAIL);

    resolve(StoreTenantAction::class)->handle($headers, [
        'first_name' => 'Paolo',
        'last_name' => 'Rossi',
        'company_name' => null,
        'domain' => 'paolo-finance',
    ]);

    $tenant = Tenant::query()->first();

    expect($tenant)->not->toBeNull()
        ->and(Domain::query()->where('tenant_id', $tenant->id)->where('domain', 'paolo-finance')->exists())->toBeTrue()
        ->and(User::query()->where('id', FAKE_USER_ID)->where('tenant_id', $tenant->id)->exists())->toBeTrue();
});

it('rolls back everything if user creation fails', function () {
    $headers = new ApiGatewayHeaders(userId: FAKE_USER_ID, userEmail: FAKE_USER_EMAIL);

    $existingTenant = Tenant::factory()->create();
    User::factory()->create(['id' => FAKE_USER_ID, 'tenant_id' => $existingTenant->id]);

    $tenantCountBefore = Tenant::query()->count();
    $domainCountBefore = Domain::query()->count();

    try {
        resolve(StoreTenantAction::class)->handle($headers, [
            'first_name' => 'Paolo',
            'last_name' => 'Rossi',
            'company_name' => null,
            'domain' => 'paolo-finance',
        ]);
    } catch (Throwable) {
    }

    expect(Tenant::query()->count())->toBe($tenantCountBefore)
        ->and(Domain::query()->count())->toBe($domainCountBefore);
});
