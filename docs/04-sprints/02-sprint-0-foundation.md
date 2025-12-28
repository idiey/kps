# Sprint 0 Daily Todos - Foundation Sprint

> **Sprint Duration**: 10 Days
> **Sprint Goal**: Establish Laravel foundation with KEW.PA-10 government workflows and database schema
> **Sprint Start**: 2025-12-28
> **Sprint End**: 2026-01-06
> **Tech Stack**: Laravel 12 + Vue.js 3 + Inertia.js

## Sprint 0 Overview

Sprint 0 focuses on building the foundational infrastructure for the **Government Workshop Management System (KEW.PA-10)**. By the end of this sprint, we should have:

- Laravel project initialization complete
- Database schema for KEW.PA-10, inspections, and workflows
- Core models and controllers (Workshop, Job, KEW.PA-10, Inspection)
- Five-role authentication system (Pentadbiran, Penyelia, Pemeriksa, Pelulus, Juruteknik)
- Basic Vue.js components and Inertia pages
- Two workflow implementations (Option 1 & 2)

**Government Requirements**:
- KEW.PA-10 form management
- Digital signature integration
- Photo documentation system
- Bilingual support (EN/BM)
- Audit trail logging

## Daily Breakdown

### Day 1: Project Setup & Module Structure (2025-12-28)

#### Morning Tasks

- [x] Initialize Git repository
- [x] Create documentation structure
- [x] Write artifact analysis
- [ ] Create module directory structure
- [ ] Set up development environment

#### Afternoon Tasks

- [ ] Create module descriptor (`modWorkshop.class.php`)
- [ ] Define module metadata (ID, name, version)
- [ ] Set up module constants
- [ ] Create basic folder structure

#### Deliverables

- Module root directory with standard Dolibarr structure
- `core/modules/modWorkshop.class.php` with basic metadata
- Empty class files for main entities

#### Acceptance Criteria

- Module appears in Dolibarr module list
- Module can be activated/deactivated
- No errors in logs

---

### Day 2: Database Schema Design

#### Morning Tasks

- [ ] Design `llx_workshop` table schema
- [ ] Design `llx_workshop_job` table schema
- [ ] Design `llx_workshop_item` table schema
- [ ] Design `llx_workshop_time` table schema
- [ ] Create dictionary tables schemas

#### Afternoon Tasks

- [ ] Write SQL creation scripts
- [ ] Write SQL migration scripts
- [ ] Add table definitions to module descriptor
- [ ] Test schema creation

#### Deliverables

- Complete SQL scripts in `/sql/` directory
- Table definitions in module descriptor
- Database creation working

#### Acceptance Criteria

- All tables create successfully
- Indexes are properly defined
- Foreign keys are correct
- No SQL errors

---

### Day 3: Core Class - Workshop Entity

#### Morning Tasks

- [ ] Create `Workshop.class.php`
- [ ] Implement constructor and initialization
- [ ] Implement `create()` method
- [ ] Implement `fetch()` method

#### Afternoon Tasks

- [ ] Implement `update()` method
- [ ] Implement `delete()` method
- [ ] Add validation logic
- [ ] Add PHPDoc comments

#### Deliverables

- Complete `class/workshop.class.php`
- Full CRUD operations
- Input validation
- Error handling

#### Acceptance Criteria

- Can create workshop records
- Can fetch workshop by ID
- Can update workshop fields
- Can delete workshop (soft/hard)
- All methods have proper error handling

---

### Day 4: Core Class - WorkshopJob Entity

#### Morning Tasks

- [ ] Create `WorkshopJob.class.php`
- [ ] Implement constructor
- [ ] Implement `create()` method
- [ ] Implement `fetch()` method

#### Afternoon Tasks

- [ ] Implement `update()` method
- [ ] Implement `delete()` method
- [ ] Add job status management
- [ ] Add cost calculation methods

#### Deliverables

- Complete `class/workshopjob.class.php`
- Status workflow implementation
- Cost calculation logic

#### Acceptance Criteria

- Can create job records linked to workshop
- Status transitions work correctly
- Cost calculations are accurate
- Proper error handling

---

### Day 5: Core Classes - Items & Time Tracking

#### Morning Tasks

- [ ] Create `WorkshopItem.class.php`
- [ ] Implement item CRUD operations
- [ ] Add product integration
- [ ] Add stock management hooks

#### Afternoon Tasks

- [ ] Create `WorkshopTime.class.php`
- [ ] Implement time tracking CRUD
- [ ] Add time calculation methods
- [ ] Integrate with user/technician data

#### Deliverables

- Complete `class/workshopitem.class.php`
- Complete `class/workshoptime.class.php`
- Product integration working
- Time tracking functional

#### Acceptance Criteria

