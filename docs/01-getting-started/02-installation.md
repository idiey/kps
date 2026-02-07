# Installation Guide

> **Goal**: Set up the Workshop Management System locally for development
> **Audience**: Developers and technical contributors
> **Last Updated**: 2026-02-07

## Prerequisites

- PHP 8.2+
- Composer 2.x
- Node.js 18+ (npm)
- MySQL 8.0+ or PostgreSQL 14+
- Git

## 1. Clone the Repository

```bash
git clone http://127.0.0.1:57733/git/idiey/wshop_man.git
cd wshop_man
```

## 2. Install Dependencies

```bash
composer install
npm install
```

## 3. Environment Setup

```bash
cp .env.example .env
php artisan key:generate
```

Update database settings in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=workshop_management
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## 4. Run Migrations and Seed Data

```bash
php artisan migrate --seed
```

The default seeder creates demo accounts:

- `admin@workshop.gov.my` / `password` (Pentadbiran)
- `supervisor@workshop.gov.my` / `password` (Penyelia)
- `inspector@workshop.gov.my` / `password` (Pemeriksa)
- `technician@workshop.gov.my` / `password` (Juruteknik)
- `frontdesk@workshop.gov.my` / `password` (Kaunter)

If you want a clean database with no demo data:

```bash
php artisan migrate
```

## 5. Storage Symlink (for uploads)

```bash
php artisan storage:link
```

## 6. Run the Application

### Option A: Single command (recommended)

```bash
composer run dev
```

This starts:
- Laravel server
- Queue listener
- Vite dev server

### Option B: Separate terminals

```bash
php artisan serve
npm run dev
```

## Optional: SSR Development

```bash
composer run dev:ssr
```

## Verify

```bash
php artisan route:list
php artisan test
```

## Troubleshooting

### Missing APP_KEY

```bash
php artisan key:generate
```

### Permissions Issues

```bash
# Linux/macOS
chmod -R 775 storage bootstrap/cache
```

### Database Errors

- Ensure the database exists and credentials are correct
- Re-run `php artisan migrate --seed`

## Next Steps

- [Quick Start](01-quick-start.md)
- [Configuration](03-configuration.md)
- [Development](../03-development/README.md)
