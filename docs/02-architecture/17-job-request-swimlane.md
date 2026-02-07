# Job Request Workflow - Swimlane Diagrams

This document illustrates the complete workflow for job requests in the Government Workshop Management System, showing both **Normal Jobs** and **KEW.PA-10 Jobs**.

**Last Updated**: 2026-02-04  
**Status**: Active  
**Related Documents**:
- [Simplified Job Modes](./16-simplified-job-modes.md)
- [ERD Simplified](./erd-simplified.md)
- [Workflow Option 2](./08-workflow-option-2.md)

---

## Complete Job Request Swimlane (By Phase)

> [!TIP]
> The workflow is broken into 4 phases for better readability. Use the carousel to navigate through each phase.

````carousel
### Phase 1: Job Initiation & Reception

```mermaid
graph TB
    subgraph Customer["👤 Customer/Requester"]
        A1[Select Job Type]
        A2[Fill Normal Job Form]
        A3[Submit KEW.PA-10 Form]
    end

    subgraph PTJ["🏛️ PTJ/Government Department"]
        B1[Prepare KEW.PA-10 Document]
        B2[Sign & Authorize]
        B3[Submit to Workshop]
    end

    subgraph Workshop_Reception["📋 Workshop Reception"]
        C1[Receive Job Request]
        C2[Verify Documents]
        C3[Generate Job Number]
        C4[Create Job Record]
        C5[Status: NEW]
    end

    %% Normal Job Flow
    A1 -->|Normal Job| A2
    A2 --> C1
    
    %% KEW.PA-10 Flow
    A1 -->|KEW.PA-10| B1
    B1 --> B2
    B2 --> B3
    B3 --> A3
    A3 --> C1

    %% Reception Processing
    C1 --> C2
    C2 -->|KEW.PA-10: Verify Signatures & Form| C3
    C2 -->|Normal: Basic Validation| C3
    C3 --> C4
    C4 --> C5

    style C5 fill:#3b82f6,color:#fff
```

**Key Activities:**
- Customer selects job type (Normal or KEW.PA-10)
- KEW.PA-10 requires PTJ authorization and signatures
- Reception verifies documents and creates job record
- Job status: **NEW**

<!-- slide -->

### Phase 2: Inspection & Approval

```mermaid
graph TB
    subgraph Workshop_Reception["📋 Workshop Reception"]
        C5[Job Status: NEW]
        C6[Assign to Inspector]
    end

    subgraph Inspector["🔍 Inspector"]
        D1[Conduct Inspection]
        D2[Document Findings]
        D3[Estimate Cost & Time]
        D4[Create Inspection Report]
        D5[Status: INSPECTION_IN_PROGRESS]
    end

    subgraph Supervisor["👨‍💼 Workshop Supervisor"]
        E1[Review Inspection Report]
        E2{Approve?}
        E3[Status: INSPECTION_APPROVED]
        E4[Status: INSPECTION_REJECTED]
    end

    subgraph Customer["👤 Customer"]
        A4[Notified: Rejected]
    end

    C5 --> C6
    C6 --> D1
    D1 --> D5
    D5 --> D2
    D2 --> D3
    D3 --> D4
    D4 --> E1
    E1 --> E2
    E2 -->|Yes| E3
    E2 -->|No| E4
    E4 --> A4

    style D5 fill:#6366f1,color:#fff
    style E3 fill:#14b8a6,color:#fff
    style E4 fill:#ef4444,color:#fff
```

**Key Activities:**
- Inspector conducts physical inspection
- Documents findings, estimates cost and time
- Supervisor reviews and approves/rejects
- Job status: **PENDING_INSPECTION** → **INSPECTION_IN_PROGRESS** → **INSPECTION_APPROVED/REJECTED**

<!-- slide -->

### Phase 3: Repair & Parts Management

