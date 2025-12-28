# Quick Start

Get Workshop Management System running in 5 minutes.

## Prerequisites

- PHP 8.2+, Composer, Node.js 18+, MySQL/PostgreSQL
- Git installed

## Installation Steps

### 1. Clone Repository

```bash
git clone http://127.0.0.1:57733/git/idiey/wshop_man.git
cd wshop_man
```

### 2. Install Dependencies

```bash
# PHP dependencies
composer install

# JavaScript dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Configure Database

Edit `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=workshop_management
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations

```bash
# Create database tables
php artisan migrate

# Seed with demo data (optional)
php artisan db:seed
```

### 6. Build Frontend

```bash
# Development build with hot reload
npm run dev
```

### 7. Start Server

```bash
# In a new terminal
php artisan serve
```

### 8. Access Application

Open browser to: `http://localhost:8000`

**Default credentials** (if seeded):

- Email: `admin@example.com`
- Password: `password`

## Verify Installation

Check everything is working:

```bash
# Run tests
php artisan test

# Check routes
php artisan route:list
```

## Common Issues

### Database Connection Error

- Verify MySQL/PostgreSQL is running
- Check database credentials in `.env`
- Ensure database exists: `CREATE DATABASE workshop_management;`

### Node Modules Error

```bash
# Clear and reinstall
rm -rf node_modules package-lock.json
npm install
```

### Permission Issues

```bash
# Fix storage permissions
chmod -R 775 storage bootstrap/cache
```

## Next Steps

- [Installation Guide](02-installation.md) - Detailed setup
- [Configuration](03-configuration.md) - Advanced configuration
- [Developer Guide](../03-development/01-developer-guide.md) - Start developing

## Development Workflow

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server (hot reload)
npm run dev

# Terminal 3: Queue worker (if needed)
php artisan queue:work
```

---

**Time to complete**: ~5 minutes
**Difficulty**: Beginner
**Next**: [Full Installation Guide →](02-installation.md)
