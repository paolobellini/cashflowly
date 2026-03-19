<?php

declare(strict_types=1);

use App\Actions\StoreUserAction;
use App\Http\ValueObjects\ApiGatewayHeaders;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Event;

beforeEach(function (): void {
    Event::fake();
});

it('creates a user for a tenant', function () {
    $tenant = Tenant::factory()->create();
    $headers = new ApiGatewayHeaders(userId: 'cognito-uuid', userEmail: 'paolo@example.com');

    resolve(StoreUserAction::class)->handle($tenant, $headers, [
        'first_name' => 'Paolo',
        'last_name' => 'Rossi',
        'company_name' => 'Acme Corp',
    ]);

    $user = User::query()->where('id', 'cognito-uuid')->first();

    expect($user)->not->toBeNull()
        ->and($user->tenant_id)->toBe($tenant->id)
        ->and($user->email)->toBe('paolo@example.com')
        ->and($user->first_name)->toBe('Paolo')
        ->and($user->last_name)->toBe('Rossi')
        ->and($user->company_name)->toBe('Acme Corp');
});
