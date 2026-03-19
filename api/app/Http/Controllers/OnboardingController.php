<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\StoreTenantAction;
use App\Http\Requests\OnboardingRequest;
use App\Http\Resources\OnboardingResource;
use App\Http\ValueObjects\ApiGatewayHeaders;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class OnboardingController extends Controller
{
    public function onboarding(OnboardingRequest $request, StoreTenantAction $action): JsonResponse
    {
        /** @var array<string, string> $validated */
        $validated = $request->validated();
        $tenant = $action->handle(ApiGatewayHeaders::fromRequest($request), $validated);

        return new OnboardingResource($tenant->load('domain', 'user'))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
