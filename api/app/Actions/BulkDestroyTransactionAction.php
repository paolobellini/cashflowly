<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Transaction;
use Illuminate\Support\Facades\Log;

final readonly class BulkDestroyTransactionAction
{
    /**
     * @param  list<string>  $ids
     */
    public function handle(array $ids): int
    {
        /** @var int $deleted */
        $deleted = Transaction::query()->whereIn('id', $ids)->delete();

        Log::info('Transactions bulk deleted', ['count' => $deleted]);

        return $deleted;
    }
}
