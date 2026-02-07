# Developer Onboarding Guide

> **Last Updated**: 2026-02-02  
> **Version**: 2.0.0-rework  
> **Audience**: New developers joining the project  

---

## Welcome to the Workshop Management System

This guide will help you set up your development environment and understand the project structure.

## Prerequisites

### Required Software

- **PHP**: 8.2 or higher
- **Composer**: Latest version
- **Node.js**: 18+ LTS
- **npm**: 9+ or **pnpm**: 8+
- **MySQL**: 8.0+ or **PostgreSQL**: 14+
- **Git**: Latest version

### Recommended IDE

- **VS Code** with extensions:
  - Laravel Extension Pack
  - Vue - Official (Volar)
  - Inertia.js snippets
  - PHP Intelephense
  - ESLint
  - Prettier

---

## Project Setup

### 1. Clone the Repository

```bash
git clone https://github.com/your-org/workshop-management-system.git
cd workshop-management-system
```

### 2. Install Backend Dependencies

```bash
composer install
```

### 3. Install Frontend Dependencies

```bash
npm install
# or
pnpm install
```

### 4. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`:

```env
APP_NAME="Workshop Management System"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=workshop_db
DB_USERNAME=root
DB_PASSWORD=

```

### 5. Database Setup

```bash
# Create database
mysql -u root -e "CREATE DATABASE workshop_db;"

# Run migrations
php artisan migrate

# Seed demo data (optional)
php artisan db:seed
```

### 6. Build Frontend Assets

```bash
# Development (with HMR)
npm run dev

# Production build
npm run build
```

### 7. Start Development Server

```bash
# Terminal 1: Laravel backend
php artisan serve

# Terminal 2: Vite dev server (for HMR)
npm run dev
```

Visit: http://localhost:8000

---

## Project Structure

```
workshop/
├── app/
│   ├── Enums/              # Enumerations (JobMode, JobStatus)
│   ├── Http/
│   │   ├── Controllers/    # API and web controllers
│   │   ├── Requests/       # Form request validation
│   │   └── Middleware/     # Custom middleware
│   ├── Models/             # Eloquent models
│   ├── Policies/           # Authorization policies
│   └── Services/           # Business logic services
│
├── database/
│   ├── migrations/         # Database migrations
│   ├── seeders/            # Database seeders
│   └── factories/          # Model factories
│
├── resources/
│   ├── js/
│   │   ├── Pages/          # Inertia.js Vue pages
│   │   ├── Components/     # Vue components
│   │   ├── Layouts/        # Layout components
│   │   └── types/          # TypeScript type definitions
│   ├── css/                # Stylesheets
│   └── views/              # Blade templates (minimal)
│
├── routes/
│   ├── web.php             # Web routes (Inertia)
│   ├── api.php             # API routes (mobile)
│   └── channels.php        # Broadcasting channels
│
├── tests/
│   ├── Feature/            # Feature tests
│   └── Unit/               # Unit tests
│
├── docs/                   # Documentation (you're here!)
│
├── mobile/                 # React Native mobile app
│   ├── src/
│   │   ├── screens/
│   │   ├── components/
│   │   ├── services/
│   │   └── utils/
│   ├── app.json
│   └── package.json
│
└── public/                 # Public assets
```

---

## Development Workflow

### Daily Workflow

1. **Pull latest changes**
   ```bash
   git pull origin main
   composer install
   npm install
   php artisan migrate
   ```

2. **Create feature branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

3. **Make changes**
   - Write code following [coding standards](02-coding-standards.md)
   - Write tests for new features
   - Update documentation

4. **Test locally**
   ```bash
   php artisan test
   npm run test
   ```

5. **Commit and push**
   ```bash
   git add .
   git commit -m "feat: add feature description"
   git push origin feature/your-feature-name
   ```

6. **Create Pull Request**
   - Use PR template
   - Request code review
   - Address feedback

### Running Tests

```bash
# Backend tests (PHPUnit/Pest)
php artisan test

# Frontend tests (Vitest)
npm run test

# With coverage
php artisan test --coverage
npm run test -- --coverage
```

