# Government Workshop Management System (KEW.PA-10)

## Overview

A comprehensive Workshop Management System built with Laravel 12, Vue.js 3, and Inertia.js specifically designed for **Malaysian government agencies** to manage asset maintenance, repairs, and inspections using the official **KEW.PA-10** procurement form.

This system digitizes and streamlines government workshop operations with full compliance to Malaysian government procurement procedures, audit trails, and bilingual support (English/Bahasa Malaysia).

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

## System Purpose

This system manages two primary workflows for government asset maintenance:

### **Option 1: External KEW.PA-10 Reception**

Receive and process KEW.PA-10 forms submitted by government departments for asset repairs.

**Workflow Steps:**
1. **Admin Officer** receives KEW.PA-10 from government department
2. **Supervisor** assigns job to technician
3. **Inspector** validates asset condition and KEW.PA-10 details
4. **Technician** performs repair work with photo documentation
5. **Supervisor** reviews completed work and updates KEW.PA-10

### **Option 2: Internal Inspection & KEW.PA-10 Generation**

Proactive internal inspections that generate KEW.PA-10 forms for required maintenance.

**Workflow Steps:**
1. **Inspector** conducts scheduled asset inspection
2. **Supervisor** reviews inspection findings
3. **Admin Officer** generates KEW.PA-10 form
4. **Approver** validates and approves work order
5. **Technician** performs repair work with photo documentation

## User Roles

The system supports five government workshop roles:

- 🟡 **Pentadbiran (Admin Officer)** - KEW.PA-10 reception, documentation, system administration
- 🟣 **Penyelia (Supervisor)** - Job assignment, work review, quality control
- 🔵 **Pemeriksa (Inspector)** - Asset inspections, condition validation, compliance checks
- 🔴 **Pelulus (Approver)** - Work order approval, budget authorization
- 🟢 **Juruteknik (Technician)** - Repair execution, photo documentation, work completion

## Key Features

### Government Compliance
- **KEW.PA-10 Form Management** - Digital forms with validation
- **Audit Trail** - Complete activity logging for compliance
- **Digital Signatures** - Secure approval workflow
- **Photo Documentation** - Before/after photos for all repairs
- **Bilingual Support** - English and Bahasa Malaysia interface

### Workshop Operations
- **Job Tracking** - Manage repair jobs with real-time status
- **Time Tracking** - Record technician work hours
- **Parts Management** - Track materials and inventory
- **Inspection Management** - Schedule and conduct asset inspections
- **Work Order Generation** - Create work orders from inspections or KEW.PA-10

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

### 1. KEW.PA-10 Form Processing
- Digital KEW.PA-10 form reception and validation
- Auto-generation from inspection findings
- Multi-level approval workflow with digital signatures
- Complete audit trail for compliance
- Bilingual form templates (EN/BM)

### 2. Inspection Management
- Scheduled and ad-hoc asset inspections
- Mobile-friendly inspection checklists
- Photo capture before/during/after repairs
- Inspector findings documentation
- Inspection history and trending

### 3. Workshop Job Management
- Job assignment based on technician skills
- Real-time job status tracking
- Work order generation and management
- Progress updates with photo evidence
- Quality control reviews

### 4. Parts and Inventory
- Government parts inventory tracking
- Parts usage documentation per job
- Stock level monitoring
- Procurement request generation
- Cost tracking for budget compliance

### 5. Reporting and Analytics
- KEW.PA-10 processing reports
- Workshop performance metrics
- Technician productivity analysis
- Asset maintenance history
- Budget utilization reports
- Audit-ready compliance reports

### 6. Role-Based Access Control
- Five-tier government role structure
- Permission-based feature access
- Workflow-specific authorizations
- Activity logging per user
- Secure authentication (Laravel Sanctum)

### 7. Advanced Features
- Bilingual interface (English/Bahasa Malaysia)
- Mobile-responsive design for field work
- Real-time notifications and updates
- Digital signature integration
- Photo attachment management
- Complete audit trail
- API for system integrations

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
