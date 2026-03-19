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

- **API-only backend** — Laravel 13 serves as a JSON API; the Vue.js frontend is a separate SPA
- **Multi-tenancy** — Stancl/Tenancy with database-per-tenant isolation
- **Database** — PostgreSQL locally, SQLite in-memory for tests
- **Cache** — Redis (predis client)
- **Queue/Session** — database-driven
- **Testing** — Pest PHP v4 (Feature and Unit suites)

## Infrastructure

### Containerization
- Single `docker-compose.yml` at the root orchestrates all services
- **API** — FrankenPHP (dunglas/frankenphp) with Laravel Octane, no Nginx needed
- **PostgreSQL** — persistent volume, health-checked
- **Redis** — ephemeral cache, password-protected, internal-only
- Single root `.env` file is the source of truth for all services

### Multi-tenancy & Authentication
- **Identity Provider** — AWS Cognito handles authentication, issues JWTs with `tenant_id` claim
- **Subdomain-based tenants** — each tenant gets `{slug}.cashflowly.com`
- **Wildcard DNS + SSL** required for `*.cashflowly.com`

### Domains
- `cashflowly.com` — central domain (landing page, login, onboarding)
- `auth.cashflowly.com` — Cognito hosted UI
- `{slug}.cashflowly.com` — tenant-specific frontend + API

### API Gateway
- **AWS HTTP API Gateway** in front of the API — handles JWT validation automatically
- Cognito JWT authorizer configured at the gateway level — invalid tokens never reach the API
- API receives validated claims (user_id, tenant_id) via request headers
- Local development uses a thin middleware to validate JWTs directly (no gateway locally)

### Authentication Flow
1. User signs up/logs in via Cognito on the central domain
2. Cognito issues JWT (`tenant_id: null` for new users, populated for existing)
3. New users → onboarding on central domain (choose workspace slug, create tenant + database)
4. API updates Cognito user with `tenant_id` after tenant creation
5. User redirected to their subdomain; JWT validated by API Gateway on every request

### AWS Target Architecture
- **HTTP API Gateway** — JWT validation, request routing to API
- **ECS/Fargate** — API containers
- **ECR** — Docker image registry
- **RDS** — managed PostgreSQL (replaces local container)
- **ElastiCache** — managed Redis (replaces local container)
- **Cognito** — identity provider
- **CloudFront + S3** — Vue.js frontend serving
- **ACM** — wildcard SSL certificate

## Code Quality Standards

- **PHPStan** — level max, analyzes `app/` only (`phpstan.neon`)
- **Pint** — Laravel preset with `final_class`, `declare_strict_types`, `modifier_keywords` enforced (`pint.json`)
- **Rector** — Laravel-specific upgrade rules for `app/` and `tests/` (`rector.php`)
- All classes should be `final readonly` when possible
- Run `vendor/bin/pint --dirty --format agent` after modifying PHP files

## Additional Guidelines

See `api/CLAUDE.md` for detailed Laravel Boost guidelines covering PHP conventions, Laravel patterns, Pest testing, and Pint formatting rules.
