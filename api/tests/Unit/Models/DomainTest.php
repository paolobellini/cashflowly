<?php

declare(strict_types=1);

use App\Models\Domain;

it('belog to tenant', function () {
    $domain = Domain::factory()->hasTenant()->create();

    expect($domain->tenant()->count())->toBe(1)
        ->and($domain->tenant()->first()->id)->toBe($domain->tenant_id);
});
