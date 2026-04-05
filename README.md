# KPS

KPS is a Laravel + Vue application for managing **FELDA peneroka debt and monthly potongan allocation** across multiple sites.

## What It Does

- Manages KPS sites with HQ oversight
- Tracks peneroka records and debt balances
- Captures monthly potongan entries, including bulk entry
- Applies a deterministic allocation waterfall
- Supports allocation review, audit logging, and month closing
- Separates HQ navigation from site-scoped navigation

## Stack

- Laravel 12
- Vue 3
- Inertia.js
- Vite
- MySQL / PostgreSQL
- Spatie Laravel Permission

## Main Routes

- `/kps/dashboard` for HQ overview
- `/kps/analytics` for HQ analytics
- `/kps/sites` for site management
- `/kps/sites/{site}` for site dashboards
- `/kps/sites/{site}/peneroka` for peneroka management
- `/kps/sites/{site}/hutang` for debt management
- `/kps/sites/{site}/potongan` for monthly deductions
- `/kps/sites/{site}/allocations` for allocation review
- `/kps/sites/{site}/reports` for statements and summaries

## Local Setup

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev
php artisan serve
```

## Documentation

Start with:

- [docs/README.md](C:/Users/zuraidiismail/RnD/kps/docs/README.md)
- [docs/00-control-center/01-project-brief.md](C:/Users/zuraidiismail/RnD/kps/docs/00-control-center/01-project-brief.md)
- [docs/02-architecture/01-prd.md](C:/Users/zuraidiismail/RnD/kps/docs/02-architecture/01-prd.md)
- [docs/02-architecture/02-system-design.md](C:/Users/zuraidiismail/RnD/kps/docs/02-architecture/02-system-design.md)
