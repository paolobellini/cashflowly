<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\ValueObjects\ApiGatewayHeaders;
use App\Models\Tenant;
use App\Models\User;
use Closure;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class CheckTenantHeader
{
    public function __construct(private readonly ResponseFactory $responseFactory) {}

    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $headers = ApiGatewayHeaders::fromRequest($request);

        return $this->ensureHeadersArePresent($headers)
            ?? $this->ensureTenantExists($headers->tenantId ?? '')
            ?? $this->ensureUserBelongsToTenant($headers->userId, $headers->tenantId ?? '')
            ?? $next($request);
    }

    private function ensureHeadersArePresent(ApiGatewayHeaders $headers): ?JsonResponse
    {
        if ($headers->userId === '' || $headers->tenantId === null || $headers->tenantId === '') {
            return $this->responseFactory->json(['message' => 'Missing required tenant headers.'], Response::HTTP_FORBIDDEN);
        }

        return null;
    }

    private function ensureTenantExists(string $tenantId): ?JsonResponse
    {
        if (! Tenant::query()->find($tenantId)) {
            return $this->responseFactory->json(['message' => 'Tenant not found.'], Response::HTTP_NOT_FOUND);
        }

        return null;
    }

    private function ensureUserBelongsToTenant(string $userId, string $tenantId): ?JsonResponse
    {
        if (! User::query()->where('id', $userId)->where('tenant_id', $tenantId)->exists()) {
            return $this->responseFactory->json(['message' => 'User not found for this tenant.'], Response::HTTP_FORBIDDEN);
        }

        return null;
    }
}
