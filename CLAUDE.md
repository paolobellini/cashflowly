# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Cashflowly is a personal finance application with a monorepo structure:
- `api/` — Laravel 13 API backend (PHP 8.3+, PostgreSQL)
- Frontend (Vue.js) — planned, not yet created

## Common Commands

All commands run from the `api/` directory:

```bash
# Full setup (install deps, generate key, run migrations, build frontend)
composer setup

# Development server (API + queue + logs + Vite, all concurrent)
composer dev

# Run all quality checks sequentially (lint → test → type → analyse → rector dry-run)
composer check

# Individual checks
composer lint          # Pint formatter (--parallel)
composer test          # Clear config + run Pest tests
composer type          # Pest type coverage
composer analyse       # PHPStan at max level
composer refactor      # Rector refactoring
composer coverage      # Pest with 90% min coverage

# Run a single test
php artisan test --compact --filter=testName
```

## Architecture

- **API-only backend** — Laravel serves as a JSON API; the Vue.js frontend will be a separate SPA
- **Database** — PostgreSQL locally, SQLite in-memory for tests
- **Queue/Cache/Session** — all database-driven
- **Testing** — Pest PHP v4 (Feature and Unit suites)

## Code Quality Standards

- **PHPStan** — level max, analyzes `app/` only (`phpstan.neon`)
- **Pint** — Laravel preset with `final_class`, `declare_strict_types`, `modifier_keywords` enforced (`pint.json`)
- **Rector** — Laravel-specific upgrade rules for `app/` and `tests/` (`rector.php`)
- All classes should be `final readonly` when possible
- Run `vendor/bin/pint --dirty --format agent` after modifying PHP files

## Additional Guidelines

See `api/CLAUDE.md` for detailed Laravel Boost guidelines covering PHP conventions, Laravel patterns, Pest testing, and Pint formatting rules.