```mermaid
graph TB
    subgraph Supervisor["👨‍💼 Supervisor"]
        E3[Inspection Approved]
        E5[Assign Technician]
        E6[Monitor Progress]
    end

    subgraph Technician["🔧 Technician"]
        T1[Receive Assignment]
        T2{Parts Needed?}
        T3[Status: AWAITING_PARTS]
        T4[Status: REPAIR_IN_PROGRESS]
        T5[Perform Repairs]
        T6[Complete Work]
        T7[Status: PENDING_REVIEW]
    end

    subgraph Store["📦 Parts & Inventory"]
        F1[Check Availability]
        F2{In Stock?}
        F3[Issue Parts]
        F4[Update Inventory]
        F5[Order Parts]
    end

    E3 --> E5
    E5 --> T1
    T1 --> T2
    T2 -->|Yes| F1
    T2 -->|No| T4
    F1 --> F2
    F2 -->|Yes| F3
    F2 -->|No| F5
    F3 --> F4
    F4 --> T4
    F5 --> T3
    T3 -.->|Parts Arrived| T4
    T4 --> T5
    T5 --> E6
    E6 -.->|Monitor| T5
    T5 --> T6
    T6 --> T7

    style T3 fill:#f97316,color:#fff
    style T4 fill:#fbbf24,color:#fff
    style T7 fill:#a78bfa,color:#fff
```

**Key Activities:**
- Supervisor assigns technician
- Technician checks parts availability
- If parts needed, job waits (AWAITING_PARTS)
- Repairs performed and work completed
- Job status: **INSPECTION_APPROVED** → **AWAITING_PARTS** (if needed) → **REPAIR_IN_PROGRESS** → **PENDING_REVIEW**

<!-- slide -->

### Phase 4: Completion & Invoicing

```mermaid
graph TB
    subgraph Technician["🔧 Technician"]
        T7[Work Complete]
    end

    subgraph Supervisor["👨‍💼 Supervisor"]
        E7[Review Completion]
        E8{Quality OK?}
        E9[Status: COMPLETED]
        E10[Authorize Invoice]
    end

    subgraph Finance["💰 Finance"]
        G1[Generate Invoice]
        G2[Status: INVOICED]
        G3[Process Payment]
        G4[Close Job]
    end

    subgraph PTJ["🏛️ PTJ (KEW.PA-10 Only)"]
        B4[Receive Returned KEW.PA-10]
        B5[Status: PENDING_KEW_PA_10_RETURN]
        B6[Status: KEW_PA_10_RETURNED]
    end

    subgraph Customer["👤 Customer"]
        A5[Receive Completed Asset]
        A6[Job Closed]
    end

    T7 --> E7
    E7 --> E8
    E8 -->|Yes| E9
    E8 -->|No - Rework| T7
    E9 --> E10
    E10 --> G1
    
    G1 -->|KEW.PA-10| B5
    G1 -->|Normal| G2
    
    B5 --> B4
    B4 --> B6
    B6 --> G2
    
    G2 --> G3
    G3 --> G4
    G4 --> A5
    A5 --> A6

    style E9 fill:#10b981,color:#fff
    style B5 fill:#0ea5e9,color:#fff
    style B6 fill:#34d399,color:#fff
    style G2 fill:#a855f7,color:#fff
```

**Key Activities:**
- Supervisor reviews completed work
- If approved, authorizes invoice
- **KEW.PA-10 jobs**: Must return form to PTJ before invoicing
- **Normal jobs**: Direct to invoicing
- Payment processed and job closed
- Job status: **PENDING_REVIEW** → **COMPLETED** → **PENDING_KEW_PA_10_RETURN** (KEW only) → **KEW_PA_10_RETURNED** (KEW only) → **INVOICED**
````

---

## Detailed Status Flow by Job Type

### Normal Job Workflow

```mermaid
stateDiagram-v2
    [*] --> NEW: Customer submits request
    NEW --> PENDING_INSPECTION: Assign inspector
    NEW --> IN_PROGRESS: Skip inspection
    NEW --> CANCELLED: Customer cancels
    
    PENDING_INSPECTION --> INSPECTION_IN_PROGRESS: Inspector starts
    INSPECTION_IN_PROGRESS --> INSPECTION_APPROVED: Passed
    INSPECTION_IN_PROGRESS --> INSPECTION_REJECTED: Failed
    
    INSPECTION_APPROVED --> REPAIR_IN_PROGRESS: Start repairs
    INSPECTION_APPROVED --> AWAITING_PARTS: Parts needed
    INSPECTION_REJECTED --> NEW: Resubmit
    INSPECTION_REJECTED --> CANCELLED: Abandon
    
    AWAITING_PARTS --> REPAIR_IN_PROGRESS: Parts arrived
    REPAIR_IN_PROGRESS --> PENDING_REVIEW: Work complete
    REPAIR_IN_PROGRESS --> AWAITING_PARTS: More parts needed
    
    PENDING_REVIEW --> COMPLETED: Approved
    PENDING_REVIEW --> REPAIR_IN_PROGRESS: Rework
    IN_PROGRESS --> COMPLETED: Direct completion
    IN_PROGRESS --> AWAITING_PARTS: Parts needed
    
    COMPLETED --> INVOICED: Generate invoice
    COMPLETED --> IN_PROGRESS: Reopen
    INVOICED --> [*]: Job closed
    CANCELLED --> [*]: Job cancelled
```

