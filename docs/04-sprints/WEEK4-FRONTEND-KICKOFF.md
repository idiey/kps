# 🎨 Week 4: Frontend Rebuild - Kickoff

> **Start Date**: February 3, 2026  
> **Duration**: 5 days  
> **Phase**: Frontend Migration (Vue Components)  
> **Status**: 🟡 **IN PROGRESS**

---

## 📋 Overview

Week 4 focuses on replacing dynamic form components with **static, mode-aware Vue components**. This removes the template rendering system and implements hardcoded forms for KEW.PA-10 and NORMAL job modes.

### Goals
- ✅ Create static Vue components for job forms
- ✅ Remove dynamic template rendering
- ✅ Update routing structure
- ✅ Implement mode-based form selection

---

## 🎯 Tasks Breakdown

### Day 1-2: Core Form Components

#### Task 1.1: Job Mode Selector ⏳ IN PROGRESS
- [ ] Create `SelectMode.vue` component
- [ ] Design card-based UI (KEW vs NORMAL)
- [ ] Add icons and descriptions
- [ ] Implement navigation to form routes
- [ ] Style with premium aesthetics

#### Task 1.2: KEW.PA-10 Form Component
- [ ] Create `CreateKewPa10.vue`
- [ ] Add vehicle information section
  - [ ] Vehicle Registration field
  - [ ] Asset Tag field
  - [ ] Department Name field
- [ ] Add inspection details section
  - [ ] Inspection Date field
  - [ ] Inspector Name field
  - [ ] Inspector IC field (14 chars max)
  - [ ] Findings textarea
  - [ ] Recommendations textarea
- [ ] Implement client-side validation
- [ ] Add error message display
- [ ] Style with Tailwind CSS
- [ ] Add help text for each field

#### Task 1.3: Normal Job Form Component
- [ ] Create `CreateNormal.vue`
- [ ] Add customer selection dropdown
- [ ] Add job title field
- [ ] Add description textarea
- [ ] Add priority selector
- [ ] Add estimated cost field
- [ ] Implement validation
- [ ] Style consistently with KEW form

### Day 3: Job Detail View Updates

#### Task 3.1: Update Show.vue
- [ ] Display KEW-specific fields conditionally
- [ ] Add approval buttons for KEW jobs (supervisor only)
- [ ] Show approval/rejection history
- [ ] Display mode-aware status badge
- [ ] Add status transition buttons (mode-aware)

#### Task 3.2: Create Approval Components  
- [ ] Create `KewApprovalPanel.vue`
- [ ] Create `KewApprovalHistory.vue`
- [ ] Style approval UI elements

### Day 4: Cleanup & Routing

#### Task 4.1: Remove Old Components
- [ ] Delete `CreateDynamic.vue`
- [ ] Delete `EditDynamic.vue`
- [ ] Delete `ShowDynamic.vue`
- [ ] Delete `SelectTemplate.vue`
- [ ] Delete `Components/dynamic-form/DynamicFormRenderer.vue`
- [ ] Delete `Components/workshop/DynamicJobForm.vue`
- [ ] Update all imports/references

#### Task 4.2: Update Routes
- [ ] Add route: `/jobs/select-mode`
- [ ] Add route: `/jobs/create/kew`
- [ ] Add route: `/jobs/create/normal`
- [ ] Add route: `/jobs/kew/{job}/approve`
- [ ] Add route: `/jobs/kew/{job}/reject`
- [ ] Remove old workflow routes

### Day 5: Testing & Polish

#### Task 5.1: Manual Testing
- [ ] Create KEW.PA-10 job - all fields required
- [ ] Submit KEW job with validation errors
- [ ] Submit KEW job successfully
- [ ] Create normal job successfully
- [ ] Test approval flow (supervisor)
- [ ] Test rejection flow with reason
- [ ] Test on mobile viewport (responsive)
- [ ] Test keyboard navigation
- [ ] Test screen reader accessibility