### Code Quality

```bash
# PHP linting (Laravel Pint)
./vendor/bin/pint

# PHP static analysis (PHPStan)
./vendor/bin/phpstan analyse

# JavaScript/Vue linting
npm run lint

# Fix linting issues
npm run lint:fix
```

---

## Key Technologies

### Backend Stack

| Technology | Purpose | Documentation |
|------------|---------|---------------|
| **Laravel 12** | PHP framework | [laravel.com](https://laravel.com/docs) |
| **Inertia.js** | SPA adapter | [inertiajs.com](https://inertiajs.com) |
| **Laravel Sanctum** | API auth | [docs](https://laravel.com/docs/sanctum) |
| **Spatie Permission** | Roles/permissions | [docs](https://spatie.be/docs/laravel-permission) |

### Frontend Stack

| Technology | Purpose | Documentation |
|------------|---------|---------------|
| **Vue.js 3** | UI framework | [vuejs.org](https://vuejs.org) |
| **TypeScript** | Type safety | [typescriptlang.org](https://www.typescriptlang.org) |
| **Tailwind CSS** | Styling | [tailwindcss.com](https://tailwindcss.com) |
| **Vite** | Build tool | [vitejs.dev](https://vitejs.dev) |

### Mobile Stack

| Technology | Purpose | Documentation |
|------------|---------|---------------|
| **React Native** | Mobile framework | [reactnative.dev](https://reactnative.dev) |
| **Expo** | Development platform | [expo.dev](https://expo.dev) |
| **WatermelonDB** | Offline database | [nozbe.github.io/WatermelonDB](https://nozbe.github.io/WatermelonDB) |
| **React Navigation** | Navigation | [reactnavigation.org](https://reactnavigation.org) |

---

## Common Tasks

### Creating a New Migration

```bash
php artisan make:migration create_quotations_table
```

### Creating a Model with Migration

```bash
php artisan make:model Quotation -m
```

### Creating a Controller

```bash
php artisan make:controller QuotationController
```

### Creating an Inertia Page

1. Create Vue file: `resources/js/Pages/Quotations/Index.vue`
2. Add route in `routes/web.php`:
   ```php
   Route::get('/quotations', [QuotationController::class, 'index']);
   ```
3. Return Inertia response:
   ```php
   return Inertia::render('Quotations/Index', [
       'quotations' => $quotations
   ]);
   ```

### Accessing Mobile App

```bash
cd mobile/
npm install
npx expo start
```

Scan QR code with Expo Go app (iOS/Android).

---

## Troubleshooting

### Common Issues

**1. "Class not found" error**
```bash
composer dump-autoload
```

**2. "Mix manifest not found"**
```bash
npm run build
```

**3. "Database connection refused"**
- Check MySQL/PostgreSQL is running
- Verify `.env` database credentials

**4. Vite not hot-reloading**
```bash
# Clear cache and restart
npm run dev
```

**5. Permission denied errors**
```bash
chmod -R 755 storage bootstrap/cache
```

---

## Getting Help

### Documentation

- [Architecture Docs](../02-architecture/README.md)
- [API Documentation](../02-architecture/13-mobile-api-design.md)
- [User Guides](../06-user-guide/README.md)

### Team Communication

- **Slack**: #workshop-dev channel
- **GitHub Issues**: Report bugs and features
- **Daily Standup**: 9:30 AM (virtual/office)

### Code Review Guidelines

1. Keep PRs small (< 400 lines)
2. Write descriptive commit messages
3. Include tests for new features
4. Update documentation
5. Be respectful and constructive

---

## Next Steps

After completing setup:

1. ✅ Read [Coding Standards](02-coding-standards.md)
2. ✅ Review [Architecture Overview](../02-architecture/README.md)
3. ✅ Explore [Sprint Planning](../04-sprints/03-sprint-rework-overview.md)
4. ✅ Pick a "good first issue" from GitHub
5. ✅ Set up mobile development environment ([Mobile Setup](03-mobile-development-setup.md))

---

**Welcome to the team! Happy coding! 🚀**
