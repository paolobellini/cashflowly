<?php

declare(strict_types=1);

namespace App\Actions;

use App\Http\ValueObjects\ApiGatewayHeaders;
use App\Models\Tenant;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Throwable;

final readonly class StoreTenantAction
{
    public function __construct(
        private AttachDomainToTenantAction $attachDomainToTenantAction,
        private StoreUserAction $storeUserAction
    ) {}

    /**
     * @param  array<string, string>  $attributes
     *
     * @throws Throwable
     */
    public function handle(ApiGatewayHeaders $headers, array $attributes): Tenant
    {
        /** @var Tenant */
        return DB::transaction(function () use ($headers, $attributes): Tenant {
            $tenant = Tenant::query()->create();

            $this->attachDomainToTenantAction->handle($tenant, $attributes['domain']);

            /** @var array<string, mixed> $userAttributes */
            $userAttributes = Arr::except($attributes, 'domain');
            $this->storeUserAction->handle($tenant, $headers, $userAttributes);

            return $tenant;
        });
    }
}
