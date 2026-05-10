# Site Operations

This chapter covers the daily site workflows for users with the required permissions. These tasks
usually belong to `site_admin` or higher admin roles.

## Before You Start

Confirm:

- You are in the correct site workspace.
- The site is active if your organization uses active status operationally.
- You can see the required menu: `Peneroka`, `Hutang`, or `Potongan Bulanan`.
- The month is not closed before entering or changing potongan.

## Manage Peneroka

Required permission: `kps.manage_peneroka`.

### View Peneroka

1. Open the selected site.
2. Select `Peneroka`.
3. Use search, sort, or pagination if available.
4. Select `Statement` to open a peneroka statement when report access is available.

### Add Peneroka

1. Open `Peneroka`.
2. Select `Add Peneroka`.
3. Enter the required `Name`.
4. Optionally enter `IC Number`, phone, and address.
5. Save the record.

`IC Number` must be unique within the site when provided.

### Edit Peneroka

1. Open `Peneroka`.
2. Select `Edit` for the peneroka.
3. Update name, IC number, phone, or address.
4. Save changes.

Use consistent IC and name details because potongan imports may match rows to existing peneroka
by IC number or name.

## Manage Hutang

Required permission: `kps.manage_hutang`.

### View Hutang

1. Open the selected site.
2. Select `Hutang`.
3. Review debt records, balances, priorities, due dates, and potongan limits.

### Add Hutang

1. Open `Hutang`.
2. Select `Add Hutang`.
3. Choose the peneroka.
4. Enter priority and balance.
5. Optionally enter monthly potongan limit, due date, and description.
6. Save the hutang.

Priority must be 1 or higher. Balance and monthly potongan limit must not be negative.

### Edit Hutang

1. Open `Hutang`.
2. Select `Edit` for the debt record.
3. Update priority, balance, monthly potongan limit, due date, or description.
4. Save changes.

Changing priority, balance, or potongan limit affects future allocation results. Recalculate open
deductions when changes should apply to existing open month data.

## Monthly Potongan (Potongan Bulanan)

Required permission: `kps.manage_potongan`.

### View The Potongan Ledger

1. Open the selected site.
2. Select `Potongan Bulanan`.
3. Select the month filter.
4. Use search, status, and sorting to review entries.
5. Select `View` to inspect allocation details for a deduction.

### Single Entry

1. Open `Potongan Bulanan`.
2. Select `Single Entry` or `Create Potongan`.
3. Choose the peneroka.
4. Enter the month in `YYYY-MM` format.
5. Enter an amount greater than 0.
6. Select `Save Potongan`.

Saving creates or updates the peneroka's deduction for that month, resets unallocated amount, and
recalculates allocations automatically.

### Bulk Entry

1. Open `Potongan Bulanan`.
2. Select `Bulk Entry`.
3. Choose the month.
4. Add one row per peneroka.
5. Enter each amount greater than 0.
6. Select `Save Bulk`.

Bulk save processes each valid row and recalculates allocations for each saved deduction.

### Excel Upload

Excel upload is available to users with `kps.manage_sites`.

1. Open `Potongan Bulanan`.
2. Select `Bulk Entry`.
3. Download the Excel template for the target month if needed.
4. Fill the supported columns:
   - `bil` or `no`
   - `nama_peneroka` or `peneroka_name`
   - `no_ic` or `ic_number`
   - `gaji`, `salary`, or `current_month_dividend`
5. Upload an `.xlsx` or `.xls` file up to 10 MB.
6. Confirm the success message.

The upload can create or update peneroka records and save current-month deductions. Empty rows are
ignored. Unsupported column names cause the upload to fail.

## Allocation Behavior

When KPS allocates potongan to hutang:

1. It applies the site's `hutang_weightage_pct`.
2. It pays debts with the lowest priority number first.
3. Within the same priority, it pays earlier due dates first.
4. Debts without due dates are paid after dated debts.
5. Within the same due date state, older records are paid first.
6. Any amount not allocated remains as `unallocated_amount`.

Partial payments are supported. A monthly potongan can be larger than the total debt balance.
