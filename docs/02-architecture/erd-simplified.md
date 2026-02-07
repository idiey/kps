# Simplified ERD - Current Schema

> **Version**: 3.0.1
> **Last Updated**: 2026-02-07
> **Status**: Current

---

## Overview

This ERD reflects the current database schema used by the Laravel application. It focuses on the core domain tables and omits framework tables (cache, queue) and Spatie permission tables for readability.

---

## ERD Diagram (Core Domain)

```mermaid
erDiagram
    companies {
        uuid id PK
        string name
        string subdomain
        string tier
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    workshops {
        uuid id PK
        uuid company_id FK
        string name
        string code
        string address
        string phone
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    users {
        bigint id PK
        uuid company_id FK
        string name
        string email
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }

    workshop_user {
        uuid workshop_id FK
        bigint user_id FK
        string role
        timestamp created_at
        timestamp updated_at
    }

    customers {
        uuid id PK
        uuid workshop_id FK
        string name
        string email
        string phone
        string customer_type
        timestamp created_at
        timestamp updated_at
    }

    government_departments {
        bigint id PK
        string name
        string ministry
        boolean is_active
    }

    assets {
        bigint id PK
        string asset_tag
        string name
        uuid workshop_id FK
    }

    workshop_jobs {
        uuid id PK
        uuid workshop_id FK
        uuid customer_id FK
        bigint assigned_to FK
        enum job_mode
        enum status
        enum priority
        timestamp created_at
        timestamp updated_at
    }

    job_notes {
        bigint id PK
        uuid workshop_job_id FK
        bigint user_id FK
        text content
        boolean is_public
        timestamp created_at
    }

    job_assignments {
        bigint id PK
        uuid workshop_job_id FK
        bigint assigned_to FK
        bigint assigned_by FK
        timestamp assigned_at
    }

    job_status_histories {
        bigint id PK
        uuid workshop_job_id FK
        enum from_status
        enum to_status
        bigint user_id FK
        timestamp changed_at
    }

    job_photos {
        bigint id PK
        uuid workshop_job_id FK
        string stage
        string path
        boolean is_public
        timestamp created_at
    }

    inspection_reports {
        bigint id PK
        uuid workshop_job_id FK
        bigint inspector_id FK
        text findings
        timestamp created_at
    }

    repair_completion_reports {
        bigint id PK
        uuid workshop_job_id FK
        bigint technician_id FK
        text summary
        timestamp created_at
    }

    parts {
        bigint id PK
        uuid workshop_id FK
        string part_number
        string name
        int stock
    }

    stock_movements {
        bigint id PK
        bigint part_id FK
        int quantity
        string movement_type
        timestamp created_at
    }

    settings {
        bigint id PK
        string key
        string value
        string group
    }

    companies ||--o{ workshops : has
    companies ||--o{ users : employs
    workshops ||--o{ customers : serves
    workshops ||--o{ assets : owns
    workshops ||--o{ workshop_jobs : handles
    workshops ||--o{ parts : stocks
    workshops ||--o{ settings : configures
    workshops }o--o{ users : assigned

    workshop_jobs }o--|| users : assigned_to
    workshop_jobs }o--|| customers : customer
    workshop_jobs }o--|| government_departments : gov_dept
    workshop_jobs }o--|| assets : asset

    workshop_jobs ||--o{ job_notes : notes
    workshop_jobs ||--o{ job_assignments : assignments
    workshop_jobs ||--o{ job_status_histories : status_changes
    workshop_jobs ||--o{ job_photos : photos
    workshop_jobs ||--o| inspection_reports : inspection
    workshop_jobs ||--o| repair_completion_reports : completion

    parts ||--o{ stock_movements : movements
```

---

## Notes

- KEW.PA-10 fields are stored directly on `workshop_jobs`
- Spatie permission tables are not shown here
- Queue and cache tables are not shown here

---

**Reference**: See `database/migrations` for full schema details.
