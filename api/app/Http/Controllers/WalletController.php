<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\StoreWalletAction;
use App\Http\Requests\StoreWalletRequest;
use App\Http\Resources\WalletResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class WalletController extends Controller
{
    public function store(StoreWalletRequest $request, StoreWalletAction $action): JsonResponse
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();
        $wallet = $action->handle($validated);

        return new WalletResource($wallet)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
