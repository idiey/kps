# Workshop Management System

## Overview

A modern Workshop Management System built with Laravel 12, Vue.js 3, and Inertia.js for managing repair workshops, service centers, and maintenance operations.

## Project Status

**Current Status:** Sprint 0 - Foundation Development (Day 1)

The project has completed comprehensive planning and is now pivoting to a modern full-stack implementation using Laravel, Vue.js, and Inertia.js.

**Latest Updates:**
- ✅ Complete documentation framework established
- ✅ Technical architecture designed (updated for Laravel)
- ✅ Sprint planning completed
- ✅ Modern tech stack selected (Laravel 12 + Vue.js 3 + Inertia.js)
- 🔄 Laravel project structure setup in progress

## Technology Stack

### Backend
- **Framework:** Laravel 12
- **PHP Version:** 8.2+
- **Database:** MySQL 8.0+ / PostgreSQL 14+
- **Authentication:** Laravel Sanctum
- **API:** RESTful API

### Frontend
- **Framework:** Vue.js 3 (Composition API)
- **Bridge:** Inertia.js
- **Build Tool:** Vite
- **UI Framework:** TailwindCSS
- **State Management:** Pinia
- **Form Handling:** Inertia.js forms

### Development Tools
- **Package Manager:** Composer (PHP), NPM/YARN (JS)
- **Code Quality:** PHPStan, ESLint, Prettier
- **Testing:** PHPUnit, Pest, Vitest
- **Version Control:** Git

## Purpose

The Workshop Management System provides:

- **Job Tracking**: Manage repair jobs, service orders, and maintenance tasks
- **Time Tracking**: Record time spent by technicians on various jobs
- **Parts Management**: Track parts and materials used in repairs
- **Customer Management**: Comprehensive customer database and history
- **Invoicing**: Generate invoices based on work performed and parts used
- **Reporting**: Real-time analytics and comprehensive reports
- **Scheduling**: Advanced scheduling with calendar integration
- **Real-time Updates**: WebSocket support for live updates

## Documentation

Comprehensive documentation is available in the `/docs` directory:

- **[Master Index](./docs/master-index.md)** - Central documentation hub
- **[Technical Architecture](./docs/technical-architecture-document.md)** - System design and architecture
- **[Implementation Roadmap](./docs/implementation-roadmap.md)** - Development timeline and milestones
- **[Sprint Planning](./docs/sprint-planning.md)** - Sprint goals and user stories
- **[Sprint 0 Daily Todos](./docs/sprint-0-daily-todos.md)** - Day-by-day task breakdown
- **[Developer Quick Start Guide](./docs/developer-quick-start-guide.md)** - Get started in 30 minutes
- **[Artifact Analysis](./ARTIFACT_ANALYSIS.md)** - Complete artifact catalog

### Quick Links for Developers

- New to the project? Start with the [Developer Quick Start Guide](./docs/developer-quick-start-guide.md)
- Need architecture details? See [Technical Architecture Document](./docs/technical-architecture-document.md)
- Want to contribute? Check [Sprint 0 Daily Todos](./docs/sprint-0-daily-todos.md) for current tasks

## Key Features

### 1. Workshop Job Management
- Create and track repair jobs with real-time status updates
- Assign jobs to technicians with notification system
- Track job status and progress through customizable workflows
- Estimate vs actual time/cost tracking with variance analysis

### 2. Parts and Inventory Integration
- Real-time inventory management
- Barcode/QR code scanning support
- Automatic stock level updates
- Low stock alerts and reorder management
- Parts cost calculation with markup rules

### 3. Time and Labor Tracking
- Clock in/out system for technicians
- Multiple billing rates (regular, overtime, weekend)
- Time-based invoicing with automatic calculation
- Labor cost analysis and reporting

### 4. Customer Management
- Comprehensive customer profiles
- Service history tracking
- Multiple contact management
- Customer portal access (planned)
- Automated communication

### 5. Document Generation
- Professional PDF work orders
- Repair estimates/quotes
- Service completion certificates
- Invoice generation
- Email delivery integration

### 6. Reporting and Analytics
- Real-time dashboard with KPIs
- Workshop activity reports
- Technician performance metrics
- Revenue and cost analysis
- Custom report builder

### 7. Advanced Features
- Role-based access control (RBAC)
- Multi-language support
- Mobile-responsive design
- Real-time notifications
- Audit logging
- API for integrations

## Technical Requirements

### Server Requirements
- **PHP:** 8.2 or higher
- **Composer:** 2.5+
- **Database:** MySQL 8.0+ or PostgreSQL 14+
- **Node.js:** 18+ (for building assets)
- **NPM/Yarn:** Latest stable version

### Recommended Server Setup
- **Web Server:** Nginx or Apache with mod_rewrite
- **Memory:** 512MB minimum, 2GB recommended
- **Storage:** 1GB minimum for application
- **SSL Certificate:** Required for production

## Installation

### Development Installation

1. **Clone the repository:**
   ```bash
   git clone http://127.0.0.1:57733/git/idiey/wshop_man.git
   cd wshop_man
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies:**
   ```bash
   npm install
   ```

4. **Environment setup:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database:**
   Edit `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=workshop_management
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

