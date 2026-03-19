<?php

declare(strict_types=1);

use App\Http\Resources\OnboardingResource;
use App\Models\Domain;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

beforeEach(function (): void {
    Event::fake();
});

it('returns the correct structure', function () {
    $tenant = Tenant::factory()->create();
    Domain::factory()->create(['tenant_id' => $tenant->id]);
    User::factory()->create(['tenant_id' => $tenant->id]);

    $resource = new OnboardingResource($tenant->load('domain', 'user'))->toArray(new Request());

    expect($resource)
        ->toHaveKeys(['tenant_id', 'domain', 'user'])
        ->and($resource['user']->toArray(new Request()))
        ->toHaveKeys(['id', 'first_name', 'last_name', 'email']);
});
