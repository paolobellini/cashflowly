<?php

declare(strict_types=1);

use App\Models\Tenant;

it('has a domain', function () {
    $tenant = Tenant::factory()->hasDomain()->create();

    expect($tenant->domains()->count())->toBe(1)
        ->and($tenant->domains()->first()->tenant_id)->toBe($tenant->id);
});

it('has a user', function () {
    $tenant = Tenant::factory()->hasUser()->create();

    expect($tenant->user()->count())->toBe(1)
        ->and($tenant->user()->first()->tenant_id)->toBe($tenant->id);
});
