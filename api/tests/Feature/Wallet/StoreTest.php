<?php

declare(strict_types=1);

beforeEach(function () {
    $this->initializeTenancy();
});

it('can store a wallet', function () {
    $response = $this->postJson('/wallets');

    $response->assertCreated();
});
