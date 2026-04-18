<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\TestProfile;
use App\Models\SensorLog;
use App\Models\QcReport;
use Illuminate\Http\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;

class SimulatorController extends Controller
{
    /**
     * Display the hardware simulator for a specific vehicle.
     * 
     * This method retrieves vehicle data by VIN and injects mock test profile data
     * for the three electrical components being tested:
     * - Headlight (11.5V - 14.5V)
     * - ABS Sensor (4.5V - 5.5V)
     * - Airbag Module (2.0V - 3.0V)
     * 
     * @param string $vin The Vehicle Identification Number
     * @return \Illuminate\View\View Returns the simulator dashboard
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException If vehicle not found
     */
    public function show($vin)
    {
        $normalizedVin = strtoupper(trim($vin));

        // 1) Hybrid approach: check local DB first
        $vehicle = Vehicle::where('vin_number', $normalizedVin)->first();

        // 2) Fallback to NHTSA API and persist if vehicle is missing
        if (!$vehicle) {
            $decodedVehicle = $this->fetchVehicleFromNhtsa($normalizedVin);

            if (!$decodedVehicle) {
                abort(404, 'Vehicle data not found for this VIN.');
            }

            $vehicle = Vehicle::create([
                'vin_number' => $normalizedVin,
                'make' => $decodedVehicle['make'],
                'model' => $decodedVehicle['model'],
                'production_year' => $decodedVehicle['production_year'],
                'production_status' => 'Testing In-Progress',
            ]);
        }

        // Get test profile from DB by model; fallback to defaults when empty
        $profilesFromDb = TestProfile::where('vehicle_model', $vehicle->model)
            ->get(['component_name', 'min_voltage', 'max_voltage']);

        $testProfiles = $profilesFromDb->map(function ($profile) {
            return [
                'component_name' => $profile->component_name,
                'min_voltage' => (float) $profile->min_voltage,
                'max_voltage' => (float) $profile->max_voltage,
                'unit' => 'V',
                'description' => "{$profile->component_name} Electrical Check",
            ];
        })->values()->all();

        if (empty($testProfiles)) {
            $testProfiles = $this->getDefaultTestProfiles();
        }

        // Return the simulator view with vehicle and test profile data
        return view('simulator', [
            'vehicle' => $vehicle,
            'testProfiles' => $testProfiles,
        ]);
    }

    /**
     * Fetch and parse VIN information from NHTSA.
     *
     * @param string $vin
     * @return array|null
     */
    private function fetchVehicleFromNhtsa(string $vin): ?array
    {
        try {
            $response = Http::timeout(10)
                ->withoutVerifying() // Local dev workaround for SSL/cacert issues (cURL error 77)
                ->acceptJson()
                ->get("https://vpic.nhtsa.dot.gov/api/vehicles/decodevin/{$vin}", [
                    'format' => 'json',
                ]);
        } catch (RequestException $e) {
            return null;
        } catch (\Throwable $e) {
            return null;
        }

        if (!$response->ok()) {
            return null;
        }

        $results = $response->json('Results', []);

        if (!is_array($results) || empty($results)) {
            return null;
        }

        $make = null;
        $model = null;
        $year = null;

        foreach ($results as $item) {
            if (!is_array($item)) {
                continue;
            }

            if ((int) ($item['VariableId'] ?? 0) === 26 && !empty($item['Value'])) {
                $make = $item['Value'];
            }

            if ((int) ($item['VariableId'] ?? 0) === 28 && !empty($item['Value'])) {
                $model = $item['Value'];
            }

            if ((int) ($item['VariableId'] ?? 0) === 29 && !empty($item['Value'])) {
                $year = (int) $item['Value'];
            }
        }

        if (!$make || !$model) {
            return null;
        }

        return [
            'make' => $make,
            'model' => $model,
            'production_year' => $year ?: (int) date('Y'),
        ];
    }

    /**
     * Fallback profiles used when no model-specific profiles exist in DB.
     *
     * @return array<int, array<string, mixed>>
     */
    private function getDefaultTestProfiles(): array
    {
        return [
            [
                'component_name' => 'Headlight',
                'min_voltage' => 11.5,
                'max_voltage' => 14.5,
                'unit' => 'V',
                'description' => 'High Beam LED Module',
            ],
            [
                'component_name' => 'ABS Sensor',
                'min_voltage' => 4.5,
                'max_voltage' => 5.5,
                'unit' => 'V',
                'description' => 'Anti-Lock Braking System Sensor',
            ],
            [
                'component_name' => 'Airbag Module',
                'min_voltage' => 2.0,
                'max_voltage' => 3.0,
                'unit' => 'V',
                'description' => 'Supplemental Restraint System Control Unit',
            ],
        ];
    }

