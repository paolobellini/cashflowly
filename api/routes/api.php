<?php

declare(strict_types=1);

use App\Http\Controllers\OnboardingController;
use Illuminate\Support\Facades\Route;

Route::post('/onboarding', [OnboardingController::class, 'onboarding']);
