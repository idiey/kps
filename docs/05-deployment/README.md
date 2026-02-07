# Deployment

## Overview

Deployment procedures and operational guides for the Workshop Management System.

**Current State**: No automated deployment scripts are included in this repository. The production deployment guide and CI/CD pipeline documentation are planned.

## Table of Contents

### Current Documentation

- [Implementation Roadmap (Legacy)](01-implementation-roadmap.md) - Historical roadmap from the Dolibarr phase

### Planned Documentation

- `02-production-deployment.md` - Step-by-step production guide
- `03-cicd-pipeline.md` - GitHub Actions or CI setup
- `04-server-setup.md` - Nginx/Apache, PHP-FPM, database
- `05-monitoring.md` - Monitoring, logging, maintenance

## Baseline Production Steps (General)

1. Configure `.env` for production
2. Install dependencies: `composer install --no-dev` and `npm ci`
3. Build assets: `npm run build`
4. Run migrations: `php artisan migrate --force`
5. Cache config/routes/views: `php artisan config:cache`, `php artisan route:cache`, `php artisan view:cache`
6. Ensure queue workers are running

## Related Documentation

- [Getting Started](../01-getting-started/README.md)
- [Architecture](../02-architecture/README.md)
- [Development](../03-development/README.md)

---

**Last Updated**: 2026-02-07