    /**
     * Store sensor logs and anomaly detection results.
     * 
     * Accepts test data, validates against database bounds, and stores results.
     * 
     * @param Request $request JSON payload with vin_number and components
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeLogs(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'vin_number' => 'required|string',
            'components' => 'required|array',
            'components.*.component_name' => 'required|string',
            'components.*.recorded_voltage' => 'required|numeric',
        ]);

        // Find vehicle by VIN
        $vehicle = Vehicle::where('vin_number', strtoupper($validated['vin_number']))->first();

        if (!$vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found.',
            ], 404);
        }

        // Process each component test result
        $savedLogs = [];

        foreach ($validated['components'] as $component) {
            $componentName = $component['component_name'];
            $recordedVoltage = $component['recorded_voltage'];

            // Get TestProfile bounds for this component
            $testProfile = TestProfile::where('vehicle_model', $vehicle->model)
                ->where('component_name', $componentName)
                ->first();

            // Fallback to hardcoded bounds if no profile in DB
            $bounds = $testProfile ? [
                'min' => $testProfile->min_voltage,
                'max' => $testProfile->max_voltage,
            ] : $this->getDefaultBounds($componentName);

            // Server-side anomaly evaluation
            $isAnomaly = $recordedVoltage < $bounds['min'] || $recordedVoltage > $bounds['max'];
            $status = $isAnomaly ? 'Fail' : 'Pass';

            // Create sensor log record
            $log = SensorLog::create([
                'vehicle_id' => $vehicle->id,
                'component_name' => $componentName,
                'recorded_voltage' => $recordedVoltage,
                'is_anomaly' => $isAnomaly,
                'status' => $status,
            ]);

            $savedLogs[] = $log;
        }

        return response()->json([
            'success' => true,
            'message' => 'Test results saved successfully.',
            'logs_count' => count($savedLogs),
            'vehicle_id' => $vehicle->id,
        ], 201);
    }

    /**
     * Get default voltage bounds for a component.
     * Fallback when TestProfile not in database.
     * 
     * @param string $componentName
     * @return array ['min' => float, 'max' => float]
     */
    private function getDefaultBounds($componentName)
    {
        $defaults = [
            'Headlight' => ['min' => 11.5, 'max' => 14.5],
            'ABS Sensor' => ['min' => 4.5, 'max' => 5.5],
            'Airbag Module' => ['min' => 2.0, 'max' => 3.0],
        ];

        return $defaults[$componentName] ?? ['min' => 0, 'max' => 100];
    }

    /**
     * Generate and download QC report PDF.
     * 
     * @param string $vin Vehicle Identification Number
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generateReport($vin)
    {
        $vehicle = Vehicle::where('vin_number', strtoupper($vin))->with('sensorLogs')->firstOrFail();
        $logs = $vehicle->sensorLogs;

        // Calculate test statistics
        $total_tests = $logs->count();
        $failed_tests = $logs->where('is_anomaly', true)->count();
        $final_decision = ($failed_tests == 0) ? 'Approved' : 'Rejected';

        // Store QC report in database
        $report = QcReport::create([
            'vehicle_id' => $vehicle->id,
            'supervisor_name' => 'Supervisor QA',
            'total_tests' => $total_tests,
            'failed_tests' => $failed_tests,
            'final_decision' => $final_decision,
            'report_file_url' => "/reports/QC_Report_{$vin}.pdf",
        ]);

        // Generate PDF
        $pdf = Pdf::loadView('report', compact('vehicle', 'logs', 'total_tests', 'failed_tests', 'final_decision'));
        return $pdf->download("QC_Report_{$vin}.pdf");
    }

    /**
     * Finalize test and update vehicle production status.
     * 
     * @param string $vin Vehicle Identification Number
     * @return \Illuminate\Http\JsonResponse
     */
    public function finalizeTest($vin)
    {
        $vehicle = Vehicle::where('vin_number', strtoupper($vin))->firstOrFail();
        
        // Count failed tests
        $failedCount = SensorLog::where('vehicle_id', $vehicle->id)
            ->where('status', 'Fail')
            ->count();

        // Update production status
        $vehicle->production_status = ($failedCount == 0) ? 'Ready for Delivery' : 'Rework';
        $vehicle->save();

        return response()->json([
            'success' => true,
            'message' => "Vehicle updated to: {$vehicle->production_status}",
            'report_url' => route('simulator.report', $vin),
        ]);
    }
}