### KEW.PA-10 Job Workflow

```mermaid
stateDiagram-v2
    [*] --> NEW: PTJ submits KEW.PA-10
    
    state "Document Verification" as VERIFY {
        [*] --> CheckForm: Verify form
        CheckForm --> CheckSignatures: Form valid
        CheckSignatures --> [*]: Signatures valid
    }
    
    NEW --> VERIFY: Reception receives
    VERIFY --> PENDING_INSPECTION: Documents verified
    VERIFY --> CANCELLED: Invalid documents
    
    PENDING_INSPECTION --> INSPECTION_IN_PROGRESS: Inspector assigned
    INSPECTION_IN_PROGRESS --> INSPECTION_APPROVED: Inspection passed
    INSPECTION_IN_PROGRESS --> INSPECTION_REJECTED: Inspection failed
    
    INSPECTION_APPROVED --> REPAIR_IN_PROGRESS: Start repairs
    INSPECTION_APPROVED --> AWAITING_PARTS: Parts needed
    INSPECTION_REJECTED --> CANCELLED: Cannot proceed
    
    AWAITING_PARTS --> REPAIR_IN_PROGRESS: Parts arrived
    REPAIR_IN_PROGRESS --> PENDING_REVIEW: Work complete
    REPAIR_IN_PROGRESS --> AWAITING_PARTS: More parts needed
    
    PENDING_REVIEW --> COMPLETED: Supervisor approves
    PENDING_REVIEW --> REPAIR_IN_PROGRESS: Rework needed
    
    COMPLETED --> PENDING_KEW_PA_10_RETURN: Return to PTJ
    PENDING_KEW_PA_10_RETURN --> KEW_PA_10_RETURNED: PTJ received
    KEW_PA_10_RETURNED --> INVOICED: Generate invoice
    INVOICED --> [*]: Job closed
    CANCELLED --> [*]: Job cancelled
```

---

## Swimlane by Responsibility

```mermaid
graph TB
    subgraph "👤 Customer"
        direction LR
        CS1["Select Job Mode<br/>(Normal/KEW.PA-10)"]
        CS2["Submit Request"]
        CS3["Track Status"]
        CS4["Collect Asset"]
    end

    subgraph "🏛️ PTJ Department"
        direction LR
        PS1["Prepare KEW.PA-10"]
        PS2["Authorize & Sign"]
        PS3["Submit to Workshop"]
        PS4["Receive Returned Form"]
    end

    subgraph "📋 Workshop Reception"
        direction LR
        WS1["Receive Request"]
        WS2["Verify Documents"]
        WS3["Create Job Record"]
        WS4["Generate Job Number"]
    end

    subgraph "🔍 Inspector"
        direction LR
        IS1["Conduct Inspection"]
        IS2["Document Findings"]
        IS3["Estimate Cost/Time"]
        IS4["Recommend Action"]
    end

    subgraph "👨‍💼 Supervisor"
        direction LR
        SS1["Review Inspection"]
        SS2["Approve/Reject"]
        SS3["Assign Technician"]
        SS4["Monitor Progress"]
        SS5["Final Review"]
    end

    subgraph "🔧 Technician"
        direction LR
        TS1["Receive Assignment"]
        TS2["Check Parts"]
        TS3["Perform Repairs"]
        TS4["Update Status"]
        TS5["Complete Work"]
    end

    subgraph "📦 Store/Inventory"
        direction LR
        ST1["Check Availability"]
        ST2["Issue Parts"]
        ST3["Update Inventory"]
    end

    subgraph "💰 Finance"
        direction LR
        FS1["Generate Invoice"]
        FS2["Process Payment"]
        FS3["Close Job"]
    end

    %% Flow connections
    CS1 --> CS2
    CS2 -.->|KEW.PA-10| PS1
    CS2 -.->|Normal| WS1
    
    PS1 --> PS2 --> PS3 --> WS1
    
    WS1 --> WS2 --> WS3 --> WS4 --> IS1
    
    IS1 --> IS2 --> IS3 --> IS4 --> SS1
    
    SS1 --> SS2 --> SS3 --> TS1
    
    TS1 --> TS2
    TS2 -.->|Parts Needed| ST1
    ST1 --> ST2 --> ST3 -.-> TS3
    TS2 -.->|Parts Available| TS3
    
    TS3 --> TS4 --> TS5 --> SS4
    
    SS4 --> SS5 --> FS1
    
    FS1 -.->|KEW.PA-10| PS4
    FS1 --> FS2 --> FS3 --> CS4
    PS4 --> FS2
    
    WS3 -.-> CS3
    TS4 -.-> CS3
    SS5 -.-> CS3
```

