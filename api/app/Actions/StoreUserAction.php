<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\ValueObjects\ApiGatewayHeaders;
use App\Models\Tenant;

final readonly class StoreUserAction
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(Tenant $tenant, ApiGatewayHeaders $headers, array $attributes): void
    {
        $tenant->user()->create([
            'id' => $headers->userId,
            'email' => $headers->userEmail,
            'first_name' => $attributes['first_name'],
            'last_name' => $attributes['last_name'],
            'company_name' => $attributes['company_name'],
        ]);
    }
}
