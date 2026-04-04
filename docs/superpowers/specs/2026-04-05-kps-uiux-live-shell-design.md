# KPS UIUX Live Shell Migration - Design Spec

**Date:** 2026-04-05
**Status:** Approved in conversation, pending file review

---

## Overview

KPS currently has two UI layers:

1. The live `/kps/...` shell used for production work
2. A separate preview-only shell under `/kps/preview/...`

The user wants the live KPS experience rebuilt using the preview shell as visual reference, with the preview color scheme adopted into production, and the preview interface removed entirely afterward.

The selected direction is **not** to promote the preview code directly. Instead, KPS will keep the current live routes and permission model, rebuild the production shell from scratch using the preview as design reference, then delete the preview routes, controller, pages, components, and middleware exceptions.

---

## Decision Summary

### Considered Approaches

1. **Promote the existing preview shell into production**
   - Fastest path
   - Lowest implementation churn
   - Rejected because the user explicitly chose not to promote the preview code directly

2. **Extract a shared `uiux` abstraction first, then migrate**
   - Cleaner intermediate naming
   - More reusable if multiple alternate shells are expected
   - Rejected because it adds an extra interface layer right before the preview interface is meant to be removed

3. **Rebuild the live shell from scratch using the preview as reference**
   - Keeps production routes and behavior stable
   - Avoids carrying preview-specific structure and naming into production
   - Allows deliberate adoption of the preview palette and layout patterns without preserving preview-only seams
   - **Selected**

---

## Goals

- Replace the current live KPS shell on `/kps/...` with a rebuilt production shell that adopts the preview visual language
- Use the preview color scheme as the production color system
- Keep all live route URLs, controllers, authorization rules, and site context behavior intact
- Remove the preview discovery/catalog experience and all preview-only routes and components
- Preserve current permission boundaries for HQ users, site users, and report access

## Non-Goals

- Keeping `/kps/preview` or `/kps/preview/stitch/...` as redirects or alternate entry points
- Shipping preview-specific labels such as "Preview Mode", "Optional UI", or "Current UI" into production
- Reusing the preview interface as runtime production architecture

---

## Architecture

### Route And Backend Model

- Live `/kps/...` routes remain the source of truth
- Existing live controllers continue to power dashboard, analytics, sites, allocations, reports, and audit workflows
- The preview controller and preview routes are removed after the production shell is rebuilt
- `EnsureKpsSiteContext` is simplified by removing the preview-route exception once no preview routes remain

### Production Shell Model

Create a new production shell for KPS rather than routing production traffic through `StitchPreviewLayout`.

The new shell will:

- apply the warm preview-derived palette and surface styling
- render a production HQ rail for global KPS navigation
- render a contextual site panel when a `site` prop exists
- render a production header/content frame around existing live page content
- continue to derive visibility and navigation state from live auth, permissions, and site context

This keeps the production boundary clear: live routes stay live, and preview artifacts do not become part of runtime architecture.

---

## Visual System

The rebuilt live shell adopts the preview's visual direction:

- warm editorial palette
- dark HQ rail
- soft layered cards and glass-like content framing
- strong heading hierarchy
- separate global and site-scoped navigation

The preview shell is the visual reference, not the runtime implementation. Production components should use neutral naming and production copy.

### Production Theme Direction

- Keep the current KPS warm/orange family, but shift it fully to the preview's softer editorial treatment
- Move shell color tokens into the new production shell so the theme is applied consistently across HQ rail, site panel, header, and content surfaces
- Remove preview-specific language from the interface while preserving the visual tone

---

## Component Design

### New Production Shell Components

Build a new production shell composition for live KPS pages with these production components:

- `KpsShellLayout.vue` rewritten as the new production shell entry point
- `KpsUiuxRail.vue` for global KPS navigation
- `KpsUiuxSitePanel.vue` for site-scoped navigation
- `KpsUiuxHeader.vue` for the production header/content frame

`StitchPreviewLayout.vue` is not reused as the production layout contract.

### Navigation Model

