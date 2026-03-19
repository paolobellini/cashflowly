<?php

declare(strict_types=1);

use App\Http\Middleware\CheckTenantHeader;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function (): void {
    Event::fake();
});

function createRequest(?string $userId = null, ?string $tenantId = null): Response
{
    $request = Request::create('/test', 'GET');

    if ($userId !== null) {
        $request->headers->set('X-User-Id', $userId);
    }

    if ($tenantId !== null) {
        $request->headers->set('X-Tenant-Id', $tenantId);
    }

    return resolve(CheckTenantHeader::class)->handle($request, fn () => new Illuminate\Http\Response('OK'));
}

it('can validate the request', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create(['tenant_id' => $tenant->id]);

    $response = createRequest((string) $user->id, $tenant->id);

    expect($response->getStatusCode())->toBe(Response::HTTP_OK);
});

it('can reject the request if no tenant_id is provided', function () {
    $response = createRequest(userId: 'some-user-id');

    expect($response->getStatusCode())->toBe(Response::HTTP_FORBIDDEN);
});

it('can reject the request if no user_id is provided', function () {
    $response = createRequest(tenantId: 'some-tenant-id');

    expect($response->getStatusCode())->toBe(Response::HTTP_FORBIDDEN);
});

it('can reject the request if tenant does not exist', function () {
    $response = createRequest('some-user-id', 'non-existent-tenant-id');

    expect($response->getStatusCode())->toBe(Response::HTTP_NOT_FOUND);
});

it('can reject the request if the tenant doesn\'t belong to the user', function () {
    $tenant = Tenant::factory()->create();
    $user = User::factory()->create();

    $response = createRequest((string) $user->id, $tenant->id);

    expect($response->getStatusCode())->toBe(Response::HTTP_FORBIDDEN);
});
