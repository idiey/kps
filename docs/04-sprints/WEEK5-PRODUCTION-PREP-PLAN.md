# Week 5 Plan: Production Readiness & Mockup Data

> **Phase**: Production Prep
> **Goal**: Populate system with high-quality mockup data and finalize web application for production demonstration.
> **Context**: Replacing "Mobile App Updates" to focus on Web App polish and realistic data demonstration. No migration of legacy data required.

---

## 🎯 Objectives
1.  **Fresh Data Seeding**: Create comprehensive seeders for realistic KEW.PA-10 and Normal job scenarios.
2.  **Visual Polish**: Ensure the UI looks "Production Ready" with populated data.
3.  **Quality Assurance**: Verify all user flows with the new data.
4.  **Performance**: Optimize query performance for the new static schema.

---

## 📅 Daily Schedule

### Day 1: Advanced Seeding Logic 🌱
**Focus**: Realistic Data Generation
- [ ] Create `KewJobSeeder`:
    - Generate jobs in various states: `NEW`, `PENDING_APPROVAL`, `INSPECTION_APPROVED`, `INSPECTION_REJECTED`.
    - Generate realistic "Inspection Findings" and "Recommendations" text.
    - Seed "Approval History" records for approved/rejected jobs (so timeline looks active).
- [ ] Create `NormalJobSeeder`:
    - Generate jobs with varied priorities (Low, Medium, High, Urgent).
    - Generate varied customer profiles.
- [ ] Update `DatabaseSeeder` to run these new seeders cleanly.

### Day 2: UI Polish & Demo Prep ✨
**Focus**: Visual Excellence
- [ ] **Data visualization check**: Ensure long text in "Findings" doesn't break layout.
- [ ] **Status Colors**: Verify all status badges look correct with varied data.
- [ ] **Empty States**: Verify UI looks good even when lists are empty (before seeding).
- [ ] **Dashboard Check**: Ensure "Pending Approvals" widget (if exists) shows correct count.

### Day 3: Comprehensive Web Testing 🧪
**Focus**: User Flows Verification
- [ ] **Supervisor Flow**:
    - Login as Supervisor.
    - Approve a seeded "Pending" job.
    - Reject a seeded "Pending" job.
- [ ] **Inspector Flow**:
    - Create a new KEW job.
    - submit for approval.
- [ ] **Search & Filter**:
    - Test searching for seeded Asset Tags.
    - Test filtering by Status.

### Day 4: Performance & Optimization 🚀
**Focus**: Speed
- [ ] **Database Indexing**:
    - Add indexes for `job_mode`, `status`, `assigned_to`.
    - Add indexes for search columns (`kew_asset_tag`, `kew_vehicle_registration`).
- [ ] **Query Review**:
    - Check N+1 problems in `JobController@index` (ensure relationships aren't eager loaded unnecessarily, or ARE eager loaded if needed).

---

## 📉 Risk Assessment

| Risk | Impact | Mitigation |
|------|--------|------------|
| Seeder complexity | Moderate | Write modular factories; test seeders in isolation. |
| UI breakage with real data | Low | "Stress test" UI with long tech descriptions in seed data. |
| Permission issues | Moderate | Seed distinct users for "Supervisor" vs "Staff" to test permissions thoroughly. |

## 🛠️ Technical Tasks (Detailed)

### Seeder Logic (Pseudo-code)
```php
// KewJobSeeder
Job::factory()->count(5)->create([
    'job_mode' => 'KEW_PA_10',
    'status' => 'PENDING_APPROVAL',
    'kew_findings' => 'Brake pads worn to 2mm...',
    'kew_recommendations' => 'Replace brake pads and rotor resurface.',
]);

Job::factory()->count(3)->create([
    'job_mode' => 'KEW_PA_10',
    'status' => 'INSPECTION_APPROVED',
])->each(function($job) {
    // Audit log entry
    JobStatusHistory::create([
        'job_id' => $job->id,
        'from_status' => 'PENDING_APPROVAL',
        'to_status' => 'INSPECTION_APPROVED',
        'user_id' => User::role('supervisor')->first()->id
    ]);
});
```

### Database Indexes
```sql
CREATE INDEX jobs_mode_status_idx ON workshop_jobs(job_mode, status);
CREATE INDEX jobs_kew_reg_idx ON workshop_jobs(kew_vehicle_registration);
```

---

## 🏁 Definition of Done
- Database can be refreshed (`php artisan migrate:fresh --seed`) to a fully populated, demo-ready state.
- All UI components display realistic data correctly.
- Performance is brisk (< 500ms loads).
- No console errors during demo flows.