The navigation model stays aligned to live KPS routes.

HQ rail:

- `/kps/dashboard`
- `/kps/analytics`
- `/kps/sites`
- admin routes when permitted

Site context panel when `site` exists:

- site dashboard
- peneroka
- hutang
- potongan bulanan
- allocation review
- reports when permitted
- audit trail when permitted
- site settings when permitted

### Conditional Rendering Rules

- If no `site` is present, omit the site context panel entirely
- If a user lacks report permission, omit report links rather than rendering disabled placeholders
- If the user is site-scoped, preserve the current redirect and access behavior already enforced by middleware and policies

---

## Data Flow

The new shell consumes existing live data rather than introducing preview-specific payloads.

- auth and permission state continue to come from the current Inertia page props
- site context continues to come from the existing `site` and `siteRole` props already used by live KPS pages
- breadcrumbs and page slot content continue to flow through the existing `KpsShellLayout.vue` interface so live page imports do not need a route-level migration

Preview-only payloads such as preview workspace catalogs, preview view flags, and preview discovery content do not survive the migration.

---

## Preview Removal Scope

Delete the preview-only interface after the rebuilt live shell is wired in.

### Backend Cleanup

- remove preview routes from `routes/kps.php`
- delete `App\Http\Controllers\Kps\StitchPreviewController`
- remove preview-route handling from `EnsureKpsSiteContext`

### Frontend Cleanup

- delete pages under `resources/js/pages/Kps/Preview`
- delete preview components under `resources/js/components/kps/preview`
- delete `resources/js/layouts/kps/StitchPreviewLayout.vue`
- delete committed generated route/action files tied solely to preview routes and regenerate any generated artifacts so no preview route symbols remain in the codebase

### UX Cleanup

- remove preview discovery language such as "UI Options", "Preview Mode", "Optional UI Layer", and "Current UI"
- remove all live-to-preview comparison affordances because preview no longer exists as a separate surface

---

## Error Handling And Behavioral Guarantees

- Authorization rules do not change as part of the shell rebuild
- Rebuilding the shell must not widen access to HQ-only or report-only areas
- Site-scoped users should continue to land in their assigned site flow through the existing middleware behavior
- Removing preview routes must not leave dead links in the production shell
- If a page does not have site context, the shell should degrade cleanly to HQ-only navigation without rendering an empty second panel

---

## Testing Strategy

### Backend Feature Coverage

Replace preview-specific feature coverage with live-route coverage that proves the same permission boundaries still hold.

Tests should cover:

- HQ user can open live KPS dashboard routes under the rebuilt shell
- site user can open live site dashboard routes under the rebuilt shell
- report access remains forbidden for users without `kps.view_reports`
- report routes remain available for users with the correct permission
- deleted preview routes return `404`

### Frontend Verification

- run the frontend build to catch Vue, TypeScript, and bundling regressions
- verify the rebuilt layout composes correctly on both HQ and site-scoped pages
- verify navigation visibility matches permissions

### Regression Focus

- live route URLs remain unchanged
- site context behavior remains unchanged
- permission-gated links remain unchanged in meaning, even though the visual shell changes

---

## Execution Strategy

Implementation can be split across parallel workers once planning starts.

### Worker 1: Production Shell Rebuild

- build the new live shell components and layout
- wire live KPS pages to the rebuilt shell
- adopt the preview color system in production-safe component names

### Worker 2: Preview Removal And Test Migration

- remove preview routes, controller, layout, pages, and components
- update middleware cleanup tied to preview routes
- rewrite preview-focused tests into live-route regression coverage

### Main Thread

- integrate both change sets
- resolve shared layout/import conflicts
- run final verification across tests and frontend build

---

## Acceptance Criteria

- Live KPS routes render with the new rebuilt UIUX shell
- The live shell uses the preview-derived color system and layout language
- Preview routes and preview-only UI surfaces are deleted
- Middleware and navigation no longer reference preview routes
- Live authorization and site-context behavior are preserved
- Tests and build verification pass after the migration
