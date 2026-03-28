<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class WalletController extends Controller
{
    public function store(): JsonResponse
    {
        return response()->json([], Response::HTTP_CREATED);
    }
}
