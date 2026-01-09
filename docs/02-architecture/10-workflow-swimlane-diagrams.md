# Workflow Swimlane Diagrams

> **Last Updated**: 2026-01-08

This document provides comprehensive swimlane diagrams illustrating the complete job lifecycle from creation to completion for all workflow types in the Workshop Management System.

---

## Table of Contents

1. [Actors Overview](#actors-overview)
2. [KEW.PA-10 External Reception Workflow](#kewpa-10-external-reception-workflow)
3. [Internal Inspection Workflow](#internal-inspection-workflow)
4. [Detailed Phase Breakdown](#detailed-phase-breakdown)
5. [Error Handling & Rejection Flows](#error-handling--rejection-flows)

---

## Actors Overview

The following actors participate in job workflows:

| Actor | Role | Responsibilities |
|-------|------|------------------|
| **Customer/Requester** | External party or internal staff | Submits repair requests, provides asset information |
| **Clerk/Operator** | Front desk staff | Creates jobs, fills forms, manages initial data entry |
| **Technician** | Technical staff | Performs inspections, repairs, adds technical notes |
| **Supervisor** | Management | Verifies work, approves/rejects jobs |
| **Approver** | Senior management | Budget approval, final sign-off |
| **System** | Automated | Validation, notifications, status updates |

---

## KEW.PA-10 External Reception Workflow

This workflow handles external repair requests where the customer brings an asset for repair.

### Workflow States

```
Received → Registered → Verified → Closed
```

### Sequence Diagram

```mermaid
sequenceDiagram
    autonumber
    box rgb(248, 250, 252) Customer/Requester
        participant REQ as Requester
    end
    box rgb(219, 234, 254) Front Desk / Clerk
        participant CLK as Clerk
    end
    box rgb(254, 243, 199) System
        participant SYS as System
    end
    box rgb(220, 252, 231) Technical Team
        participant TECH as Technician
    end
    box rgb(233, 213, 255) Management
        participant MGR as Supervisor
    end

    Note over REQ,MGR: 🚀 PHASE 1: Job Creation

    REQ->>CLK: Submit repair request (asset info)
    CLK->>SYS: Create New Job
    SYS->>SYS: Select Template & Workflow
    SYS->>SYS: Assign initial status ("Received")
    SYS-->>CLK: Job created with Job Number

    Note over REQ,MGR: 📝 PHASE 2: Form Completion (Status: Received)

    SYS->>CLK: Display required form (KEW.PA-10)
    CLK->>REQ: Request asset details
    REQ-->>CLK: Provide damage report info
    CLK->>SYS: Fill & Submit KEW.PA-10 form
    SYS->>SYS: Validate form data
    
    alt Form Incomplete
        SYS-->>CLK: ❌ Validation errors
        CLK->>SYS: Correct & resubmit
    end

    SYS->>SYS: Save field values to job
    CLK->>SYS: Execute transition: "Submit to Registration"
    SYS->>SYS: Update status → Registered

    Note over REQ,MGR: 🔧 PHASE 3: Technical Assessment (Status: Registered)

    SYS-->>TECH: Notify: New job assigned
    TECH->>SYS: View job details & form data
    TECH->>TECH: Inspect asset
    TECH->>SYS: Add technical notes & cost estimate
    TECH->>SYS: Execute transition: "Submit for Verification"
    SYS->>SYS: Update status → Verified

    Note over REQ,MGR: ✅ PHASE 4: Supervisor Approval (Status: Verified)

    SYS-->>MGR: Notify: Pending approval
    MGR->>SYS: Review job & technician report
    
    alt Approved
        MGR->>SYS: Execute transition: "Close Case"
        SYS->>SYS: Update status → Closed (Final)
        SYS-->>REQ: Notify: Job completed
    else Rejected / Needs Revision
        MGR->>SYS: Add notes, return to technician
        SYS->>SYS: Update status → Registered
        SYS-->>TECH: Notify: Revision required
    end

    Note over REQ,MGR: 🏁 PHASE 5: Job Complete (Status: Closed)

    SYS->>SYS: Record completion timestamp
    SYS->>SYS: Archive job data
```

### Flowchart View

```mermaid
flowchart TB
    subgraph REQ["👤 Customer/Requester"]
        R1[Submit repair request]
        R2[Provide asset info]
        R3[Receive completion notice]
    end

    subgraph CLK["🖥️ Clerk / Front Desk"]
        C1[Create new job]
        C2[Select template & workflow]
        C3[Fill KEW.PA-10 form]
        C4[Submit to Registration]
    end

    subgraph SYS["⚙️ System"]
        S1[(Assign initial status:<br/>Received)]
        S2{Form required<br/>for status?}
        S3[Validate form data]
        S4{Valid?}
        S5[Save field values]
        S6[Execute transition]
        S7[(Update status)]
        S8[Send notifications]
        S9[(Archive & Close)]
    end

    subgraph TECH["🔧 Technician"]
        T1[Receive assignment]
        T2[Inspect asset]
        T3[Add notes & estimate]
        T4[Submit for Verification]
    end

    subgraph MGR["📋 Supervisor"]
        M1[Review job & report]
        M2{Approved?}
        M3[Close case]
        M4[Return for revision]
    end

    R1 --> C1
    C1 --> C2
    C2 --> S1
    S1 --> S2
    S2 -->|Yes| C3
    S2 -->|No| S6
    R2 --> C3
    C3 --> S3
    S3 --> S4
    S4 -->|No| C3
    S4 -->|Yes| S5
    S5 --> C4
    C4 --> S6
    S6 --> S7
    S7 -->|Registered| S8
    S8 --> T1
    T1 --> T2
    T2 --> T3
    T3 --> T4
    T4 --> S6
    S7 -->|Verified| M1
    M1 --> M2
    M2 -->|Yes| M3
    M3 --> S6
    S6 --> S9
    S7 -->|Closed| R3
    M2 -->|No| M4
    M4 --> S7
    S7 -->|Registered| T1

    style REQ fill:#e0f2fe,stroke:#0284c7
    style CLK fill:#dbeafe,stroke:#2563eb
    style SYS fill:#fef3c7,stroke:#d97706
    style TECH fill:#dcfce7,stroke:#16a34a
    style MGR fill:#f3e8ff,stroke:#9333ea
```

---

## Internal Inspection Workflow

This workflow handles proactive internal asset inspections that may result in repair jobs.

### Workflow States

```
Inspection In Progress → Inspection Submitted → Inspection Verified → 
KEW.PA-10 Generated → Pending Approval → Approved → 
Repair In Progress → Repair Completed → Work Verified → Closed
```

### Sequence Diagram

```mermaid
sequenceDiagram
    autonumber
    box rgb(220, 252, 231) Inspection Team
        participant INS as Inspector
    end
    box rgb(233, 213, 255) Supervision
        participant SUP as Supervisor
    end
    box rgb(219, 234, 254) Administration
        participant ADM as Admin Officer
    end
    box rgb(254, 226, 226) Approval
        participant APR as Approver
    end
    box rgb(254, 243, 199) System
        participant SYS as System
    end
    box rgb(187, 247, 208) Execution
        participant TCH as Technician
    end

    Note over INS,TCH: 🔍 PHASE 1: Inspection

    INS->>SYS: Create inspection job
    SYS->>SYS: Status: "Inspection In Progress"
    INS->>INS: Conduct physical inspection
    INS->>SYS: Record findings & photos
    INS->>SYS: Execute: "Submit Inspection"
    SYS->>SYS: Status → "Inspection Submitted"

    Note over INS,TCH: ✅ PHASE 2: Verification

    SYS-->>SUP: Notify: Inspection pending review
    SUP->>SYS: Review inspection findings
    
    alt Findings Valid
        SUP->>SYS: Execute: "Verify Findings"
        SYS->>SYS: Status → "Inspection Verified"
    else Needs Clarification
        SUP->>SYS: Return to inspector with notes
        SYS->>SYS: Status → "Inspection In Progress"
        SYS-->>INS: Notify: Revision required
    end

    Note over INS,TCH: 📋 PHASE 3: KEW.PA-10 Generation

    SYS-->>ADM: Notify: Ready for form generation
    ADM->>SYS: Review verified findings
    ADM->>SYS: Generate KEW.PA-10 form
    ADM->>SYS: Execute: "Generate KEW.PA-10"
    SYS->>SYS: Status → "KEW.PA-10 Generated"

    Note over INS,TCH: 💰 PHASE 4: Approval Process

    ADM->>SYS: Execute: "Submit for Approval"
    SYS->>SYS: Status → "Pending Approval"
    SYS-->>APR: Notify: Job pending approval

    APR->>SYS: Review job & budget
    
    alt Approved
        APR->>SYS: Execute: "Approve Job"
        SYS->>SYS: Status → "Approved"
    else Rejected
        APR->>SYS: Reject with reason
        SYS->>SYS: Status → "Closed" (rejected)
        SYS-->>ADM: Notify: Job rejected
    end

    Note over INS,TCH: 🔧 PHASE 5: Repair Execution

    SYS-->>TCH: Notify: Job ready for repair
    TCH->>SYS: Execute: "Start Repair"
    SYS->>SYS: Status → "Repair In Progress"
    TCH->>TCH: Perform repair work
    TCH->>SYS: Log parts & labor
    TCH->>SYS: Execute: "Complete Repair"
    SYS->>SYS: Status → "Repair Completed"

    Note over INS,TCH: ✅ PHASE 6: Work Verification & Closure

    SYS-->>SUP: Notify: Repair completed
    SUP->>SYS: Inspect completed work
    
    alt Work Acceptable
        SUP->>SYS: Execute: "Verify Work"
        SYS->>SYS: Status → "Work Verified"
        SUP->>SYS: Execute: "Close Work Order"
        SYS->>SYS: Status → "Closed" (Final)
    else Needs Rework
        SUP->>SYS: Return to technician
        SYS->>SYS: Status → "Repair In Progress"
        SYS-->>TCH: Notify: Rework required
    end
```

### Flowchart View

```mermaid
flowchart TB
    subgraph INSPECT["🔍 Inspection Phase"]
        I1[Create inspection job]
        I2[Conduct inspection]
        I3[Record findings]
        I4[Submit inspection]
    end

    subgraph VERIFY["✅ Verification Phase"]
        V1[Supervisor reviews]
        V2{Findings valid?}
        V3[Verify findings]
        V4[Return for clarification]
    end

    subgraph FORM["📋 Form Generation"]
        F1[Admin reviews findings]
        F2[Generate KEW.PA-10]
        F3[Submit for approval]
    end

    subgraph APPROVE["💰 Approval Phase"]
        A1[Approver reviews]
        A2{Budget approved?}
        A3[Approve job]
        A4[Reject job]
    end

    subgraph REPAIR["🔧 Repair Phase"]
        R1[Technician starts]
        R2[Perform repairs]
        R3[Log parts & labor]
        R4[Complete repair]
    end

    subgraph CLOSE["🏁 Closure Phase"]
        C1[Supervisor inspects work]
        C2{Work acceptable?}
        C3[Verify & close]
        C4[Return for rework]
    end

    I1 --> I2 --> I3 --> I4
    I4 --> V1
    V1 --> V2
    V2 -->|Yes| V3
    V2 -->|No| V4
    V4 --> I2
    V3 --> F1
    F1 --> F2 --> F3
    F3 --> A1
    A1 --> A2
    A2 -->|Yes| A3
    A2 -->|No| A4
    A3 --> R1
    R1 --> R2 --> R3 --> R4
    R4 --> C1
    C1 --> C2
    C2 -->|Yes| C3
    C2 -->|No| C4
    C4 --> R1

    style INSPECT fill:#dcfce7,stroke:#16a34a
    style VERIFY fill:#f3e8ff,stroke:#9333ea
    style FORM fill:#dbeafe,stroke:#2563eb
    style APPROVE fill:#fef3c7,stroke:#d97706
    style REPAIR fill:#e0f2fe,stroke:#0284c7
    style CLOSE fill:#d1fae5,stroke:#059669
```

---

## Detailed Phase Breakdown

### Phase 1: Job Creation

```mermaid
flowchart LR
    subgraph Input
        A1[Customer Request]
        A2[Asset Information]
        A3[Problem Description]
    end

    subgraph Processing
        B1[Select Template]
        B2[Select Workflow]
        B3[Generate Job Number]
        B4[Set Initial Status]
    end

    subgraph Output
        C1[Job Created]
        C2[Initial Status Assigned]
        C3[Form Requirements Identified]
    end

    A1 --> B1
    A2 --> B1
    A3 --> B1
    B1 --> B2 --> B3 --> B4
    B4 --> C1
    B4 --> C2
    B4 --> C3
```

### Phase 2: Form Submission

```mermaid
flowchart TB
    A[Status requires form?] -->|No| B[Proceed to transition]
    A -->|Yes| C[Load form template]
    C --> D[Display form fields]
    D --> E[User fills form]
    E --> F{Validate data}
    F -->|Invalid| G[Show errors]
    G --> E
    F -->|Valid| H[Save field values]
    H --> I[Form completed]
    I --> B
```

### Phase 3: Status Transition

```mermaid
flowchart TB
    A[User initiates transition] --> B{Form required for current status?}
    B -->|Yes| C{Form completed?}
    B -->|No| D[Check permissions]
    C -->|No| E[❌ Block transition]
    C -->|Yes| D
    D --> F{User has permission?}
    F -->|No| E
    F -->|Yes| G[Execute pre-rules]
    G --> H[Update status]
    H --> I[Record in history]
    I --> J[Execute post-rules]
    J --> K[Send notifications]
    K --> L[✅ Transition complete]
```

---

## Error Handling & Rejection Flows

### Form Validation Errors

```mermaid
flowchart LR
    A[Submit Form] --> B{Validate}
    B -->|Missing Required| C[Error: Required fields]
    B -->|Invalid Format| D[Error: Format issues]
    B -->|Business Rule| E[Error: Rule violation]
    C --> F[Highlight fields]
    D --> F
    E --> F
    F --> G[User corrects]
    G --> A
    B -->|Valid| H[✅ Proceed]
```

### Rejection Handling

```mermaid
flowchart TB
    subgraph Rejection["Rejection Flow"]
        R1[Supervisor/Approver reviews]
        R2{Decision}
        R3[Approve]
        R4[Reject]
        R5[Add rejection reason]
        R6[Select return status]
        R7[Notify assignee]
    end

    subgraph Recovery["Recovery Flow"]
        C1[Assignee receives notification]
        C2[Review rejection notes]
        C3[Address issues]
        C4[Resubmit for review]
    end

    R1 --> R2
    R2 -->|Approve| R3
    R2 -->|Reject| R4
    R4 --> R5 --> R6 --> R7
    R7 --> C1 --> C2 --> C3 --> C4
    C4 --> R1

    style Rejection fill:#fee2e2,stroke:#dc2626
    style Recovery fill:#dbeafe,stroke:#2563eb
```

### Parallel Processing

```mermaid
flowchart TB
    subgraph Main["Main Workflow"]
        M1[Job In Progress]
        M2[Waiting for Parts]
        M3[Resume Work]
    end

    subgraph Parallel["Parallel Activities"]
        P1[Order Parts]
        P2[Document Progress]
        P3[Update Customer]
    end

    M1 --> P2
    M1 --> M2
    M2 --> P1
    M2 --> P3
    P1 --> M3
    P2 -.-> M3
    P3 -.-> M3
```

---

## Status Color Reference

| Status Type | Color | Hex Code | Usage |
|-------------|-------|----------|-------|
| Initial | 🟡 Amber | `#f59e0b` | Starting states |
| In Progress | 🔵 Blue | `#3b82f6` | Active work |
| Pending | 🟠 Orange | `#f97316` | Awaiting action |
| Verified | 🟣 Purple | `#8b5cf6` | Reviewed states |
| Approved | 🟢 Green | `#22c55e` | Approved states |
| Final/Closed | ⚫ Slate | `#1e293b` | Completed |
| Error | 🔴 Red | `#ef4444` | Error states |

---

## Related Documents

- [Workflow Option 1 - External Reception](07-workflow-option-1.md)
- [Workflow Option 2 - Internal Inspection](08-workflow-option-2.md)
- [Entity Relationship Diagram](erd.md)
- [Sprint 2: Workflow Restructuring](../04-sprints/05-sprint-2-workflow-restructuring.md)

---

**Last Updated**: 2026-01-08