6. **Run migrations:**
   ```bash
   php artisan migrate --seed
   ```

7. **Build frontend assets:**
   ```bash
   npm run dev
   ```

8. **Start development server:**
   ```bash
   php artisan serve
   ```

9. **Access the application:**
   Open your browser to `http://localhost:8000`

### Production Installation

Detailed production deployment instructions will be provided in the documentation.

## Development Roadmap

**Sprint 0: Foundation (Days 1-10)** - Current
1. ✅ Artifact analysis and planning
2. ✅ Comprehensive documentation
3. ✅ Tech stack selection (Laravel + Vue.js + Inertia.js)
4. 🔄 Laravel project initialization
5. ⏳ Database schema design (Laravel migrations)
6. ⏳ Core models and controllers
7. ⏳ Basic authentication setup

**Sprint 1: Core Features (Days 11-20)**
- Workshop and Job CRUD with Inertia pages
- Job status workflow with real-time updates
- Customer integration
- Technician assignment system
- Notes and documentation

**Sprint 2: Advanced Features (Days 21-30)**
- Time tracking with clock in/out
- Parts inventory management
- Cost calculation engine
- Invoice generation

**Sprint 3: Integration & UI (Days 31-40)**
- Advanced Vue.js components
- Real-time notifications (WebSockets)
- PDF generation
- Email integration
- Calendar integration

**Sprint 4: Reporting & Analytics (Days 41-50)**
- Dashboard with charts (Chart.js/ApexCharts)
- Reports and analytics
- Export functionality (Excel, PDF)
- Custom report builder

**Sprint 5: Release (Days 51-60)**
- Testing and QA (PHPUnit, Pest, Vitest)
- Performance optimization
- Security audit
- Documentation finalization
- Production deployment

See [Implementation Roadmap](./docs/implementation-roadmap.md) for detailed timeline.

## Project Structure

```
wshop_man/
├── app/
│   ├── Http/
│   │   ├── Controllers/      # Application controllers
│   │   ├── Requests/         # Form request validators
│   │   └── Middleware/       # Custom middleware
│   ├── Models/               # Eloquent models
│   ├── Services/             # Business logic services
│   └── Repositories/         # Data access layer
├── database/
│   ├── migrations/           # Database migrations
│   ├── seeders/              # Database seeders
│   └── factories/            # Model factories
├── resources/
│   ├── js/
│   │   ├── Pages/            # Inertia.js pages (Vue components)
│   │   ├── Components/       # Reusable Vue components
│   │   ├── Layouts/          # Layout components
│   │   └── app.js            # Main JavaScript entry
│   ├── css/                  # Stylesheets (TailwindCSS)
│   └── views/                # Blade templates (minimal)
├── routes/
│   ├── web.php               # Web routes
│   ├── api.php               # API routes
│   └── auth.php              # Authentication routes
├── tests/
│   ├── Feature/              # Feature tests
│   └── Unit/                 # Unit tests
├── public/                   # Public assets
├── docs/                     # Documentation
└── README.md                 # This file
```

## Development Workflow

### Running the Application

**Development mode with hot reload:**
```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Vite dev server
npm run dev
```

**Production build:**
```bash
npm run build
```

### Database Management

```bash
# Run migrations
php artisan migrate

# Rollback last migration
php artisan migrate:rollback

# Fresh migration with seed data
php artisan migrate:fresh --seed

# Create new migration
php artisan make:migration create_workshops_table
```

### Code Generation

```bash
# Create model with migration, factory, and seeder
php artisan make:model Workshop -mfs

# Create controller
php artisan make:controller WorkshopController

# Create request validator
php artisan make:request StoreWorkshopRequest

# Create Vue component (manual creation in resources/js)
```

### Testing

```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter WorkshopTest

# Run with coverage
php artisan test --coverage

# Frontend tests
npm run test
```

### Code Quality

```bash
# PHP Code Sniffer
./vendor/bin/phpcs

# PHP Stan
./vendor/bin/phpstan analyse

# ESLint
npm run lint

# Fix code style
npm run lint:fix
```

## API Documentation

API documentation will be available at `/api/documentation` using Laravel Sanctum for authentication.

## Contributing

We welcome contributions! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Coding Standards

- **PHP:** Follow PSR-12 coding standards
- **JavaScript:** Follow Airbnb JavaScript Style Guide
- **Vue.js:** Follow Vue.js Style Guide (Priority A + B)
- **Commits:** Use conventional commits format

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For support and questions:
- **Documentation:** Check the `/docs` directory
- **Issues:** Create a GitHub issue
- **Email:** support@workshopmanagement.com (planned)

## Changelog

### Version 1.0.0-dev (In Development)
- Initial project setup
- Laravel 12 + Vue.js 3 + Inertia.js stack
- Comprehensive documentation
- Database schema design
- Sprint planning complete

## Acknowledgments

- Built with [Laravel](https://laravel.com)
- Powered by [Vue.js](https://vuejs.org)
- Seamless SPA experience with [Inertia.js](https://inertiajs.com)
- Styled with [TailwindCSS](https://tailwindcss.com)

---

**Last Updated**: 2025-12-28
**Version**: 1.0.0-dev
**Status**: Active Development - Sprint 0
