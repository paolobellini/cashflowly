<?php

declare(strict_types=1);

use App\Actions\AttachDomainToTenantAction;
use App\Models\Domain;
use App\Models\Tenant;
use Illuminate\Support\Facades\Event;

beforeEach(function (): void {
    Event::fake();
});

it('attaches a domain to a tenant', function () {
    $tenant = Tenant::factory()->create();

    resolve(AttachDomainToTenantAction::class)->handle($tenant, 'paolo-finance');

    expect(Domain::query()->where('tenant_id', $tenant->id)->where('domain', 'paolo-finance')->exists())->toBeTrue();
});
