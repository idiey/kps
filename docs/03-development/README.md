# Development

## Overview

Developer workflows, coding standards, and common tasks for the Workshop Management System.

## Quick Start Commands

### Start Development Servers

```bash
# Recommended (server + queue + Vite)
composer run dev
```

Or run separately:

```bash
php artisan serve
npm run dev
```

### Run Tests

```bash
php artisan test
```

### Lint and Format

```bash
# JS/TS lint
npm run lint

# JS/TS format (resources only)
npm run format

# PHP format
./vendor/bin/pint
```

## Development Environment

### Required Tools

- PHP 8.2+
- Composer 2.x
- Node.js 18+
- MySQL/PostgreSQL
- Git

### Frontend Stack

- Vue 3 + Inertia.js (TypeScript)
- TailwindCSS v4
- Vite

### Backend Stack

- Laravel 12
- Fortify (auth)
- Spatie Laravel-Permission (RBAC)

## Project Structure (Key Paths)

- `routes/web.php` - Web routes (no API routes in this repo)
- `app/Http/Controllers` - Controllers
- `app/Services` - Business logic
- `app/Policies` - Authorization policies
- `resources/js/pages` - Inertia pages
- `resources/js/components` - UI components

## Common Tasks

### Create a New Model + Migration

```bash
php artisan make:model Example -m
```

### Create a Controller

```bash
php artisan make:controller ExampleController
```

### Create a Form Request

```bash
php artisan make:request StoreExampleRequest
```

## Git Workflow

- Create feature branches from `main`
- Use conventional commits
- Open PRs for review

## Related Documentation

- [Getting Started](../01-getting-started/README.md)
- [Architecture](../02-architecture/README.md)
- [Testing](../07-testing/README.md)

---

**Last Updated**: 2026-02-07
