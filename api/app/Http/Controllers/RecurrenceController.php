<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\DestroyRecurrenceAction;
use App\Models\Recurrence;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class RecurrenceController extends Controller
{
    public function destroy(Recurrence $recurrence, DestroyRecurrenceAction $action): JsonResponse
    {
        $action->handle($recurrence);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
