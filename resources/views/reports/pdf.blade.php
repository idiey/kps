<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header .period {
            font-size: 14px;
            color: #666;
        }
        .summary-box {
            background-color: #f5f5f5;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .summary-box h3 {
            margin-top: 0;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        .summary-item {
            padding: 10px;
            background: white;
            border-left: 3px solid #4CAF50;
        }
        .summary-item label {
            font-size: 11px;
            color: #666;
            display: block;
        }
        .summary-item value {
            font-size: 18px;
            font-weight: bold;
            display: block;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="period">Period: {{ $period }}</div>
        <div class="period">Generated: {{ $generated_at }}</div>
    </div>

    @if(isset($summary))
    <div class="summary-box">
        <h3>Summary</h3>
        <div class="summary-grid">
            @foreach($summary as $label => $value)
            <div class="summary-item">
                <label>{{ ucwords(str_replace('_', ' ', $label)) }}</label>
                <value>{{ $value }}</value>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(isset($jobs))
    <table>
        <thead>
            <tr>
                <th>Job No</th>
                <th>Customer</th>
                <th>Vehicle</th>
                <th>Status</th>
                <th>Job Mode</th>
                <th>Created Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($jobs as $job)
            <tr>
                <td>{{ $job->job_no ?? '-' }}</td>
                <td>{{ $job->customer->name ?? '-' }}</td>
                <td>{{ $job->vehicle->registration_no ?? '-' }}</td>
                <td>{{ ucfirst($job->status ?? '-') }}</td>
                <td>{{ $job->job_mode === 'kew_pa_10' ? 'KEW.PA-10' : 'Normal' }}</td>
                <td>{{ $job->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">No data available</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @endif

    @if(isset($customers))
    <table>
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Type</th>
                <th>Total Jobs</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customers as $customer)
            <tr>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->phone ?? '-' }}</td>
                <td>{{ ucfirst($customer->customer_type ?? '-') }}</td>
                <td>{{ $customer->jobs_count ?? 0 }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">No data available</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @endif

    @if(isset($metrics))
    <div class="summary-box">
        <h3>Performance Metrics</h3>
        <div class="summary-grid">
            @foreach($metrics as $label => $value)
            <div class="summary-item">
                <label>{{ ucwords(str_replace('_', ' ', $label)) }}</label>
                <value>{{ $value }}{{ in_array($label, ['completion_rate']) ? '%' : '' }}</value>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="footer">
        Workshop Management System - Generated on {{ now()->format('d/m/Y H:i:s') }}
    </div>
</body>
</html>
