<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Recurrence;
use Illuminate\Support\Facades\Log;

final readonly class DestroyRecurrenceAction
{
    public function handle(Recurrence $recurrence): void
    {
        $recurrence->delete();

        Log::info('Recurrence deleted', ['recurrence_id' => $recurrence->id]);
    }
}
