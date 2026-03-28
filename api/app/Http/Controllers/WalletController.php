<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\StoreWalletAction;
use App\Actions\UpdateWalletAction;
use App\Http\Requests\StoreWalletRequest;
use App\Http\Requests\UpdateWalletRequest;
use App\Http\Resources\WalletResource;
use App\Models\Wallet;
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

    public function update(UpdateWalletRequest $request, string $wallet, UpdateWalletAction $action): JsonResponse
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();

        /** @var Wallet $walletModel */
        $walletModel = Wallet::query()->findOrFail($wallet);

        return new WalletResource($action->handle($walletModel, $validated))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }
}
