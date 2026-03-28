<?php

declare(strict_types=1);

namespace Tests;

use App\Models\Tenant;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    protected bool $tenancy = false;

    protected Tenant $tenant;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate:fresh');

        if ($this->tenancy) {
            $this->initializeTenancy();
        }
    }

    protected function tearDown(): void
    {
        if ($this->tenancy) {
            tenancy()->end();
            $this->tenant->delete();
        }

        parent::tearDown();
    }

    protected function initializeTenancy(): void
    {
        $this->tenant = Tenant::query()->create();

        tenancy()->initialize($this->tenant);
    }
}
