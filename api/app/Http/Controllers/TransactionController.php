<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\StoreTransactionAction;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Resources\TransactionResource;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class TransactionController extends Controller
{
    public function store(StoreTransactionRequest $request, StoreTransactionAction $action): JsonResponse
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();
        $transaction = $action->handle($validated);

        return new TransactionResource($transaction)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
