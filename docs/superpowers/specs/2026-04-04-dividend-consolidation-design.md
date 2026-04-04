# Dividend Consolidation — Design Spec

**Date:** 2026-04-04
**Status:** Approved

---

## Overview

Every FELDA peneroka receives a monthly dividend from the cooperative based on their oil palm farm production (FFB — Fresh Fruit Bunches and oil palm bunches harvested). Before the dividend reaches the peneroka, the admin consolidates it: a configurable portion is used to pay hutang (debts), and the remainder is paid out to the peneroka.

KPS needs to:
1. Record the gross dividend per peneroka per month (single entry or bulk Excel upload)
2. Auto-distribute the dividend to hutang using the existing waterfall algorithm, capped at a configurable weightage percentage
3. Allow admin to manually adjust per-hutang amounts and skip debts
4. Show net amount peneroka receives
5. Lock dividends when the month is closed

---

## Business Rules

- **Dividend is variable** — different amount per peneroka per month, determined externally by production output
- **Hutang weightage cap** — total hutang payments cannot exceed `gross × weightage%` (default 40%, configurable per site)
- **Waterfall within cap** — debts paid in priority order (priority ASC, due_date ASC null last, created_at ASC) until cap is exhausted
- **Partial payment** — if cap only covers part of a debt, only that partial amount is deducted
- **Manual override** — admin can adjust any per-hutang amount, skip debts entirely; total Pay cannot exceed cap
- **Locked months** — once month is closed, dividends are locked and read-only

### Cap Example

```
Gross = RM800, weightage = 40% → cap = RM320

Hutang 1 (priority 1, balance RM300) → pay RM300 → cap remaining = RM20
Hutang 2 (priority 2, balance RM250) → pay RM20  → cap exhausted
Hutang 3 (priority 3, balance RM600) → pay RM0
Hutang 4 (priority 4, balance RM150) → pay RM0

Total hutang payments = RM320
Net peneroka receives  = RM480
```

---

## Data Model

### New: `dividends` table

| Column | Type | Notes |
|---|---|---|
| id | UUID | PK, HasUuids |
| site_id | UUID FK | references `sites.id` |
| peneroka_id | UUID FK | references `penerokas.id` |
| month | date | stored as first of month (e.g. 2026-04-01) |
| gross_amount | decimal(12,2) | oil palm production earnings |
| net_amount | decimal(12,2) | gross − total hutang payments |
| is_locked | boolean | default false; set true on month close |
| locked_at | timestamp | nullable |
| created_at / updated_at | timestamp | |

**Constraints:**
- Unique `(peneroka_id, month)` — one dividend per peneroka per month
- Soft deletes not applied — dividends are locked, not deleted

### Modified: `monthly_deductions`

Add column:
- `dividend_id` UUID FK nullable — references `dividends.id`

Links a monthly deduction (total hutang payment) to its source dividend. Existing rows unaffected (nullable).

### Modified: `sites`

Add column:
- `hutang_weightage_pct` decimal(5,2) — default 40.00

Configurable per site by HQ/company_admin. Does not retroactively affect locked months.

### Chain

```
dividends.gross_amount
  → monthly_deductions.amount        (total allocated to hutang, ≤ cap)
    → deduction_allocations          (per-hutang breakdown, existing table)
  → dividends.net_amount             (gross − monthly_deductions.amount)
```

---

## Consolidation Page

**Route:** `GET /kps/sites/{site}/dividends/{dividend}/consolidate`

**Layout:**

```
┌─────────────────────────────────────────────────────┐
│  Ahmad bin Ismail — April 2026                      │
├─────────────────────────────────────────────────────┤
│  Dividen Kasar        RM [    800.00    ]            │
│  Had Bayaran Hutang   RM  320.00  (40% had)         │
├─ Peruntukan Hutang ─────────────────────────────────┤
│  #  Hutang          Priority  Baki      Bayar (RM)  │
│  1  Baja subsidi    1         RM 300    [300.00] ✓  │
│  2  Racun rumput    2         RM 250    [ 20.00] ✓  │
│  3  Pinjaman mesin  3         RM 600    [  0.00] ⊘  │
│  4  Lain-lain       4         RM 150    [  0.00] ⊘  │
├─────────────────────────────────────────────────────┤
│  Had Hutang    ████████████░░░░░░░░  RM320 / RM320  │
│  Total Bayaran                        RM  320.00    │
│  Net Diterima Peneroka                RM  480.00    │
│                                         [Simpan]    │
└─────────────────────────────────────────────────────┘
```

**Behaviour:**
- On load: `DividendService::preview()` runs waterfall within cap → fills Bayar column
- Gross dividend field editable → changing it triggers AJAX re-preview and recalculates cap
- Admin can:
  - Edit any Bayar amount — net updates live
  - Toggle ⊘ to skip a debt (zeroes Bayar)
  - Redistribute freely within the cap
