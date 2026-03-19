<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\ValueObjects\ApiGatewayHeaders;
use App\Models\Tenant;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

final readonly class StoreTenantAction
{
    public function __construct(
        private AttachDomainToTenantAction $attachDomainToTenantAction,
        private StoreUserAction $storeUserAction
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function handle(ApiGatewayHeaders $headers, array $attributes): void
    {
        DB::transaction(function () use ($headers, $attributes): void {
            $tenant = Tenant::query()->create();

            $this->attachDomainToTenantAction->handle($tenant, $attributes['domain']);
            $this->storeUserAction->handle($tenant, $headers, Arr::except($attributes, 'domain'));
        });
    }
}
