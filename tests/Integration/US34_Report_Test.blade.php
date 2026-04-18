@php
/**
 * US 3.4 Verification: PDF Report Generation
 * 
 * This demonstrates the complete flow:
 * 1. Fetch vehicle with sensor logs
 * 2. Calculate statistics
 * 3. Create QC report record
 * 4. Generate PDF (when accessed via route)
 */

// Use existing vehicle or create new one
$vehicle = \App\Models\Vehicle::updateOrCreate(
    ['vin_number' => 'TEST_US34_001'],
    ['make' => 'Honda', 'model' => 'Civic', 'production_year' => 2023, 'production_status' => 'Testing In-Progress']
);

echo "✅ Step 1: Vehicle loaded/created\n";
echo "   VIN: " . $vehicle->vin_number . "\n";
echo "   ID: " . $vehicle->id . "\n\n";

// Create sample sensor logs if none exist
if ($vehicle->sensorLogs->isEmpty()) {
    $entries = [
        ['component_name' => 'Headlight', 'recorded_voltage' => 13.0, 'is_anomaly' => false, 'status' => 'Pass'],
        ['component_name' => 'ABS Sensor', 'recorded_voltage' => 4.8, 'is_anomaly' => false, 'status' => 'Pass'],
        ['component_name' => 'Airbag Module', 'recorded_voltage' => 2.5, 'is_anomaly' => false, 'status' => 'Pass'],
    ];
    
    foreach ($entries as $entry) {
        \App\Models\SensorLog::create(array_merge(['vehicle_id' => $vehicle->id], $entry));
    }
}

$logs = $vehicle->sensorLogs;
echo "✅ Step 2: Sensor logs found\n";
echo "   Total logs: " . $logs->count() . "\n\n";

// Calculate statistics
$total_tests = $logs->count();
$failed_tests = $logs->where('is_anomaly', true)->count();
$final_decision = ($failed_tests == 0) ? 'Approved' : 'Rejected';

echo "✅ Step 3: Statistics calculated\n";
echo "   Total Tests: $total_tests\n";
echo "   Failed Tests: $failed_tests\n";
echo "   Final Decision: $final_decision\n\n";

// Create QC report
$report = \App\Models\QcReport::create([
    'vehicle_id' => $vehicle->id,
    'supervisor_name' => 'Supervisor QA',
    'total_tests' => $total_tests,
    'failed_tests' => $failed_tests,
    'final_decision' => $final_decision,
    'report_file_url' => "/reports/QC_Report_{$vehicle->vin_number}.pdf",
]);

echo "✅ Step 4: QC Report saved to database\n";
echo "   Report ID: " . $report->id . "\n";
echo "   Status: " . $report->final_decision . "\n\n";

echo "✅ READY: Access PDF at /simulator/TEST_US34_001/report\n";
echo "   This will generate and download the PDF while keeping the record in qc_reports table\n";
@endphp
