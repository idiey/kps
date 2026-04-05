<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>KPS Peneroka Statement</title>
    <style>
        body {
            font-family: Helvetica, sans-serif;
            font-size: 12px;
            color: #1f2937;
            margin: 24px;
        }

        h1, h2 {
            margin: 0 0 8px;
        }

        p {
            margin: 0 0 6px;
        }

        .muted {
            color: #6b7280;
        }

        .section {
            margin-top: 20px;
        }

        .summary-table,
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td,
        .data-table th,
        .data-table td {
            border: 1px solid #d1d5db;
            padding: 8px;
            vertical-align: top;
        }

        .summary-table .label {
            width: 30%;
            font-weight: bold;
            background: #f9fafb;
        }

        .data-table th {
            background: #f3f4f6;
            text-align: left;
        }

        .allocation-item {
            margin-bottom: 4px;
        }
    </style>
</head>
<body>
    <div>
        <h1>KPS Peneroka Statement</h1>
        <p>{{ $site->name }} ({{ $site->code }})</p>
        <p class="muted">Generated {{ $generatedAt->format('Y-m-d H:i:s') }}</p>
    </div>

    <div class="section">
        <h2>Peneroka Details</h2>
        <table class="summary-table">
            <tr>
                <td class="label">Name</td>
                <td>{{ $peneroka->name }}</td>
            </tr>
            <tr>
                <td class="label">IC Number</td>
                <td>{{ $peneroka->ic_number ?: '-' }}</td>
            </tr>
            <tr>
                <td class="label">Phone</td>
                <td>{{ $peneroka->phone ?: '-' }}</td>
            </tr>
            <tr>
                <td class="label">Address</td>
                <td>{{ $peneroka->address ?: '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Summary</h2>
        <table class="summary-table">
            <tr>
                <td class="label">Debt Count</td>
                <td>{{ $summary['debt_count'] }}</td>
            </tr>
            <tr>
                <td class="label">Outstanding Balance</td>
                <td>{{ number_format((float) $summary['outstanding_balance'], 2) }}</td>
            </tr>
            <tr>
                <td class="label">Total Deductions</td>
                <td>{{ number_format((float) $summary['deduction_total'], 2) }}</td>
            </tr>
            <tr>
                <td class="label">Allocated Total</td>
                <td>{{ number_format((float) $summary['allocated_total'], 2) }}</td>
            </tr>
            <tr>
                <td class="label">Unallocated Total</td>
                <td>{{ number_format((float) $summary['unallocated_total'], 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <h2>Debts</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Priority</th>
                    <th>Original Amount</th>
                    <th>Balance</th>
                    <th>Due Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($peneroka->debts as $debt)
                    <tr>
                        <td>{{ $debt->description ?: $debt->id }}</td>
                        <td>{{ $debt->priority }}</td>
                        <td>{{ number_format((float) $debt->original_amount, 2) }}</td>
                        <td>{{ number_format((float) $debt->balance, 2) }}</td>
                        <td>{{ $debt->due_date?->format('Y-m-d') ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No debts found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Monthly Deductions</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Amount</th>
                    <th>Allocated</th>
                    <th>Unallocated</th>
                    <th>Status</th>
                    <th>Allocations</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($deductions as $deduction)
                    <tr>
                        <td>{{ $deduction->month?->format('Y-m-d') ?: $deduction->month }}</td>
                        <td>{{ number_format((float) $deduction->amount, 2) }}</td>
                        <td>{{ number_format((float) $deduction->allocations->sum('amount'), 2) }}</td>
                        <td>{{ number_format((float) $deduction->unallocated_amount, 2) }}</td>
                        <td>{{ $deduction->is_closed ? 'Closed' : 'Open' }}</td>
                        <td>
                            @forelse ($deduction->allocations as $allocation)
                                <div class="allocation-item">
                                    {{ $allocation->debt?->description ?: $allocation->debt_id }}:
                                    {{ number_format((float) $allocation->amount, 2) }}
                                </div>
                            @empty
                                <span>No allocations.</span>
                            @endforelse
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No deductions found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
