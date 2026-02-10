# Decisions Log: KPS

> Record of key architectural and design decisions with rationale.

**Purpose**: Track important decisions and their context for future reference  
**Intended audience**: Development team, architects, product owners  
**Last updated**: 2026-02-10  
**Links**: [Decisions](../02-architecture/04-decisions.md) | [PRD](../02-architecture/01-prd.md)

## Decision Format

Each entry includes:
- **Date**: When the decision was made
- **Decision**: What was decided
- **Rationale**: Why this approach was chosen
- **Alternatives**: What other options were considered
- **Impact**: How this affects the system

---

## 2026-02-10: Documentation Audit Standards

**Decision**: Conduct comprehensive documentation audits following the /docs-audit workflow with code synchronization verification

**Rationale**:
- Ensures documentation stays synchronized with codebase
- Identifies gaps in operational documentation (Development, Deployment, User Guides)
- Validates markdown quality and link integrity
- Provides measurable completeness metrics (73% overall, 100% for architecture)
- Creates actionable improvement roadmap

**Alternatives**:
- Manual ad-hoc reviews (rejected: inconsistent, error-prone)
- Rely on contributor diligence (rejected: no verification process)

**Impact**:
- Documentation grade: B+ (excellent architecture, needs operational guides)
- Clear priority: Create Development, Deployment, and User Guides
- Established baseline for future audits

---


## 2026-02-10: Documentation Reorganization

**Decision**: Reorganize all documentation to antigravity-docs standards with numbered, context-based folders

**Rationale**:
- Improves discoverability and navigation
- Provides clear separation of concerns (getting-started, architecture, development, deployment, user-guide)
- Follows industry best practices
- Enables better scalability as documentation grows

**Alternatives**:
- Keep flat structure (rejected: poor scalability)
- Use topic-based folders without numbers (rejected: unclear ordering)

**Impact**:
- All documentation files moved and renamed
- All cross-references updated
- Better user experience for documentation consumers
- Easier maintenance and updates

---

## 2026-02-09: KPS Database Schema

**Decision**: Use 7 dedicated KPS tables with UUID primary keys and proper foreign key relationships

**Rationale**:
- Clear domain separation from other modules
- UUIDs provide better security and distribution
- Proper normalization reduces data redundancy
- Foreign keys ensure referential integrity

**Alternatives**:
- Reuse existing tables (rejected: poor separation of concerns)
- Use integer IDs (rejected: less secure, harder to distribute)

**Impact**:
- Clean database schema
- Better performance with proper indexes
- Easier to maintain and extend

---

## 2026-02-07: Dual Sidebar System

**Decision**: Implement two-sidebar UX with HQ sidebar and Site sidebar

**Rationale**:
- Clear visual separation between HQ and site scopes
- Improves user experience by showing only relevant navigation
- Reduces cognitive load for site-scoped users
- Aligns with multi-tenant architecture

**Alternatives**:
- Single sidebar with conditional items (rejected: cluttered)
- Separate applications for HQ and sites (rejected: maintenance overhead)

**Impact**:
- Better UX for different user roles
- Clear scope boundaries
- Easier navigation

---

## 2026-02-07: Site Context Middleware

**Decision**: Create `EnsureKpsSiteContext` middleware to handle site scoping

**Rationale**:
- Centralized site context resolution
- Automatic site assignment for site users
- Prevents unauthorized cross-site access
- Simplifies controller logic

**Alternatives**:
- Handle in each controller (rejected: code duplication)
- Use global scope (rejected: less flexible)

**Impact**:
- Cleaner controller code
- Better security
- Consistent site scoping

---

## 2026-02-04: Allocation Waterfall Algorithm

**Decision**: Use priority ASC, due_date ASC (null last), created_at ASC ordering for debt allocation

**Rationale**:
- Deterministic and predictable
- Prioritizes urgent debts
- Handles edge cases (no due date)
- Auditable and transparent

**Alternatives**:
- FIFO only (rejected: ignores priority)
- Manual allocation (rejected: error-prone)
- Random allocation (rejected: unpredictable)

**Impact**:
- Fair and consistent allocation
- Clear business rules
- Easy to explain to users

---

## 2026-01-XX: Multi-site Architecture

**Decision**: Implement multi-site architecture with HQ oversight and site-scoped operations

**Rationale**:
- Matches real-world organizational structure
- Enables centralized monitoring with distributed operations
- Supports scalability across multiple sites
- Clear data ownership and access control

**Alternatives**:
- Single-site system (rejected: doesn't meet requirements)
- Completely separate systems per site (rejected: no HQ visibility)

**Impact**:
- Requires site context management
- More complex authorization
- Better alignment with business needs

---

## Decision Template

Use this template for new decisions:

```markdown
## YYYY-MM-DD: [Decision Title]

**Decision**: [What was decided]

**Rationale**:
- [Why this approach]
- [Key benefits]

**Alternatives**:
- [Option 1] (rejected: [reason])
- [Option 2] (rejected: [reason])

**Impact**:
- [How this affects the system]
- [Trade-offs made]
```

## Related Documents

- [Architectural Decisions](../02-architecture/04-decisions.md)
- [PRD](../02-architecture/01-prd.md)
- [System Design](../02-architecture/02-system-design.md)

---

**Last Updated**: 2026-02-10
