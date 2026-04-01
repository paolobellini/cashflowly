<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\BulkDestroyTransactionAction;
use App\Actions\DestroyTransactionAction;
use App\Actions\StoreTransactionAction;
use App\Actions\UpdateTransactionAction;
use App\Http\Requests\BulkDestroyTransactionRequest;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

final class TransactionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $cacheKey = 'transactions.index.'.md5($request->getQueryString() ?? '');

        $transactions = Cache::tags(['transactions'])->flexible($cacheKey, [300, 600], fn () => Transaction::query()
            ->when($request->has('category_id'), fn (Builder $query) => $query->ofCategory($request->string('category_id')->toString()))
            ->when($request->has('month'), fn (Builder $query) => $query->ofMonth((int) $request->integer('month')))
            ->when($request->has('year'), fn (Builder $query) => $query->ofYear((int) $request->integer('year')))
            ->when($request->has('date'), fn (Builder $query) => $query->ofDate($request->string('date')->toString()))
            ->latest('date')
            ->paginate(25));

        return TransactionResource::collection($transactions)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function show(Transaction $transaction): JsonResponse
    {
        return new TransactionResource($transaction)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function store(StoreTransactionRequest $request, StoreTransactionAction $action): JsonResponse
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();
        $transaction = $action->handle($validated);

        return new TransactionResource($transaction)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction, UpdateTransactionAction $action): JsonResponse
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();
        $transaction = $action->handle($transaction, $validated);

        return new TransactionResource($transaction)
            ->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(Transaction $transaction, DestroyTransactionAction $action): JsonResponse
    {
        $action->handle($transaction);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function bulkDestroy(BulkDestroyTransactionRequest $request, BulkDestroyTransactionAction $action): JsonResponse
    {
        /** @var list<string> $ids */
        $ids = $request->validated('ids');
        $action->handle($ids);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
