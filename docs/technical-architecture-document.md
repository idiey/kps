# Technical Architecture Document

> **Project**: Workshop Management Module for Dolibarr ERP/CRM
> **Version**: 1.0.0
> **Document Version**: 1.0
> **Last Updated**: 2025-12-28
> **Status**: Design Phase
> **Architecture Owner**: Technical Lead

## Table of Contents

1. [System Overview](#system-overview)
2. [Architecture Principles](#architecture-principles)
3. [System Architecture](#system-architecture)
4. [Database Design](#database-design)
5. [Class Structure](#class-structure)
6. [Module Integration](#module-integration)
7. [Security Architecture](#security-architecture)
8. [Performance Considerations](#performance-considerations)
9. [Deployment Architecture](#deployment-architecture)
10. [API Design](#api-design)

---

## 1. System Overview

### 1.1 Purpose

The Workshop Management Module extends Dolibarr ERP/CRM to provide comprehensive workshop, repair service, and maintenance operation management capabilities.

### 1.2 Scope

**In Scope:**

- Workshop/service center management
- Repair job tracking
- Time tracking for labor
- Parts/inventory management
- Cost calculations
- Invoice generation
- Customer service history
- Reporting and analytics
- Integration with Dolibarr core modules

**Out of Scope (v1.0):**

- Mobile native applications
- Advanced scheduling algorithms
- Multi-location inventory sync
- Customer self-service portal
- Real-time notifications (push)

### 1.3 System Context Diagram

```text
┌─────────────────────────────────────────────────────────────┐
│                     External Systems                         │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  ┌──────────┐      ┌──────────┐      ┌──────────┐          │
│  │  Email   │      │ Payment  │      │  PDF     │          │
│  │  Server  │      │ Gateway  │      │ Generator│          │
│  └────┬─────┘      └────┬─────┘      └────┬─────┘          │
│       │                 │                  │                 │
└───────┼─────────────────┼──────────────────┼─────────────────┘
        │                 │                  │
┌───────┼─────────────────┼──────────────────┼─────────────────┐
│       │                 │                  │                 │
│  ┌────▼────────────────────────────────────▼────┐           │
│  │         Dolibarr Core Framework              │           │
│  │  ┌────────────────────────────────────────┐  │           │
│  │  │   Workshop Management Module           │  │           │
│  │  │  ┌──────────┐  ┌──────────┐           │  │           │
│  │  │  │Workshop  │  │Workshop  │           │  │           │
│  │  │  │ Entity   │  │   Job    │           │  │           │
│  │  │  └────┬─────┘  └────┬─────┘           │  │           │
│  │  │       │             │                  │  │           │
│  │  │  ┌────▼─────┐  ┌────▼─────┐           │  │           │
│  │  │  │Workshop  │  │Workshop  │           │  │           │
│  │  │  │  Item    │  │   Time   │           │  │           │
│  │  │  └──────────┘  └──────────┘           │  │           │
│  │  └────────────────────────────────────────┘  │           │
│  └──────────────────────────────────────────────┘           │
│                                                               │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐  ┌──────────┐    │
│  │Third     │  │Product   │  │Invoice   │  │Project   │    │
│  │Party     │  │Module    │  │Module    │  │Module    │    │
│  └──────────┘  └──────────┘  └──────────┘  └──────────┘    │
│                   Dolibarr Core Modules                      │
└───────────────────────────────────────────────────────────────┘
                        │
                  ┌─────▼──────┐
                  │  Database  │
                  │  (MySQL)   │
                  └────────────┘
```

### 1.4 Technology Stack

| Layer | Technology | Version |
|-------|-----------|---------|
| Platform | Dolibarr ERP/CRM | 12.0+ |
| Programming Language | PHP | 7.4, 8.0, 8.1, 8.2 |
| Database | MySQL / MariaDB | 5.7+ / 10.3+ |
| Frontend | HTML5, CSS3, JavaScript | - |
| PDF Generation | TCPDF | Latest |
| Testing | PHPUnit | 9.x |
| Version Control | Git | 2.x |

---

## 2. Architecture Principles

### 2.1 Design Principles

1. **Dolibarr-First**: Follow Dolibarr conventions and patterns
2. **Modularity**: Loosely coupled, highly cohesive components
3. **Extensibility**: Support for customization and extension
4. **Maintainability**: Clean, documented, testable code
5. **Performance**: Optimize for common use cases
6. **Security**: Security by design, not afterthought
7. **Simplicity**: Prefer simple solutions over complex ones

### 2.2 Coding Standards

- Follow Dolibarr coding standards
- PSR-12 for PHP code formatting
- PHPDoc comments on all public methods
- Meaningful variable and function names
- DRY (Don't Repeat Yourself) principle
- SOLID principles for OOP

### 2.3 Architectural Patterns

**MVC Pattern** (Model-View-Controller)

- **Model**: Entity classes (`class/`)
- **View**: UI pages (`.php` files in root)
- **Controller**: Page logic + form handlers

**Repository Pattern** (via Dolibarr's CommonObject)

- Data access abstraction
- CRUD operations standardized

**Hook/Event Pattern**

- Loosely coupled module integration
- Event-driven notifications

---

## 3. System Architecture

### 3.1 Module Structure

```text
workshop/
├── admin/                         # Administration & Configuration
│   ├── setup.php                 # Module settings
│   ├── workshop.php              # Workshop config
│   └── about.php                 # About page
│
├── class/                         # Business Logic Layer
│   ├── workshop.class.php        # Workshop entity
│   ├── workshopjob.class.php     # Job entity
│   ├── workshopitem.class.php    # Item entity
│   ├── workshoptime.class.php    # Time tracking entity
│   └── api_workshop.class.php    # REST API endpoints
│
├── core/                          # Core Module Components
│   ├── modules/
│   │   ├── modWorkshop.class.php # Module descriptor
│   │   └── workshop/
│   │       ├── doc/              # Document generators
│   │       │   └── pdf_workshop.modules.php
│   │       └── mod_workshop_standard.php  # Numbering
│   ├── boxes/                    # Dashboard widgets
│   │   └── box_workshop.php
│   └── triggers/                 # Event triggers
│       └── interface_99_modWorkshop_WorkshopTrigger.class.php
│
├── css/                           # Stylesheets
│   └── workshop.css
│
├── img/                           # Images & Icons
│   ├── object_workshop.png       # 32x32
│   └── object_workshop@2x.png    # 64x64
│
├── js/                            # JavaScript
│   └── workshop.js
│
├── langs/                         # Translations
│   ├── en_US/
│   │   └── workshop.lang
│   └── fr_FR/
│       └── workshop.lang
│
├── lib/                           # Helper Functions
│   └── workshop.lib.php          # Common functions
│
├── sql/                           # Database Scripts
│   ├── llx_workshop.sql          # Main table
│   ├── llx_workshop_job.sql      # Job table
│   ├── llx_workshop_item.sql     # Item table
│   ├── llx_workshop_time.sql     # Time table
│   ├── llx_c_workshop_status.sql # Dictionary
│   └── data.sql                  # Initial data
│
├── test/                          # Unit Tests
│   ├── WorkshopTest.php
│   └── WorkshopJobTest.php
│
├── docs/                          # Documentation
│
├── workshop_card.php              # Workshop view/edit page
├── workshop_list.php              # Workshop list page
├── workshop_job.php               # Job management page
└── README.md                      # Project readme
```

### 3.2 Layered Architecture

```text
┌───────────────────────────────────────────────────────────┐
│                    Presentation Layer                      │
│  ┌─────────┐  ┌─────────┐  ┌─────────┐  ┌─────────┐      │
│  │ Card    │  │ List    │  │ Job     │  │ Admin   │      │
│  │ Pages   │  │ Pages   │  │ Pages   │  │ Pages   │      │
│  └────┬────┘  └────┬────┘  └────┬────┘  └────┬────┘      │
└───────┼────────────┼────────────┼────────────┼────────────┘
        │            │            │            │
┌───────┼────────────┼────────────┼────────────┼────────────┐
│       │            │            │            │             │
│  ┌────▼────────────▼────────────▼────────────▼────┐       │
│  │             Business Logic Layer              │       │
│  │  ┌──────────┐  ┌──────────┐  ┌──────────┐    │       │
│  │  │ Workshop │  │Workshop  │  │Workshop  │    │       │
│  │  │  Class   │  │Job Class │  │Item Class│    │       │
│  │  └────┬─────┘  └────┬─────┘  └────┬─────┘    │       │
│  └───────┼─────────────┼─────────────┼───────────┘       │
│          │             │             │                    │
└──────────┼─────────────┼─────────────┼────────────────────┘
           │             │             │
┌──────────┼─────────────┼─────────────┼────────────────────┐
│          │             │             │                     │
│  ┌───────▼─────────────▼─────────────▼───────────┐        │
│  │          Data Access Layer (CommonObject)     │        │
│  │  ┌──────────┐  ┌──────────┐  ┌──────────┐    │        │
│  │  │ Create() │  │ Fetch()  │  │ Update() │    │        │
│  │  └──────────┘  └──────────┘  └──────────┘    │        │
│  └───────────────────────┬──────────────────────┘        │
│                          │                                │
└──────────────────────────┼────────────────────────────────┘
                           │
┌──────────────────────────▼────────────────────────────────┐
│                    Database Layer (MySQL)                  │
│  ┌───────────┐  ┌──────────┐  ┌───────────┐              │
│  │llx_       │  │llx_      │  │llx_       │              │
│  │workshop   │  │workshop_ │  │workshop_  │              │
│  │           │  │job       │  │item       │              │
│  └───────────┘  └──────────┘  └───────────┘              │
└───────────────────────────────────────────────────────────┘
```

---

## 4. Database Design

### 4.1 Entity Relationship Diagram

```text
┌────────────────────┐
│  llx_workshop      │
├────────────────────┤
│ rowid (PK)         │───┐
│ ref                │   │
│ entity             │   │
│ label              │   │
│ description        │   │
│ status             │   │
│ date_creation      │   │
│ tms                │   │
│ fk_user_author     │   │
│ fk_user_modif      │   │
│ note_public        │   │
│ note_private       │   │
└────────────────────┘   │
                         │ 1:N
                         │
        ┌────────────────▼────────────────┐
        │  llx_workshop_job                │
        ├──────────────────────────────────┤
        │ rowid (PK)                       │
        │ ref                              │
        │ fk_workshop (FK)                 │───┐
        │ fk_soc (FK) → llx_societe        │   │
        │ fk_project (FK) → llx_projet     │   │
        │ fk_user_assigned (FK) → llx_user │   │
        │ date_start                       │   │
        │ date_end                         │   │
        │ estimated_hours                  │   │
        │ status                           │   │
        │ description                      │   │
        │ note_public                      │   │
        │ note_private                     │   │
        │ total_ht                         │   │
        │ total_ttc                        │   │
        │ date_creation                    │   │
        │ tms                              │   │
        └──────────────────────────────────┘   │
                                               │ 1:N
        ┌──────────────────────────────────────┴───────────┐
        │                                                  │
        │                                                  │
┌───────▼──────────────┐                   ┌───────▼──────────────┐
│ llx_workshop_item    │                   │ llx_workshop_time    │
├──────────────────────┤                   ├──────────────────────┤
│ rowid (PK)           │                   │ rowid (PK)           │
│ fk_workshop_job (FK) │                   │ fk_workshop_job (FK) │
│ fk_product (FK)      │                   │ fk_user (FK)         │
│ description          │                   │ date_time            │
│ qty                  │                   │ duration             │
│ price_unit           │                   │ description          │
│ total_ht             │                   │ hourly_rate          │
│ tva_tx               │                   │ total_ht             │
│ date_creation        │                   │ date_creation        │
│ tms                  │                   │ tms                  │
└──────────────────────┘                   └──────────────────────┘

┌──────────────────────────┐
│ llx_c_workshop_status    │ (Dictionary)
├──────────────────────────┤
│ rowid (PK)               │
│ code                     │
│ label                    │
│ active                   │
└──────────────────────────┘
```

### 4.2 Table Specifications

#### 4.2.1 llx_workshop

**Purpose**: Main workshop entity table

```sql
CREATE TABLE llx_workshop (
    rowid           INTEGER AUTO_INCREMENT PRIMARY KEY,
    ref             VARCHAR(30) NOT NULL,
    entity          INTEGER DEFAULT 1 NOT NULL,
    label           VARCHAR(255),
    description     TEXT,
    status          INTEGER DEFAULT 1 NOT NULL,
    date_creation   DATETIME,
    tms             TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    fk_user_author  INTEGER,
    fk_user_modif   INTEGER,
    note_public     TEXT,
    note_private    TEXT,
    import_key      VARCHAR(14),

    UNIQUE KEY uk_workshop_ref (ref, entity),
    KEY idx_workshop_entity (entity),
    KEY idx_workshop_status (status),
    CONSTRAINT fk_workshop_user_author FOREIGN KEY (fk_user_author)
        REFERENCES llx_user(rowid),
    CONSTRAINT fk_workshop_user_modif FOREIGN KEY (fk_user_modif)
        REFERENCES llx_user(rowid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

**Indexes:**

- Primary: `rowid`
- Unique: `(ref, entity)` - Ensures unique reference per entity
- Index: `entity` - Multi-entity filtering
- Index: `status` - Status-based queries

#### 4.2.2 llx_workshop_job

**Purpose**: Repair jobs/work orders

```sql
CREATE TABLE llx_workshop_job (
    rowid                INTEGER AUTO_INCREMENT PRIMARY KEY,
    ref                  VARCHAR(30) NOT NULL,
    fk_workshop          INTEGER NOT NULL,
    fk_soc               INTEGER,
    fk_project           INTEGER,
    fk_user_assigned     INTEGER,
    date_start           DATETIME,
    date_end             DATETIME,
    estimated_hours      DOUBLE(24,8),
    status               INTEGER DEFAULT 0 NOT NULL,
    description          TEXT,
    note_public          TEXT,
    note_private         TEXT,
    total_ht             DOUBLE(24,8) DEFAULT 0,
    total_tva            DOUBLE(24,8) DEFAULT 0,
    total_ttc            DOUBLE(24,8) DEFAULT 0,
    date_creation        DATETIME,
    tms                  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    fk_user_author       INTEGER,
    fk_user_modif        INTEGER,
    import_key           VARCHAR(14),

    UNIQUE KEY uk_workshop_job_ref (ref),
    KEY idx_workshop_job_fk_workshop (fk_workshop),
    KEY idx_workshop_job_fk_soc (fk_soc),
    KEY idx_workshop_job_status (status),
    KEY idx_workshop_job_assigned (fk_user_assigned),
    CONSTRAINT fk_workshop_job_workshop FOREIGN KEY (fk_workshop)
        REFERENCES llx_workshop(rowid) ON DELETE CASCADE,
    CONSTRAINT fk_workshop_job_soc FOREIGN KEY (fk_soc)
        REFERENCES llx_societe(rowid),
    CONSTRAINT fk_workshop_job_project FOREIGN KEY (fk_project)
        REFERENCES llx_projet(rowid),
    CONSTRAINT fk_workshop_job_user FOREIGN KEY (fk_user_assigned)
        REFERENCES llx_user(rowid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

**Status Values:**

- 0: Draft
- 1: Validated/Open
- 2: In Progress
- 3: Completed
- 4: Invoiced
- 9: Cancelled

#### 4.2.3 llx_workshop_item

**Purpose**: Parts/products used in jobs

```sql
CREATE TABLE llx_workshop_item (
    rowid              INTEGER AUTO_INCREMENT PRIMARY KEY,
    fk_workshop_job    INTEGER NOT NULL,
    fk_product         INTEGER,
    description        TEXT,
    qty                DOUBLE DEFAULT 1,
    price_unit         DOUBLE(24,8),
    total_ht           DOUBLE(24,8),
    tva_tx             DOUBLE(7,4),
    total_tva          DOUBLE(24,8),
    total_ttc          DOUBLE(24,8),
    date_creation      DATETIME,
    tms                TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    KEY idx_workshop_item_job (fk_workshop_job),
    KEY idx_workshop_item_product (fk_product),
    CONSTRAINT fk_workshop_item_job FOREIGN KEY (fk_workshop_job)
        REFERENCES llx_workshop_job(rowid) ON DELETE CASCADE,
    CONSTRAINT fk_workshop_item_product FOREIGN KEY (fk_product)
        REFERENCES llx_product(rowid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

#### 4.2.4 llx_workshop_time

**Purpose**: Time tracking for labor

```sql
CREATE TABLE llx_workshop_time (
    rowid              INTEGER AUTO_INCREMENT PRIMARY KEY,
    fk_workshop_job    INTEGER NOT NULL,
    fk_user            INTEGER NOT NULL,
    date_time          DATETIME NOT NULL,
    duration           INTEGER NOT NULL,  -- Duration in seconds
    description        TEXT,
    hourly_rate        DOUBLE(24,8),
    total_ht           DOUBLE(24,8),
    date_creation      DATETIME,
    tms                TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    KEY idx_workshop_time_job (fk_workshop_job),
    KEY idx_workshop_time_user (fk_user),
    KEY idx_workshop_time_date (date_time),
    CONSTRAINT fk_workshop_time_job FOREIGN KEY (fk_workshop_job)
        REFERENCES llx_workshop_job(rowid) ON DELETE CASCADE,
    CONSTRAINT fk_workshop_time_user FOREIGN KEY (fk_user)
        REFERENCES llx_user(rowid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

### 4.3 Dictionary Tables

#### llx_c_workshop_status

```sql
CREATE TABLE llx_c_workshop_status (
    rowid    INTEGER AUTO_INCREMENT PRIMARY KEY,
    code     VARCHAR(16) NOT NULL,
    label    VARCHAR(255),
    active   TINYINT DEFAULT 1 NOT NULL,
    UNIQUE KEY uk_c_workshop_status_code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO llx_c_workshop_status (code, label, active) VALUES
('DRAFT', 'Draft', 1),
('OPEN', 'Open', 1),
('IN_PROGRESS', 'In Progress', 1),
('COMPLETED', 'Completed', 1),
('INVOICED', 'Invoiced', 1),
('CANCELLED', 'Cancelled', 1);
```

---

## 5. Class Structure

### 5.1 Class Diagram

```text
┌─────────────────────────────────┐
│      CommonObject               │
│  (Dolibarr Base Class)          │
└──────────────▲──────────────────┘
               │
               │ extends
     ┌─────────┴─────────┬──────────────┬──────────────┐
     │                   │              │              │
┌────▼─────────┐  ┌──────▼─────┐  ┌────▼─────┐  ┌────▼─────┐
│  Workshop    │  │WorkshopJob │  │Workshop  │  │Workshop  │
│              │  │            │  │  Item    │  │  Time    │
├──────────────┤  ├────────────┤  ├──────────┤  ├──────────┤
│+ rowid       │  │+ rowid     │  │+ rowid   │  │+ rowid   │
│+ ref         │  │+ ref       │  │+ fk_job  │  │+ fk_job  │
│+ label       │  │+ fk_soc    │  │+ fk_prod │  │+ fk_user │
│+ status      │  │+ status    │  │+ qty     │  │+ duration│
├──────────────┤  ├────────────┤  ├──────────┤  ├──────────┤
│+ create()    │  │+ create()  │  │+ create()│  │+ create()│
│+ fetch()     │  │+ fetch()   │  │+ fetch() │  │+ fetch() │
│+ update()    │  │+ update()  │  │+ update()│  │+ update()│
│+ delete()    │  │+ delete()  │  │+ delete()│  │+ delete()│
│              │  │+ setStatus()│  │+ calcTotal│+ calcTotal│
└──────────────┘  │+ getItems()│  └──────────┘  └──────────┘
                  │+ getTimes()│
                  └────────────┘
```

### 5.2 Workshop Class

**File**: `class/workshop.class.php`

```php
/**
 * Workshop entity class
 */
class Workshop extends CommonObject
{
    /**
     * @var string Module name
     */
    public $module = 'workshop';

    /**
     * @var string Element name
     */
    public $element = 'workshop';

    /**
     * @var string Table name
     */
    public $table_element = 'workshop';

    /**
     * @var int Workshop ID
     */
    public $id;

    /**
     * @var string Reference
     */
    public $ref;

    /**
     * @var string Label
     */
    public $label;

    /**
     * @var string Description
     */
    public $description;

    /**
     * @var int Status
     */
    public $status;

    /**
     * Constructor
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Create workshop in database
     *
     * @param  User $user User object
     * @return int <0 if KO, >0 if OK
     */
    public function create($user)
    {
        // Implementation
    }

    /**
     * Load object from database
     *
     * @param  int $id Object ID
     * @param  string $ref Object ref
     * @return int <0 if KO, 0 if not found, >0 if OK
     */
    public function fetch($id, $ref = '')
    {
        // Implementation
    }

    /**
     * Update object in database
     *
     * @param  User $user User object
     * @return int <0 if KO, >0 if OK
     */
    public function update($user)
    {
        // Implementation
    }

    /**
     * Delete object from database
     *
     * @param  User $user User object
     * @return int <0 if KO, >0 if OK
     */
    public function delete($user)
    {
        // Implementation
    }

    /**
     * Get list of jobs for this workshop
     *
     * @return array Array of WorkshopJob objects
     */
    public function getJobs()
    {
        // Implementation
    }
}
```

### 5.3 WorkshopJob Class

**File**: `class/workshopjob.class.php`

**Key Methods:**

```php
class WorkshopJob extends CommonObject
{
    // ... properties

    /**
     * Calculate total cost (labor + parts)
     *
     * @return double Total cost
     */
    public function calculateTotal()
    {
        $total_labor = $this->calculateLaborCost();
        $total_parts = $this->calculatePartsCost();
        return $total_labor + $total_parts;
    }

    /**
     * Calculate labor cost from time entries
     *
     * @return double Labor cost
     */
    public function calculateLaborCost()
    {
        // Sum all time entries
    }

    /**
     * Calculate parts cost from items
     *
     * @return double Parts cost
     */
    public function calculatePartsCost()
    {
        // Sum all item costs
    }

    /**
     * Change job status
     *
     * @param int $status New status
     * @param User $user User object
     * @return int <0 if KO, >0 if OK
     */
    public function setStatus($status, $user)
    {
        // Validate transition
        // Update status
        // Trigger event
    }

    /**
     * Generate invoice from job
     *
     * @param User $user User object
     * @return int Invoice ID if OK, <0 if KO
     */
    public function createInvoice($user)
    {
        // Create invoice
        // Add labor lines
        // Add parts lines
        // Link invoice to job
    }
}
```

---

## 6. Module Integration

### 6.1 Hooks Implementation

**File**: `class/actions_workshop.class.php`

```php
/**
 * Workshop hooks class
 */
class ActionsWorkshop
{
    /**
     * Add workshop info to third-party card
     *
     * @param array $parameters Hook parameters
     * @param CommonObject $object Object
     * @param string $action Action code
     * @return int 0
     */
    public function formObjectOptions($parameters, &$object, &$action)
    {
        if ($parameters['currentcontext'] == 'thirdpartycard') {
            // Display recent workshop jobs for customer
        }
        return 0;
    }

    /**
     * Add workshop tab to project
     *
     * @param array $parameters Hook parameters
     * @return int 0
     */
    public function printCommonFooter($parameters)
    {
        if ($parameters['currentcontext'] == 'projectcard') {
            // Add workshop jobs tab
        }
        return 0;
    }
}
```

### 6.2 Triggers Implementation

**File**: `core/triggers/interface_99_modWorkshop_WorkshopTrigger.class.php`

```php
/**
 * Workshop trigger class
 */
class InterfaceWorkshopTrigger
{
    /**
     * Trigger function
     *
     * @param string $action Event code
     * @param CommonObject $object Object
     * @param User $user User
     * @param Translate $langs Translation
     * @param conf $conf Config
     * @return int <0 if KO, 0 if nothing, >0 if OK
     */
    public function runTrigger($action, $object, $user, $langs, $conf)
    {
        switch ($action) {
            case 'WORKSHOPJOB_CREATE':
                // Send notification to assigned user
                break;

            case 'WORKSHOPJOB_MODIFY':
                // Log changes
                break;

            case 'WORKSHOPJOB_SETSTATUS':
                // Send status change notification
                if ($object->status == WorkshopJob::STATUS_COMPLETED) {
                    // Notify customer job is complete
                }
                break;

            case 'WORKSHOPJOB_DELETE':
                // Cleanup related records
                break;
        }
        return 0;
    }
}
```

---

## 7. Security Architecture

### 7.1 Permission Model

**Defined in**: `core/modules/modWorkshop.class.php`

```php
// Read permission
$this->rights[$r][0] = 500001;
$this->rights[$r][1] = 'Read workshops and jobs';
$this->rights[$r][4] = 'read';
$r++;

// Create/modify permission
$this->rights[$r][0] = 500002;
$this->rights[$r][1] = 'Create and modify workshops';
$this->rights[$r][4] = 'write';
$r++;

// Delete permission
$this->rights[$r][0] = 500003;
$this->rights[$r][1] = 'Delete workshops';
$this->rights[$r][4] = 'delete';
$r++;

// Export permission
$this->rights[$r][0] = 500004;
$this->rights[$r][1] = 'Export workshop data';
$this->rights[$r][4] = 'export';
$r++;
```

### 7.2 Access Control

**In all pages:**

```php
// Check user is logged in
if (!$user->rights->workshop->read) {
    accessforbidden();
}

// Check write permission for actions
if ($action == 'add' || $action == 'update') {
    if (!$user->rights->workshop->write) {
        accessforbidden();
    }
}

// Check delete permission
if ($action == 'confirm_delete') {
    if (!$user->rights->workshop->delete) {
        accessforbidden();
    }
}
```

### 7.3 Input Validation

```php
// Sanitize inputs
$ref = GETPOST('ref', 'alpha');
$label = GETPOST('label', 'alphanohtml');
$description = GETPOST('description', 'restricthtml');
$status = GETPOST('status', 'int');

// Validate
if (empty($ref)) {
    setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentitiesnoconv("Ref")), null, 'errors');
    $error++;
}

// SQL injection prevention (using parameterized queries)
$sql = "SELECT * FROM ".MAIN_DB_PREFIX."workshop WHERE rowid = ?";
$resql = $this->db->query($sql, array($id));
```

### 7.4 CSRF Protection

```php
// Add token to forms
print '<input type="hidden" name="token" value="'.newToken().'">';

// Verify token on submission
if (!empty($_POST) && !verifToken()) {
    setEventMessages($langs->trans("ErrorTokenExpired"), null, 'errors');
    $error++;
}
```

---

## 8. Performance Considerations

### 8.1 Database Optimization

**Indexing Strategy:**

- Primary keys on all tables
- Foreign keys indexed
- Status fields indexed (frequently queried)
- Entity field indexed (multi-company)
- Composite indexes for common queries

**Query Optimization:**

```php
// Use joins instead of N+1 queries
$sql = "SELECT w.*, s.nom as customer_name";
$sql .= " FROM ".MAIN_DB_PREFIX."workshop_job as w";
$sql .= " LEFT JOIN ".MAIN_DB_PREFIX."societe as s ON w.fk_soc = s.rowid";
$sql .= " WHERE w.status = ".WorkshopJob::STATUS_IN_PROGRESS;
```

### 8.2 Caching Strategy

```php
// Cache workshop list
$cachekey = 'workshop_list_'.md5($filter);
$workshops = $cache->read($cachekey);
if ($workshops === false) {
    $workshops = $workshop->fetchAll($filter);
    $cache->write($cachekey, $workshops, 300); // 5 min cache
}
```

### 8.3 Pagination

```php
// Implement pagination for large lists
$limit = GETPOST('limit', 'int') ? GETPOST('limit', 'int') : $conf->liste_limit;
$page = GETPOST('page', 'int') ? GETPOST('page', 'int') : 0;
$offset = $limit * $page;

$sql .= $this->db->plimit($limit + 1, $offset);
```

---

## 9. Deployment Architecture

### 9.1 Installation Process

**On module activation:**

1. Create database tables
2. Insert dictionary data
3. Set up default configuration
4. Initialize permissions
5. Create default menus

**Module Descriptor:**

```php
public function init($options = '')
{
    global $conf, $db;

    // Load SQL files
    $dir = dol_buildpath('/custom/workshop/sql/');
    $files = array(
        'llx_workshop.sql',
        'llx_workshop_job.sql',
        'llx_workshop_item.sql',
        'llx_workshop_time.sql',
        'data.sql'
    );

    foreach ($files as $file) {
        $result = $this->_load_tables($dir.$file);
        if ($result < 0) return -1;
    }

    return 1;
}
```

### 9.2 Upgrade Process

**Migration scripts:**

```text
sql/
├── llx_workshop-1.0.0.sql
├── llx_workshop-1.1.0.sql
└── update_1.0_to_1.1.sql
```

### 9.3 Environment Configuration

**Development:**

- Debug mode enabled
- SQL logging enabled
- Error display on

**Production:**

- Debug mode disabled
- SQL logging minimal
- Error logging to file
- Caching enabled

---

## 10. API Design

### 10.1 REST API Endpoints

**File**: `class/api_workshop.class.php`

```php
/**
 * API class for workshop module
 */
class Workshops extends DolibarrApi
{
    /**
     * @url GET /
     */
    public function index($sortfield = "t.rowid", $sortorder = 'ASC', $limit = 100, $page = 0)
    {
        // Return list of workshops
    }

    /**
     * @url GET {id}
     */
    public function get($id)
    {
        // Return single workshop
    }

    /**
     * @url POST /
     */
    public function post($request_data = null)
    {
        // Create workshop
    }

    /**
     * @url PUT {id}
     */
    public function put($id, $request_data = null)
    {
        // Update workshop
    }

    /**
     * @url DELETE {id}
     */
    public function delete($id)
    {
        // Delete workshop
    }

    /**
     * @url GET {id}/jobs
     */
    public function getJobs($id)
    {
        // Get jobs for workshop
    }
}
```

**API URLs:**

- `GET /api/index.php/workshops` - List workshops
- `GET /api/index.php/workshops/{id}` - Get workshop
- `POST /api/index.php/workshops` - Create workshop
- `PUT /api/index.php/workshops/{id}` - Update workshop
- `DELETE /api/index.php/workshops/{id}` - Delete workshop

---

## Appendices

### Appendix A: Naming Conventions

**Tables**: `llx_{module}_{entity}`
**Classes**: `PascalCase`
**Methods**: `camelCase`
**Variables**: `snake_case` or `camelCase`
**Constants**: `UPPER_CASE`

### Appendix B: Error Codes

| Code | Meaning |
|------|---------|
| -1   | Generic error |
| -2   | Database error |
| -3   | Permission denied |
| -4   | Validation error |
| -5   | Not found |

### Appendix C: Status Codes

| Code | Status |
|------|--------|
| 0    | Draft |
| 1    | Validated |
| 2    | In Progress |
| 3    | Completed |
| 4    | Invoiced |
| 9    | Cancelled |

---

**Document Control:**

- **Author**: Technical Architecture Team
- **Reviewers**: [TBD]
- **Approved By**: [TBD]
- **Version**: 1.0
- **Last Updated**: 2025-12-28
- **Next Review**: 2026-01-28
