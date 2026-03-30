<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\DestroyWalletAction;
use App\Actions\StoreWalletAction;
use App\Actions\UpdateWalletAction;
use App\Http\Requests\StoreWalletRequest;
use App\Http\Requests\UpdateWalletRequest;
use App\Http\Resources\WalletResource;
use App\Models\Wallet;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

final class WalletController extends Controller
{
    public function index(): JsonResponse
    {
        $wallets = Cache::tags(['wallets'])->remember('wallets.index', now()->addDay(), fn () => Wallet::all());

        return WalletResource::collection($wallets)->response()->setStatusCode(Response::HTTP_OK);
    }

    public function store(StoreWalletRequest $request, StoreWalletAction $action): JsonResponse
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();
        $wallet = $action->handle($validated);

        return new WalletResource($wallet)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdateWalletRequest $request, Wallet $wallet, UpdateWalletAction $action): JsonResponse
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();

        return new WalletResource($action->handle($wallet, $validated))
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(Wallet $wallet, DestroyWalletAction $action): JsonResponse
    {
        $action->handle($wallet);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