---

## Key Differences: Normal vs KEW.PA-10

| Aspect | Normal Job | KEW.PA-10 Job |
|--------|-----------|---------------|
| **Initiator** | Customer directly | PTJ/Government Department |
| **Documentation** | Basic job form | Official KEW.PA-10 form with signatures |
| **Verification** | Minimal | Strict document & signature verification |
| **Priority** | Low/Medium/High | Urgent/High/Medium/Low (KewPa10Priority) |
| **Completion** | Direct to invoicing | Must return KEW.PA-10 to PTJ first |
| **Status Flow** | NEW → ... → COMPLETED → INVOICED | NEW → ... → COMPLETED → PENDING_KEW_PA_10_RETURN → KEW_PA_10_RETURNED → INVOICED |
| **Fields** | Standard job fields | Additional KEW-specific fields (inspector IC, findings, recommendations) |

---

## Critical Handoff Points

> [!IMPORTANT]
> **Key handoff points where delays commonly occur:**

1. **PTJ → Workshop**: KEW.PA-10 document preparation and submission
2. **Reception → Inspector**: Job assignment and prioritization
3. **Inspector → Supervisor**: Inspection report review and approval
4. **Supervisor → Technician**: Work assignment and parts availability
5. **Technician → Store**: Parts requisition and inventory management
6. **Completion → Finance**: Invoice generation and payment processing
7. **Finance → PTJ**: KEW.PA-10 document return (KEW jobs only)

---

## Status Tracking Points

Customers can track their job at these key milestones:

- ✅ **Job Created** - Job number assigned
- 🔍 **Inspection Scheduled** - Inspector assigned
- 📋 **Inspection Complete** - Report generated
- ✅ **Approved for Repair** - Work authorized
- 🔧 **Repair in Progress** - Technician working
- ⏸️ **Awaiting Parts** - Inventory delay
- ✅ **Work Completed** - Ready for review
- 💰 **Invoiced** - Payment processing
- 🏁 **Closed** - Asset returned

---

## System Implementation Notes

### Database Schema
- **Job Mode**: `job_mode` enum (NORMAL, KEW_PA_10)
- **Status Tracking**: `status` enum with 14 states
- **KEW Fields**: Dedicated columns for KEW.PA-10 data
- **Audit Trail**: `JobStatusHistory` and `JobAssignment` tables

### Controllers
- [JobController.php](file:///c:/Users/zuraidiismail/RnD/workshop/app/Http/Controllers/JobController.php) - Main job CRUD operations
- [JobAnalyticsController.php](file:///c:/Users/zuraidiismail/RnD/workshop/app/Http/Controllers/JobAnalyticsController.php) - Reporting and analytics

### Models
- [WorkshopJob.php](file:///c:/Users/zuraidiismail/RnD/workshop/app/Models/WorkshopJob.php) - Core job model with mode-specific methods
- [JobStatus.php](file:///c:/Users/zuraidiismail/RnD/workshop/app/Enums/JobStatus.php) - Status enum with transition rules

### Frontend Components
- Job creation forms (Normal and KEW.PA-10)
- Status tracking dashboard
- Timeline view for job history
- Inspector and technician assignment interfaces
