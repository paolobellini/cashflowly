<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\DestroyTransactionAction;
use App\Actions\StoreTransactionAction;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
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

    public function destroy(Transaction $transaction, DestroyTransactionAction $action): JsonResponse
    {
        $action->handle($transaction);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
