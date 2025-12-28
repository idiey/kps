# Workshop Management Module for Dolibarr

## Overview

This is a Workshop Management module for Dolibarr ERP/CRM system, designed to help businesses manage repair workshops, service centers, and maintenance operations.

## Project Status

**Current Status:** Sprint 0 - Foundation Development (Day 1)

The project has completed comprehensive planning and is now in active development. Module structure, database schema, and core classes are being implemented.

**Latest Updates:**
- ✅ Complete documentation framework established
- ✅ Technical architecture designed
- ✅ Sprint planning completed
- ✅ Module descriptor created
- ✅ Database schema designed
- ✅ Core Workshop class implemented
- 🔄 Development in progress

## Purpose

The Workshop Management module aims to provide:

- **Job Tracking**: Manage repair jobs, service orders, and maintenance tasks
- **Time Tracking**: Record time spent by technicians on various jobs
- **Parts Management**: Track parts and materials used in repairs
- **Customer Management**: Integration with Dolibarr's customer database
- **Invoicing**: Generate invoices based on work performed and parts used
- **Reporting**: Comprehensive reports on workshop activity and performance
- **Scheduling**: Plan and schedule workshop activities and technician assignments

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

## Key Features (Planned)

1. **Workshop Job Management**
   - Create and track repair jobs
   - Assign jobs to technicians
   - Track job status and progress
   - Estimate and actual time/cost tracking

2. **Parts and Inventory Integration**
   - Link to Dolibarr product catalog
   - Track parts used in repairs
   - Automatic stock updates
   - Parts cost calculation

3. **Time and Labor Tracking**
   - Record technician hours
   - Multiple billing rates
   - Time-based invoicing
   - Labor cost analysis

4. **Document Generation**
   - Work orders
   - Repair estimates
   - Service completion certificates
   - Invoices

5. **Reporting and Analytics**
   - Workshop activity reports
   - Technician performance
   - Revenue and cost analysis
   - Customer service history

## Technical Requirements

- **Platform**: Dolibarr ERP/CRM (v12.0 or higher recommended)
- **PHP**: 7.4 or higher
- **Database**: MySQL/MariaDB
- **Dependencies**:
  - Dolibarr core modules (Product, Third Party, Projects)

## Installation

**Note:** Module is currently under development. Full installation instructions will be available with v1.0.0 release.

### Development Installation

1. Clone the repository:
   ```bash
   git clone http://127.0.0.1:57733/git/idiey/wshop_man.git
   cd wshop_man
   ```

2. Symlink to Dolibarr custom directory:
   ```bash
   ln -s /path/to/wshop_man /path/to/dolibarr/htdocs/custom/workshop
   ```

3. Enable module in Dolibarr:
   - Navigate to Home → Setup → Modules
   - Find "Workshop Management"
   - Click Enable

4. Configure module:
   - Navigate to Home → Setup → Modules → Workshop Management → Setup

## Development Roadmap

**Sprint 0: Foundation (Days 1-10)** - Current
1. ✅ Artifact analysis and planning
2. ✅ Comprehensive documentation
3. ✅ Module structure setup
4. ✅ Database schema design
5. 🔄 Core entity classes (in progress)
6. ⏳ Basic UI pages
7. ⏳ Admin configuration

**Sprint 1: Core Features (Days 11-20)**
- Job status workflow
- Customer integration
- Job assignment system
- Notes and documentation

**Sprint 2: Advanced Features (Days 21-30)**
- Time tracking
- Parts management
- Cost calculations

**Sprint 3: Integration (Days 31-40)**
- Invoice generation
- Project integration
- Calendar integration

**Sprint 4: Reporting (Days 41-50)**
- Reports and analytics
- Document generation

**Sprint 5: Release (Days 51-60)**
- Testing and QA
- Documentation finalization
- Production deployment

See [Implementation Roadmap](./docs/implementation-roadmap.md) for detailed timeline.

## Contributing

Contributions are welcome. Please follow Dolibarr's coding standards and module development guidelines.

## License

This module follows Dolibarr's licensing model (GPL v3 or compatible).

## Support

For support and questions, please refer to the project's issue tracker.

## Author

Workshop Management Module Development Team

## Links

- [Dolibarr Official Website](https://www.dolibarr.org)
- [Dolibarr Module Development Documentation](https://wiki.dolibarr.org/index.php/Module_development)

---

**Last Updated**: 2025-12-28
