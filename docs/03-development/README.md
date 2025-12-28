# Development

## Overview

Developer guides, workflows, coding standards, and best practices for contributing to the Workshop Management System.

## Table of Contents

### [1. Developer Guide](01-developer-guide.md)

Complete developer onboarding and daily development workflow.

### [2. Coding Standards](02-coding-standards.md)

PHP, JavaScript, Vue.js coding standards and conventions.

### [3. Testing Guide](03-testing.md)

Writing and running tests with PHPUnit, Pest, and Vitest.

### [4. Git Workflow](04-git-workflow.md)

Branching strategy, commit conventions, and pull request process.

### [5. API Development](05-api-development.md)

Building RESTful APIs with Laravel and Sanctum authentication.

## Quick Links

### Common Development Tasks

**Start development servers:**

```bash
# Terminal 1: Laravel
php artisan serve

# Terminal 2: Vite (hot reload)
npm run dev
```

**Run tests:**

```bash
# All tests
php artisan test

# Frontend tests
npm run test
```

**Code quality:**

```bash
# PHP
./vendor/bin/phpstan analyse

# JavaScript
npm run lint
```

## Development Environment

### Required Tools

- PHP 8.2+
- Composer 2.5+
- Node.js 18+
- MySQL/PostgreSQL
- Git
- IDE (VS Code, PHPStorm recommended)

### Recommended VS Code Extensions

- Laravel Extension Pack
- Volar (Vue 3)
- ESLint
- Prettier
- Tailwind CSS IntelliSense

## Development Workflow

1. Create feature branch
2. Make changes following coding standards
3. Write/update tests
4. Run tests locally
5. Commit with conventional commits
6. Push and create pull request
7. Code review
8. Merge to main

## Getting Help

- Check [Getting Started](../01-getting-started/README.md) for setup issues
- Review [Architecture](../02-architecture/README.md) for design questions
- See [Sprint Planning](../04-sprints/README.md) for current tasks
- Ask in team discussions

## Related Documentation

- [Getting Started](../01-getting-started/README.md) - Setup and installation
- [Architecture](../02-architecture/README.md) - System design
- [Sprints](../04-sprints/README.md) - Sprint planning

---

**Next**: [Developer Guide →](01-developer-guide.md)
