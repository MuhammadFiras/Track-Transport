<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SimulatorController;

Route::get('/', function () {
    return view('vin-scanner');
});

// US 3.2: Automated Electrical Test (Hardware Simulator)
Route::get('/simulator/{vin}', [SimulatorController::class, 'show'])->name('simulator.show');

// US 3.3: Real-time Anomaly Alert & Logging
Route::post('/simulator/submit', [SimulatorController::class, 'storeLogs'])->name('simulator.submit');

// US 3.4: Generate Digital QC Report
Route::get('/simulator/{vin}/report', [SimulatorController::class, 'generateReport'])->name('simulator.report');

// US 3.5: Finalize Test & Update Production Status
Route::post('/simulator/{vin}/finalize', [SimulatorController::class, 'finalizeTest'])->name('simulator.finalize');