#### Task 5.2: UI/UX Polish
- [ ] Ensure consistent spacing
- [ ] Verify color palette alignment
- [ ] Add loading states
- [ ] Add success/error toasts
- [ ] Smooth transitions
- [ ] Micro-animations

---

## 🎨 Design Principles

### Visual Excellence
- **Modern UI**: Use shadcn/ui inspired design patterns
- **Rich Colors**: Use HSL-based color palette (avoid plain red/blue/green)
- **Typography**: Inter font family for clean, modern look
- **Spacing**: Consistent 8px grid system
- **Glassmorphism**: Subtle backdrop blur effects on cards

### User Experience
- **Guided Flow**: Clear job mode selection first
- **Progressive Disclosure**: Show relevant fields based on mode
- **Inline Validation**: Real-time feedback on field errors
- **Helpful Hints**: Contextual help text for each field
- **Keyboard Shortcuts**: Support Tab navigation and Enter to submit

### Code Quality
- **TypeScript**: Strongly typed props and emits
- **Composables**: Reusable form logic
- **Accessibility**: ARIA labels, semantic HTML
- **Performance**: Lazy load components where possible

---

## 📁 File Structure

```
resources/js/
├── Pages/
│   └── Jobs/
│       ├── SelectMode.vue          (NEW)
│       ├── CreateKewPa10.vue       (NEW)
│       ├── CreateNormal.vue        (NEW)
│       ├── Create.vue              (DEPRECATED - keep for now)
│       ├── Edit.vue                (UPDATE LATER)
│       ├── Show.vue                (UPDATE)
│       ├── CreateDynamic.vue       (DELETE)
│       ├── EditDynamic.vue         (DELETE)
│       ├── ShowDynamic.vue         (DELETE)
│       └── SelectTemplate.vue      (DELETE)
│
├── Components/
│   └── Workshop/
│       ├── KewApprovalPanel.vue    (NEW)
│       ├── KewApprovalHistory.vue  (NEW)
│       ├── JobModeBadge.vue        (NEW)
│       └── DynamicJobForm.vue      (DELETE)
```

---

## 🔗 Related Documentation

- [Sprint 7 Overview](./07-sprint-architecture-simplification.md)
- [Architecture Redesign TODO](./architecture-redesign-todo.md)
- [Simplified Job Modes](../../02-architecture/16-simplified-job-modes.md)
- [ERD Simplified](../../02-architecture/erd-simplified.md)

---

## 📊 Progress Tracker

| Task Category | Progress | Status |
|---------------|----------|--------|
| Job Mode Selector | 0% | 🔴 Not Started |
| KEW.PA-10 Form | 0% | 🔴 Not Started |
| Normal Form | 0% | 🔴 Not Started |
| Job Detail Updates | 0% | 🔴 Not Started |
| Component Cleanup | 0% | 🔴 Not Started |
| Route Updates | 0% | 🔴 Not Started |
| Testing | 0% | 🔴 Not Started |
| **Overall** | **0%** | 🟡 **IN PROGRESS** |

---

## 🚀 Getting Started

### Step 1: Review Backend Services
```bash
# Review the new services created in Week 2-3
cat app/Enums/JobMode.php
cat app/Services/JobStatusService.php
cat app/Services/KewPa10ValidationService.php
cat app/Services/KewPa10ApprovalService.php
```

### Step 2: Set Up Development Environment
```bash
# Install dependencies
npm install

# Run dev server
npm run dev

# In another terminal, start Laravel
php artisan serve
```

### Step 3: Create First Component
Start with `SelectMode.vue` as the entry point for the new job creation flow.

---

## 🎯 Success Criteria

- [ ] All new components created and functional
- [ ] Old dynamic components removed
- [ ] Routes updated and tested
- [ ] Forms validate correctly (client-side)
- [ ] KEW.PA-10 approval flow working
- [ ] Responsive on mobile devices
- [ ] Zero console errors
- [ ] Manual test report completed

---

**Status**: 🟡 IN PROGRESS  
**Current Task**: Creating SelectMode.vue component  
**Blockers**: None  
**Last Updated**: 2026-02-03 15:34
