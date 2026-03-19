<?php

declare(strict_types=1);

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

beforeEach(function (): void {
    Event::fake();
});

it('returns the correct structure', function () {
    $user = User::factory()->create();

    $resource = new UserResource($user)->toArray(new Request());

    expect($resource)
        ->toHaveKeys(['id', 'first_name', 'last_name', 'email'])
        ->not->toHaveKey('company_name')
        ->not->toHaveKey('tenant_id');
});