- **Validation (inline):**
  - Individual Bayar cannot exceed that hutang's remaining balance
  - Total Bayar cannot exceed `gross × weightage%`
- Progress bar shows total used vs cap
- [Simpan] — saves all records atomically

**Locked state:** When `dividend.is_locked = true`, page renders read-only with a "Bulan ditutup" banner.

---

## Bulk Upload

**Route:** `GET /kps/sites/{site}/dividends/template?month=2026-04` → downloads Excel
**Route:** `POST /kps/sites/{site}/dividends/import` → processes upload

### Excel Template

Generated from active peneroka list for the site:

| A | B | C | D |
|---|---|---|---|
| No. | No. IC | Nama Peneroka | Dividen Kasar (RM) |
| 1 | 701234-05-1234 | Ahmad bin Ismail | _(blank)_ |
| 2 | 680512-08-5678 | Rohani binti Ali | _(blank)_ |

- Admin fills column D only
- Rows with blank D are skipped
- Template always reflects current active peneroka list

### Import Validation

| Rule | Behaviour |
|---|---|
| IC not found in site | Rejected — error with row number |
| Amount not positive decimal | Rejected — error with row number |
| Duplicate peneroka in file | Rejected — error with row numbers |
| Peneroka already has dividend for month | Ask: overwrite or skip |
| Month is locked | Entire upload rejected |

### Import Result

```
Ringkasan Upload — April 2026
✅  42 rekod berjaya diimport
⚠️   3 dilangkau (amaun kosong)
❌   1 ralat: baris 18 — IC tidak dijumpai
```

After import: waterfall preview runs in background for all imported peneroka using site's `hutang_weightage_pct`. Admin opens individual peneroka to adjust if needed.

---

## Services

### `DividendService` (`app/Services/Kps/DividendService.php`)

| Method | Purpose |
|---|---|
| `computeCap(gross, Site): float` | Returns `gross × site.hutang_weightage_pct / 100` |
| `preview(Peneroka, month, gross, Site): array` | Runs waterfall within cap — returns allocations, not saved |
| `consolidate(Peneroka, month, gross, array $allocations, Site): Dividend` | Atomic: saves Dividend + MonthlyDeduction + DeductionAllocations, updates debt balances. Audit logged. |
| `generateTemplate(Site, month): BinaryFileResponse` | Excel with peneroka list |
| `bulkImport(Site, month, UploadedFile): array` | Validates + calls `consolidate()` per row. Returns summary. |

`AllocationService` is called inside `preview()` and `consolidate()` — no changes to existing service.

### Modified: `MonthlyClosingService::closeMonth()`

Additionally sets `dividends.is_locked = true`, `locked_at = now()` for all dividends in that site+month. Audit log: `action = 'dividends_locked'`, metadata: `{ month, count }`.

---

## Controllers & Routes

### `DividendController` (`app/Http/Controllers/Kps/DividendController.php`)

| Method | Route | Purpose |
|---|---|---|
| `index` | `GET /kps/sites/{site}/dividends` | List dividends by month |
| `create` | `GET /kps/sites/{site}/dividends/create` | Single entry form |
| `exportTemplate` | `GET /kps/sites/{site}/dividends/template` | Download Excel template |
| `import` | `POST /kps/sites/{site}/dividends/import` | Bulk upload |
| `preview` | `POST /kps/sites/{site}/dividends/preview` | AJAX waterfall preview |
| `consolidate` | `GET /kps/sites/{site}/dividends/{dividend}/consolidate` | Consolidation page |
| `store` | `POST /kps/sites/{site}/dividends/{dividend}/consolidate` | Save consolidation |

All routes under `EnsureKpsSiteContext` middleware. Permission: `kps.manage_potongan`.

### Vue Pages

- `Dividend/Index.vue` — month filter, list of peneroka with dividend status, download template, upload button
- `Dividend/Create.vue` — single peneroka dividend entry (gross amount input)
- `Dividend/Consolidate.vue` — main consolidation page

### Modified

- `SiteController` / Site Settings — add `hutang_weightage_pct` field (HQ/company_admin only)
- `routes/kps.php` — dividend routes under site-scoped group
- Site sidebar — add "Dividen" navigation item

---

## Audit Log

| Action | Trigger | Metadata |
|---|---|---|
| `dividend_created` | Single entry saved | `{ month, gross_amount, peneroka_id }` |
| `dividend_consolidated` | Consolidation saved | `{ month, gross_amount, net_amount, total_hutang, manual_adjustments: bool }` |
| `dividend_imported` | Bulk upload completed | `{ month, imported, skipped, errors }` |
| `dividends_locked` | Month closed | `{ month, count }` |

---

## Out of Scope

- Calculating dividend from production data (FFB kg, price/kg) — KPS receives final RM amount only
- Payment gateway / bank transfer
- Peneroka self-service portal
