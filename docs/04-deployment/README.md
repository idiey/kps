# Deployment

## Overview

Deployment for KPS is the standard Laravel production flow with the additional requirement that the KPS seeders and permissions stay in sync with the live roles.

## Production Checklist

- Set the production `.env` values for the app name, URL, database, mail, and cache/session drivers.
- Run `composer install --no-dev --optimize-autoloader`.
- Run `npm ci` followed by `npm run build`.
- Run `php artisan migrate --force`.
- Run `php artisan db:seed --force` only when you need to refresh seed data or bootstrap a new environment.
- Run `php artisan storage:link` if file uploads are enabled in the deployment.
- Clear and rebuild caches with `php artisan optimize`.

## Release Flow

1. Pull the target branch.
2. Install dependencies.
3. Build frontend assets.
4. Run migrations.
5. Validate the app with smoke checks on dashboard, site pages, and reports.
6. Promote the release only after the KPS permissions and role assignments look correct.

## Rollback Notes

- Keep a database backup before each migration batch.
- If a migration causes a problem, restore the backup before re-running the app.
- If a seed change is needed, update the KPS seeders first and then re-run them in a controlled environment.

## Operational Checks

- Confirm the site context middleware resolves a valid site for operational users.
- Confirm the KPS roles still match the admin navigation and permission matrix.
- Confirm the front-end build completes without errors.

## Related Links

- [Development](../03-development/README.md)
- [User Guide](../05-user-guide/README.md)
