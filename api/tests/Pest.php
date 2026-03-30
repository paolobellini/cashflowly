<?php

declare(strict_types=1);

use Tests\TestCase;

const FAKE_USER_ID = '550e8400-e29b-41d4-a716-446655440000';
const FAKE_USER_EMAIL = 'paolo@example.com';

pest()->extend(TestCase::class)
    ->in('Feature', 'Unit');
