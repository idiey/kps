# Development

## Overview

This project is a Laravel application with Vue 3 + Inertia pages for the KPS modules. Keep new work inside the existing KPS boundaries:

- Backend controllers under `app/Http/Controllers/Kps`
- Services under `app/Services/Kps`
- Routes in `routes/kps.php`
- Frontend pages in `resources/js/pages/Kps`
- KPS navigation in `resources/js/components/kps`

## Local Setup

1. Install PHP dependencies with `composer install`.
2. Install frontend dependencies with `npm install`.
3. Copy `.env.example` to `.env` and configure database access.
4. Generate the application key with `php artisan key:generate`.
5. Run migrations and seeders with `php artisan migrate --seed`.
6. Start the app with `php artisan serve` and `npm run dev`.

## Daily Workflow

- Use `php artisan test` before committing backend changes.
- Use `npm run build` before shipping frontend changes.
- Keep site-scoped features behind the KPS route prefix and context middleware.
- Update permissions in the KPS seeders when adding or removing access-controlled screens.

## Testing Notes

- Feature tests should cover the main site flows, especially CRUD, allocation, and month closing.
- Unit tests should focus on business rules in the KPS services.
- If you add a new KPS permission, update both the seeder and the UI checks.

## Code Organization

- `app/Models/Kps` holds the KPS domain models.
- `app/Http/Controllers/Kps` handles the operational screens.
- `app/Services/Kps` contains workflow logic that should stay out of controllers.
- `resources/js/pages/Kps` contains the Inertia pages for the product.

## Related Links

- [System Map](../00-control-center/03-system-map.md)
- [Deployment](../04-deployment/README.md)
- [User Guide](../05-user-guide/README.md)