- Items link to products correctly
- Stock updates properly
- Time entries record accurately
- Duration calculations correct

---

### Day 6: UI - Workshop Card Page

#### Morning Tasks

- [ ] Create `workshop_card.php`
- [ ] Implement view mode
- [ ] Implement edit mode
- [ ] Add form validation

#### Afternoon Tasks

- [ ] Add status management UI
- [ ] Implement save functionality
- [ ] Add delete confirmation
- [ ] Style with Dolibarr CSS

#### Deliverables

- Working `workshop_card.php`
- View/Edit modes functional
- Proper form handling

#### Acceptance Criteria

- Can view workshop details
- Can edit and save changes
- Validation works client and server-side
- UI matches Dolibarr standards

---

### Day 7: UI - Workshop List & Job Pages

#### Morning Tasks

- [ ] Create `workshop_list.php`
- [ ] Implement filtering
- [ ] Add pagination
- [ ] Add sorting

#### Afternoon Tasks

- [ ] Create `workshop_job.php`
- [ ] Implement job form
- [ ] Add item/parts section
- [ ] Add time tracking section

#### Deliverables

- Working `workshop_list.php`
- Working `workshop_job.php`
- Filtering and pagination functional

#### Acceptance Criteria

- List shows all workshops
- Filters work correctly
- Job page handles all job operations
- Items and time can be added/removed

---

### Day 8: Module Configuration & Admin

#### Morning Tasks

- [ ] Create `admin/setup.php`
- [ ] Add module configuration options
- [ ] Create `admin/workshop.php` for settings
- [ ] Implement permission structure

#### Afternoon Tasks

- [ ] Add default values configuration
- [ ] Create status dictionary entries
- [ ] Add module activation hooks
- [ ] Test configuration save/load

#### Deliverables

- Working admin configuration pages
- Permissions properly defined
- Configuration persists correctly

#### Acceptance Criteria

- Admin pages accessible
- Settings save and load correctly
- Permissions control access properly
- Default values apply correctly

---

### Day 9: Integration & Hooks

#### Morning Tasks

- [ ] Create `actions_workshop.class.php`
- [ ] Implement third-party card hooks
- [ ] Add product card integration
- [ ] Create project integration

#### Afternoon Tasks

- [ ] Implement trigger class
- [ ] Add email notification triggers
- [ ] Create calendar integration
- [ ] Test all hooks

#### Deliverables

- Complete hooks implementation
- Trigger class functional
- Third-party integration working

#### Acceptance Criteria

- Workshop info shows on customer cards
- Triggers fire correctly
- Notifications send properly
- Calendar events create successfully

---

### Day 10: Testing & Documentation

#### Morning Tasks

- [ ] Write unit tests for Workshop class
- [ ] Write unit tests for WorkshopJob class
- [ ] Test database operations
- [ ] Test UI workflows

#### Afternoon Tasks

- [ ] Update documentation
- [ ] Create user guide
- [ ] Write installation instructions
- [ ] Sprint review preparation

#### Deliverables

- Complete test suite
- Updated documentation
- Installation guide
- Sprint review materials

#### Acceptance Criteria

- All unit tests pass
- No critical bugs
- Documentation complete
- Module ready for Sprint 1

---

## Daily Checklist Template

For each day, ensure:

- [ ] Morning standup (if team)
- [ ] Review previous day's work
- [ ] Complete morning tasks
- [ ] Lunch break / Code review
- [ ] Complete afternoon tasks
- [ ] Commit code with proper messages
- [ ] Update sprint board
- [ ] Document any blockers
- [ ] Push to remote repository

## Blockers & Issues

### Day 1

- None yet

### Day 2

- [Track blockers here]

### Day 3

- [Track blockers here]

## Sprint Metrics

### Planned vs Actual

| Day | Planned Tasks | Completed Tasks | Notes |
|-----|--------------|-----------------|-------|
| 1   | 9            | TBD            |       |
| 2   | 9            | TBD            |       |
| 3   | 8            | TBD            |       |
| 4   | 8            | TBD            |       |
| 5   | 8            | TBD            |       |
| 6   | 8            | TBD            |       |
| 7   | 8            | TBD            |       |
| 8   | 8            | TBD            |       |
| 9   | 8            | TBD            |       |
| 10  | 8            | TBD            |       |

### Velocity Tracking

- **Estimated Story Points**: 40
- **Completed Story Points**: TBD
- **Velocity**: TBD

## Sprint Retrospective Notes

### What Went Well

- [Add after sprint completion]

### What Could Be Improved

- [Add after sprint completion]

### Action Items for Next Sprint

- [Add after sprint completion]

---

**Sprint Master**: [TBD]
**Last Updated**: 2025-12-28
**Status**: In Progress - Day 1
