<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Models\User;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class CheckTenantHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->header('X-User-Id');
        $tenantId = $request->header('X-Tenant-Id');

        return $this->ensureHeadersArePresent($userId, $tenantId)
            ?? $this->ensureTenantExists($tenantId)
            ?? $this->ensureUserBelongsToTenant($userId, $tenantId)
            ?? $next($request);
    }

    private function ensureHeadersArePresent(?string $userId, ?string $tenantId): ?JsonResponse
    {
        if (! $userId || ! $tenantId) {
            return response()->json(['message' => 'Missing required tenant headers.'], Response::HTTP_FORBIDDEN);
        }

        return null;
    }

    private function ensureTenantExists(string $tenantId): ?JsonResponse
    {
        if (! Tenant::find($tenantId)) {
            return response()->json(['message' => 'Tenant not found.'], Response::HTTP_NOT_FOUND);
        }

        return null;
    }

    private function ensureUserBelongsToTenant(string $userId, string $tenantId): ?JsonResponse
    {
        if (! User::where('id', $userId)->where('tenant_id', $tenantId)->exists()) {
            return response()->json(['message' => 'User not found for this tenant.'], Response::HTTP_FORBIDDEN);
        }

        return null;
    }
}
