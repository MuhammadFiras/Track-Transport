@php
/**
 * US 3.3 Integration Test: Anomaly Detection & Logging
 * Tests the complete flow: Submit test data → Validate bounds → Store logs
 */

$testVehicle = \App\Models\Vehicle::create([
    'vin_number' => 'TEST_US33_001',
    'make' => 'Honda',
    'model' => 'Civic',
    'production_year' => 2023,
    'production_status' => 'Testing In-Progress',
]);

echo "✅ Test vehicle created: " . $testVehicle->vin_number . "\n\n";

// Test payload (simulating simulator.blade.php submission)
$payload = [
    'vin_number' => 'TEST_US33_001',
    'components' => [
        ['component_name' => 'Headlight', 'recorded_voltage' => 13.0],      // PASS (11.5-14.5)
        ['component_name' => 'ABS Sensor', 'recorded_voltage' => 4.8],   // PASS (4.5-5.5)
        ['component_name' => 'Airbag Module', 'recorded_voltage' => 1.5], // FAIL (2.0-3.0)
    ],
];

echo "📊 Submission Payload:\n";
echo json_encode($payload, JSON_PRETTY_PRINT) . "\n\n";

// Simulate the controller logic
$vehicle = \App\Models\Vehicle::where('vin_number', $payload['vin_number'])->first();
$logs = [];

foreach ($payload['components'] as $component) {
    // Get bounds (from default bounds since no TestProfile in DB)
    $defaultBounds = [
        'Headlight' => ['min' => 11.5, 'max' => 14.5],
        'ABS Sensor' => ['min' => 4.5, 'max' => 5.5],
        'Airbag Module' => ['min' => 2.0, 'max' => 3.0],
    ];
    
    $bounds = $defaultBounds[$component['component_name']] ?? ['min' => 0, 'max' => 100];
    
    // Server-side anomaly evaluation
    $voltage = $component['recorded_voltage'];
    $isAnomaly = $voltage < $bounds['min'] || $voltage > $bounds['max'];
    
    $log = \App\Models\SensorLog::create([
        'vehicle_id' => $vehicle->id,
        'component_name' => $component['component_name'],
        'recorded_voltage' => $voltage,
        'is_anomaly' => $isAnomaly,
        'status' => $isAnomaly ? 'Fail' : 'Pass',
    ]);
    
    $logs[] = $log;
    
    echo sprintf(
        "📝 Logged: %s @ %.1fV | Bounds: [%.1f, %.1f] | Status: %s | Anomaly: %s\n",
        $component['component_name'],
        $voltage,
        $bounds['min'],
        $bounds['max'],
        $log->status,
        $isAnomaly ? 'YES' : 'NO'
    );
}

echo "\n✅ Test Complete! Logs created: " . count($logs);
echo "\n✅ Check database: SELECT * FROM sensor_logs WHERE vehicle_id = " . $vehicle->id . ";";
echo "\n✅ Check soft deletes: deleted_at column present: " . (\Schema::hasColumn('sensor_logs', 'deleted_at') ? 'YES' : 'NO') . "\n";
@endphp
