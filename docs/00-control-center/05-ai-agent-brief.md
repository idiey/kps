# AI Agent Brief: KPS

> Instructions and context for AI agents working on the KPS project.

**Purpose**: Provide AI agents with essential context, patterns, and guidelines  
**Intended audience**: AI assistants (Antigravity, Copilot, etc.)  
**Last updated**: 2026-02-10  
**Links**: [PRD](../02-architecture/01-prd.md) | [System Design](../02-architecture/02-system-design.md)

## Project Context

**What is KPS?**  
Multi-site cooperative system for managing FELDA peneroka debt and monthly potongan allocation with deterministic waterfall ordering and HQ oversight.

**Key Characteristics**:
- Multi-site architecture with HQ and site scopes
- Dual sidebar UX (HQ + Site)
- Deterministic allocation algorithm
- Audit-first approach
- Role-based access control

## Code Patterns

### Naming Conventions

- **Controllers**: `{Entity}Controller` (e.g., `PenerokaController`)
- **Models**: Singular, PascalCase (e.g., `Peneroka`, `MonthlyDeduction`)
- **Tables**: Plural, snake_case (e.g., `penerokas`, `monthly_deductions`)
- **Routes**: Kebab-case (e.g., `/kps/sites/{site}/peneroka`)
- **Migrations**: `YYYY_MM_DD_HHMMSS_create_{table}_table.php`

### Architecture Patterns

1. **Site Scoping**: All site-specific routes use `/kps/sites/{site}/...` pattern
2. **Middleware**: `EnsureKpsSiteContext` handles site context resolution
3. **Services**: Domain logic in dedicated service classes (AllocationService, etc.)
4. **Audit Logging**: All critical operations logged to `kps_audit_logs`

### Database Patterns

- **Primary Keys**: UUID for all KPS tables
- **Foreign Keys**: Explicit relationships with cascade rules
- **Soft Deletes**: Use `deleted_at` for recoverable deletions
- **Timestamps**: Always include `created_at` and `updated_at`

## Common Tasks

### Adding a New Entity

1. Create migration with UUID primary key
2. Create model with relationships
3. Create controller with CRUD methods
4. Add routes to `routes/kps.php`
5. Update documentation in `docs/02-architecture/02-system-design.md`
6. Add to glossary if domain-specific term

### Adding a New Route

1. Add to `routes/kps.php` in appropriate group
2. Ensure middleware is applied
3. Document in System Design
4. Update API examples if needed

### Modifying Allocation Logic

1. Update `AllocationService`
2. Add/update tests
3. Document in PRD and System Design
4. Log decision in `04-decisions.md`

## Important Constraints

### Business Rules

- **Allocation Ordering**: MUST use priority ASC, due_date ASC (null last), created_at ASC
- **Monthly Closing**: Closed months MUST be locked from edits
- **Audit Trail**: All allocation adjustments MUST be logged
- **Site Scoping**: Site users MUST only access their assigned site

### Technical Constraints

- **UUIDs**: All KPS tables use UUID primary keys
- **Transactions**: Allocation operations MUST be atomic
- **Validation**: All user input MUST be validated
- **Authorization**: All routes MUST check permissions

## Documentation Standards

### When to Update Docs

- **Always**: When adding/changing database schema
- **Always**: When adding/changing API routes
- **Always**: When making architectural decisions
- **Sometimes**: When adding new features (update PRD)
- **Rarely**: When fixing bugs (unless it reveals a gap in docs)

### Where to Document

| Type | Location |
|------|----------|
| Requirements | `docs/02-architecture/01-prd.md` |
| Database Schema | `docs/02-architecture/02-system-design.md` |
| API Routes | `docs/02-architecture/02-system-design.md` |
| Decisions | `docs/00-control-center/04-decisions.md` |
| Glossary | `docs/02-architecture/03-glossary.md` |
| Development | `docs/03-development/` (when created) |

## Testing Guidelines

### Test Coverage Priorities

1. **Critical**: Allocation logic (waterfall ordering)
2. **High**: Authorization and site scoping
3. **High**: Data validation and constraints
4. **Medium**: CRUD operations
5. **Low**: UI components

### Test Types

- **Unit**: Service logic, helpers, utilities
- **Feature**: API endpoints, workflows
- **Browser**: UI interactions, navigation

## Common Pitfalls

### ❌ Don't Do This

- Don't bypass site scoping middleware
- Don't modify allocation logic without updating docs
- Don't use integer IDs for KPS tables
- Don't skip audit logging for critical operations
- Don't allow cross-site data access

### ✅ Do This Instead

- Always use site context from middleware
- Document all allocation logic changes
- Use UUIDs consistently
- Log all allocation adjustments
- Enforce site boundaries in queries

## Quick Reference

### User Roles

| Role | Code | Can Do |
|------|------|--------|
| Admin Officer | `pentadbiran` | Manage KEW.PA-10, admin tasks |
| Company Admin | `company_admin` | Manage sites, analytics |
| Supervisor | `penyelia` | Approve reports, assign jobs |
| Inspector | `pemeriksa` | Conduct inspections |
| Approver | `pelulus` | Approve workflows |
| Technician | `juruteknik` | Perform repairs |
| Front Desk | `kaunter` | Job intake, customer service |

### Key Files

- **Routes**: `routes/kps.php`
- **Migrations**: `database/migrations/2026_02_09_*`
- **Controllers**: `app/Http/Controllers/Kps/`
- **Models**: `app/Models/`
- **Services**: `app/Services/` (to be verified)
- **Enums**: `app/Enums/UserRole.php`

### Key Tables

- `sites` - Site master data
- `penerokas` - Cooperative members
- `debts` - Debt records
- `monthly_deductions` - Monthly deduction entries
- `deduction_allocations` - Allocation line items
- `kps_site_user` - Site-user assignments
- `kps_audit_logs` - Audit trail

## Related Documents

- [PRD](../02-architecture/01-prd.md)
- [System Design](../02-architecture/02-system-design.md)
- [Glossary](../02-architecture/03-glossary.md)
- [Decisions](04-decisions.md)

---

**Last Updated**: 2026-02-10
