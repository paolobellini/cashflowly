<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Recurrence;
use Illuminate\Support\Facades\Cache;

final class RecurrenceObserver
{
    public function created(Recurrence $recurrence): void
    {
        Cache::tags(['recurrences'])->flush();
    }

    public function updated(Recurrence $recurrence): void
    {
        Cache::tags(['recurrences'])->flush();
    }

    public function deleted(Recurrence $recurrence): void
    {
        Cache::tags(['recurrences'])->flush();
    }
}
