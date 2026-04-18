<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 15px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; font-size: 12px; color: #666; }
        .section { margin-bottom: 20px; }
        .section-title { font-weight: bold; font-size: 14px; background: #f0f0f0; padding: 8px; margin-bottom: 10px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px; }
        .info-item { font-size: 12px; }
        .info-label { font-weight: bold; color: #555; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; font-size: 12px; }
        th { background: #333; color: white; padding: 8px; text-align: left; }
        td { border-bottom: 1px solid #ddd; padding: 8px; }
        tr:nth-child(even) { background: #f9f9f9; }
        .decision { text-align: center; margin-top: 30px; padding: 20px; font-size: 18px; font-weight: bold; }
        .approved { color: green; border: 2px solid green; }
        .rejected { color: red; border: 2px solid red; }
    </style>
</head>
<body>
    <div class="header">
        <h1>⚡ Electrical Test QC Report</h1>
        <p>{{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Vehicle Information</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">VIN</div>
                <div>{{ $vehicle->vin_number }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Year</div>
                <div>{{ $vehicle->production_year }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Make</div>
                <div>{{ $vehicle->make }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Model</div>
                <div>{{ $vehicle->model }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Test Results</div>
        <table>
            <thead>
                <tr>
                    <th>Component</th>
                    <th>Voltage (V)</th>
                    <th>Status</th>
                    <th>Anomaly</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                <tr>
                    <td>{{ $log->component_name }}</td>
                    <td>{{ $log->recorded_voltage }}</td>
                    <td>{{ $log->status }}</td>
                    <td>{{ $log->is_anomaly ? 'Yes' : 'No' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Summary</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Total Tests</div>
                <div>{{ $total_tests }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Failed Tests</div>
                <div>{{ $failed_tests }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Passed Tests</div>
                <div>{{ $total_tests - $failed_tests }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Pass Rate</div>
                <div>{{ $total_tests > 0 ? round((($total_tests - $failed_tests) / $total_tests) * 100, 1) : 0 }}%</div>
            </div>
        </div>
    </div>

    <div class="decision {{ $final_decision === 'Approved' ? 'approved' : 'rejected' }}">
        ✓ FINAL DECISION: {{ strtoupper($final_decision) }}
    </div>
</body>
</html>
