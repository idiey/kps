# Configuration Guide

Configure Workshop Management System for your environment.

## Environment Configuration

### .env File Structure

```env
# Application
APP_NAME="Workshop Management"
APP_ENV=local
APP_KEY=base64:generated_key
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=workshop_management
DB_USERNAME=root
DB_PASSWORD=

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@workshop.test"
MAIL_FROM_NAME="${APP_NAME}"
```

## Database Configuration

### MySQL

```bash
# Create database
mysql -u root -p
CREATE DATABASE workshop_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'workshop_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON workshop_management.* TO 'workshop_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

Update `.env`:

```env
DB_CONNECTION=mysql
DB_DATABASE=workshop_management
DB_USERNAME=workshop_user
DB_PASSWORD=secure_password
```

### PostgreSQL

```bash
# Create database
sudo -u postgres psql
CREATE DATABASE workshop_management;
CREATE USER workshop_user WITH PASSWORD 'secure_password';
GRANT ALL PRIVILEGES ON DATABASE workshop_management TO workshop_user;
\q
```

Update `.env`:

```env
DB_CONNECTION=pgsql
DB_DATABASE=workshop_management
DB_USERNAME=workshop_user
DB_PASSWORD=secure_password
```

## Cache Configuration

### Redis Setup

**Install Redis:**

```bash
# Ubuntu/Debian
sudo apt-get install redis-server

# macOS
brew install redis

# Start Redis
redis-server
```

**Configure Laravel:**

```env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

**Alternative (File/Database):**

```env
CACHE_DRIVER=file
SESSION_DRIVER=database
QUEUE_CONNECTION=database
```

## Mail Configuration

### Development (Mailtrap)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
```

### Production (SendGrid)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_api_key
```

## File Storage

### Local Storage

```bash
# Create symbolic link
php artisan storage:link
```

```env
FILESYSTEM_DISK=local
```

### S3 Storage

```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your_bucket
```

## Application Settings

### Debug Mode

**Development:**

```env
APP_DEBUG=true
APP_ENV=local
```

**Production:**

```env
APP_DEBUG=false
APP_ENV=production
```

### Timezone

```env
APP_TIMEZONE=UTC
# or
APP_TIMEZONE=America/New_York
```

### Locale

```env
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
```

## Security Configuration

### Generate Application Key

```bash
php artisan key:generate
```

### CORS Configuration

Edit `config/cors.php`:

```php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['http://localhost:3000'],
    'allowed_headers' => ['*'],
    'max_age' => 0,
    'supports_credentials' => true,
];
```

## Performance Configuration

### Optimization Commands

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

### Queue Workers

```bash
# Start queue worker
php artisan queue:work

# With supervisor (production)
php artisan queue:work --daemon
```

## Vite Configuration

### vite.config.js

```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
```

## Environment-Specific Settings

### Development

```env
APP_ENV=local
APP_DEBUG=true
LOG_LEVEL=debug
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
```

### Staging

```env
APP_ENV=staging
APP_DEBUG=true
LOG_LEVEL=info
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Production

```env
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=error
CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
```

## Verify Configuration

```bash
# Check configuration
php artisan config:show

# Test database connection
php artisan migrate:status

# Test cache
php artisan cache:clear
php artisan cache:set test_key test_value
php artisan cache:get test_key

# Test queue
php artisan queue:work --once
```

## Troubleshooting

### Clear All Caches

```bash
php artisan optimize:clear
```

### Reset Configuration

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### Permission Issues

```bash
# Fix permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## Next Steps

- [Developer Guide](../03-development/01-developer-guide.md)
- [Testing Guide](../03-development/03-testing.md)
- [Deployment](../05-deployment/01-production-deployment.md)

---

**Related**:
- [Quick Start](01-quick-start.md)
- [Installation](02-installation.md)
- [Architecture](../02-architecture/README.md)
