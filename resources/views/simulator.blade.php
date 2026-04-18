<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Electrical Test Simulator - US 3.2</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #1e3a5f;
            --secondary: #2563eb;
            --success: #10b981;
            --warning: #f59e0b;
            --error: #ef4444;
            --info: #06b6d4;
            --light-bg: #f3f4f6;
            --border: #d1d5db;
            --dark-text: #1f2937;
            --card-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            line-height: 1.6;
        }

        .simulator-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* ====== HEADER SECTION ====== */
        .simulator-header {
            background: white;
            border-radius: 12px 12px 0 0;
            padding: 30px;
            box-shadow: var(--card-shadow);
            margin-bottom: 0;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .header-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--primary);
        }

        .header-title .icon {
            margin-right: 10px;
        }

        .vehicle-info {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
        }

        .vehicle-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 15px;
        }

        .vehicle-info-item {
            display: flex;
            flex-direction: column;
        }

        .vehicle-info-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            opacity: 0.9;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .vehicle-info-value {
            font-size: 18px;
            font-weight: 700;
        }

        .vin-display {
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
        }

        /* ====== GLOBAL STATUS BANNER ====== */
        .global-status {
            background: var(--light-bg);
            border-left: 5px solid transparent;
            padding: 20px 30px;
            margin: 0;
            border-radius: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: var(--transition);
            font-size: 16px;
            font-weight: 600;
            color: var(--dark-text);
        }

        .global-status.normal {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-left-color: var(--success);
            color: var(--success);
        }

        .global-status.normal::before {
            content: '✓ ';
            margin-right: 10px;
            font-weight: 700;
        }

        .global-status.testing {
            background: linear-gradient(135deg, #ecf0ff 0%, #dbeafe 100%);
            border-left-color: var(--info);
            color: var(--info);
        }

        .global-status.testing::before {
            content: '⚙ ';
            margin-right: 10px;
            animation: spin 2s linear infinite;
        }

        .global-status.anomaly {
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
            border-left-color: var(--error);
            color: var(--error);
        }

        .global-status.anomaly::before {
            content: '⚠ ';
            margin-right: 10px;
            font-weight: 700;
            animation: pulse 1s infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .status-timestamp {
            font-size: 12px;
            opacity: 0.7;
        }

        /* ====== CONTENT SECTION ====== */
        .simulator-content {
            background: white;
            padding: 30px;
            box-shadow: var(--card-shadow);
            margin-bottom: 30px;
        }

        .content-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* ====== COMPONENT CARDS ====== */
        .components-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .component-card {
            background: white;
            border: 2px solid var(--border);
            border-radius: 10px;
            padding: 25px;
            transition: var(--transition);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .component-card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .component-card.pass {
            border-color: var(--success);
            background: linear-gradient(135deg, #f0fdf4 0%, rgba(16, 185, 129, 0.05) 100%);
        }

        .component-card.warning {
            border-color: var(--warning);
            background: linear-gradient(135deg, #fef3c7 0%, rgba(245, 158, 11, 0.05) 100%);
        }

        .component-card.fail {
            border-color: var(--error);
            background: linear-gradient(135deg, #fef2f2 0%, rgba(239, 68, 68, 0.05) 100%);
        }

        .component-header {
            margin-bottom: 20px;
        }

        .component-name {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-text);
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .component-status-badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 20px;
            letter-spacing: 0.5px;
        }

        .component-status-badge.pass {
            background: var(--success);
            color: white;
        }

        .component-status-badge.warning {
            background: var(--warning);
            color: white;
        }

        .component-status-badge.fail {
            background: var(--error);
            color: white;
        }

        .component-description {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 15px;
        }

        .safe-range {
            background: rgba(37, 99, 235, 0.1);
            padding: 12px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            color: var(--secondary);
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .safe-range-label {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.8;
        }

        .safe-range-value {
            font-family: 'Courier New', monospace;
            font-size: 15px;
            font-weight: 700;
        }

        /* ====== SLIDER SECTION ====== */
        .slider-group {
            margin-bottom: 20px;
        }

        .slider-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--primary);
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            display: block;
        }

        input[type="range"] {
            width: 100%;
            height: 8px;
            border-radius: 5px;
            background: linear-gradient(to right, #e5e7eb 0%, #e5e7eb 100%);
            outline: none;
            -webkit-appearance: none;
            appearance: none;
            cursor: pointer;
            transition: var(--transition);
        }

        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--secondary);
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
            transition: var(--transition);
        }

        input[type="range"]::-webkit-slider-thumb:hover {
            background: #1d4ed8;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.5);
            transform: scale(1.1);
        }

        input[type="range"]::-moz-range-thumb {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--secondary);
            cursor: pointer;
            border: none;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
            transition: var(--transition);
        }

        input[type="range"]::-moz-range-thumb:hover {
            background: #1d4ed8;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.5);
            transform: scale(1.1);
        }

        .slider-value-display {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 12px;
            padding: 12px;
            background: var(--light-bg);
            border-radius: 6px;
        }

        .slider-value-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            color: #6b7280;
            letter-spacing: 0.5px;
        }

        .slider-value {
            font-family: 'Courier New', monospace;
            font-size: 16px;
            font-weight: 700;
            color: var(--secondary);
        }

        .component-card.warning .slider-value {
            color: var(--warning);
        }

        .component-card.fail .slider-value {
            color: var(--error);
        }

        /* ====== ACTIONS SECTION ====== */
        .actions-section {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
        }

        button {
            padding: 14px 28px;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-submit {
            background: var(--secondary);
            color: white;
        }

        .btn-submit:hover:not(:disabled) {
            background: #1d4ed8;
            box-shadow: 0 8px 16px rgba(37, 99, 235, 0.3);
            transform: translateY(-2px);
        }

        .btn-submit:active:not(:disabled) {
            transform: translateY(0);
        }

        .btn-submit:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }

        .btn-reset {
            background: var(--light-bg);
            color: var(--dark-text);
            border: 2px solid var(--border);
        }

        .btn-reset:hover {
            background: var(--border);
            border-color: var(--primary);
        }

        .btn-finalize {
            background: var(--success);
            color: white;
        }

        .btn-finalize:hover:not(:disabled) {
            background: #059669;
            box-shadow: 0 8px 16px rgba(16, 185, 129, 0.3);
            transform: translateY(-2px);
        }

        .btn-finalize:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }

        /* ====== FOOTER ====== */
        .simulator-footer {
            background: white;
            border-radius: 0 0 12px 12px;
            padding: 20px 30px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            box-shadow: var(--card-shadow);
        }

        /* ====== RESPONSIVE ====== */
        @media (max-width: 768px) {
            .simulator-header,
            .simulator-content,
            .simulator-footer {
                padding: 20px;
            }

            .header-top {
                flex-direction: column;
                gap: 15px;
            }

            .vehicle-info-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .components-grid {
                grid-template-columns: 1fr;
            }

            .actions-section {
                flex-direction: column;
            }

            button {
                width: 100%;
            }

            .header-title {
                font-size: 22px;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 10px;
            }

            .simulator-header,
            .simulator-content,
            .simulator-footer {
                padding: 15px;
                border-radius: 8px;
            }

            .vehicle-info-grid {
                grid-template-columns: 1fr;
            }

            .header-title {
                font-size: 18px;
            }

            .component-card {
                padding: 15px;
            }

            .safe-range {
                flex-direction: column;
                gap: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="simulator-container">
        <!-- HEADER SECTION -->
        <div class="simulator-header">
            <div class="header-top">
                <h1 class="header-title">
                    <span class="icon">⚡</span>Electrical Test Simulator
                </h1>
            </div>

            <div class="vehicle-info">
                <div style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.9; margin-bottom: 10px;">Vehicle Profile</div>
                <div class="vehicle-info-grid">
                    <div class="vehicle-info-item">
                        <span class="vehicle-info-label">Make</span>
                        <span class="vehicle-info-value">{{ $vehicle->make }}</span>
                    </div>
                    <div class="vehicle-info-item">
                        <span class="vehicle-info-label">Model</span>
                        <span class="vehicle-info-value">{{ $vehicle->model }}</span>
                    </div>
                    <div class="vehicle-info-item">
                        <span class="vehicle-info-label">Year</span>
                        <span class="vehicle-info-value">{{ $vehicle->production_year }}</span>
                    </div>
                    <div class="vehicle-info-item">
                        <span class="vehicle-info-label">VIN</span>
                        <span class="vehicle-info-value vin-display">{{ $vehicle->vin_number }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- GLOBAL STATUS BANNER -->
        <div id="globalStatus" class="global-status normal">
            <span id="statusText">Status: NORMAL - Testing In Progress</span>
            <span class="status-timestamp" id="statusTimestamp"></span>
        </div>

        <!-- CONTENT SECTION -->
        <div class="simulator-content">
            <h2 class="content-title">🔌 Component Voltage Analysis</h2>

            <!-- COMPONENTS GRID -->
            <div class="components-grid">
                @foreach ($testProfiles as $profile)
                <div class="component-card pass" data-component="{{ $profile['component_name'] }}" data-min="{{ $profile['min_voltage'] }}" data-max="{{ $profile['max_voltage'] }}">
                    <div class="component-header">
                        <div class="component-name">
                            {{ $profile['component_name'] }}
                            <span class="component-status-badge pass" data-badge="{{ $profile['component_name'] }}">Pass</span>
                        </div>
                        <div class="component-description">
                            {{ $profile['description'] }}
                        </div>
                    </div>

                    <div class="safe-range">
                        <span class="safe-range-label">Safe Range</span>
                        <span class="safe-range-value">{{ $profile['min_voltage'] }}{{ $profile['unit'] }} - {{ $profile['max_voltage'] }}{{ $profile['unit'] }}</span>
                    </div>

                    <div class="slider-group">
                        <label class="slider-label">Voltage ({{ $profile['unit'] }})</label>
                        @php
                            // Calculate slider range: extend by 30% of the safe range width
                            $rangeWidth = $profile['max_voltage'] - $profile['min_voltage'];
                            $extension = $rangeWidth * 0.30;
                            $sliderMin = $profile['min_voltage'] - $extension;
                            $sliderMax = $profile['max_voltage'] + $extension;
                            $initialValue = ($profile['min_voltage'] + $profile['max_voltage']) / 2;
                        @endphp
                        <input 
                            type="range" 
                            class="component-slider"
                            data-component="{{ $profile['component_name'] }}"
                            min="{{ round($sliderMin, 2) }}" 
                            max="{{ round($sliderMax, 2) }}" 
                            step="0.1"
                            value="{{ round($initialValue, 2) }}"
                        >

                        <div class="slider-value-display">
                            <span class="slider-value-label">Current Value</span>
                            <span class="slider-value" data-value="{{ $profile['component_name'] }}">{{ round($initialValue, 2) }} {{ $profile['unit'] }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- ACTIONS SECTION -->
            <div class="actions-section">
                <button class="btn-reset" onclick="resetSimulator()">Reset Simulation</button>
                <button class="btn-submit" id="submitBtn" onclick="submitTestData()">Submit Test Data</button>
                <button class="btn-finalize" id="finalizeBtn" onclick="finalizeAndReport()" style="display:none;">Finalize & Download Report</button>
            </div>
        </div>

        <!-- FOOTER SECTION -->
        <div class="simulator-footer">
            US 3.2: Automated Electrical Test (Hardware Simulator Interface) | Vehicle Status: {{ $vehicle->production_status }}
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- VANILLA JAVASCRIPT: Real-time Interactivity & US 3.3 Foundation -->
    <!-- ============================================================ -->
    <script>
        /**
         * Simulator State Management
         * Tracks the current state of all component sliders and validates bounds
         */
        class SimulatorState {
            constructor() {
                this.components = {};
                this.hasAnomalies = false;
                this.initializeComponents();
            }

            /**
             * Initialize component state from the DOM
             */
            initializeComponents() {
                document.querySelectorAll('.component-card').forEach(card => {
                    const componentName = card.dataset.component;
                    const minVoltage = parseFloat(card.dataset.min);
                    const maxVoltage = parseFloat(card.dataset.max);

                    this.components[componentName] = {
                        minVoltage,
                        maxVoltage,
                        currentValue: 0,
                        isAnomaly: false,
                    };
                });
            }

            /**
             * Update a component's voltage value and check for anomalies
             * @param {string} componentName
             * @param {number} value
             */
            updateComponent(componentName, value) {
                if (!this.components[componentName]) return;

                this.components[componentName].currentValue = value;

                // Check if value is strictly outside safe bounds
                const { minVoltage, maxVoltage } = this.components[componentName];
                const isAnomaly = value < minVoltage || value > maxVoltage;
                this.components[componentName].isAnomaly = isAnomaly;

                // Recalculate global anomaly state
                this.hasAnomalies = Object.values(this.components).some(c => c.isAnomaly);
            }

            /**
             * Get the current global anomaly state
             */
            getHasAnomalies() {
                return this.hasAnomalies;
            }

            /**
             * Reset all components to their initial state
             */
            reset() {
                Object.keys(this.components).forEach(key => {
                    this.components[key].currentValue = 0;
                    this.components[key].isAnomaly = false;
                });
                this.hasAnomalies = false;
            }
        }

        // Initialize simulator state
        const simulatorState = new SimulatorState();

        /**
         * Initialize event listeners for all range sliders
         * Triggered on page load
         */
        function initializeSliders() {
            document.querySelectorAll('.component-slider').forEach(slider => {
                // Initialize value display
                const componentName = slider.dataset.component;
                const valueDisplay = document.querySelector(`[data-value="${componentName}"]`);
                const initialValue = parseFloat(slider.value);
                valueDisplay.textContent = `${initialValue.toFixed(2)} V`;
                simulatorState.updateComponent(componentName, initialValue);

                // Add change listener for real-time updates
                slider.addEventListener('input', (event) => {
                    const value = parseFloat(event.target.value);
                    const containerName = event.target.dataset.component;

                    // Update the displayed value
                    const display = document.querySelector(`[data-value="${containerName}"]`);
                    display.textContent = `${value.toFixed(2)} V`;

                    // Update state and validate bounds
                    simulatorState.updateComponent(containerName, value);

                    // Update UI based on validation
                    updateComponentCardStyle(containerName);
                    updateGlobalStatus();
                });
            });
        }

        /**
         * Update component card styling based on voltage bounds
         * @param {string} componentName
         */
        function updateComponentCardStyle(componentName) {
            const card = document.querySelector(`[data-component="${componentName}"]`);
            const component = simulatorState.components[componentName];
            const badge = document.querySelector(`[data-badge="${componentName}"]`);

            // Remove all status classes
            card.classList.remove('pass', 'warning', 'fail');
            badge.classList.remove('pass', 'warning', 'fail');

            if (component.isAnomaly) {
                card.classList.add('fail');
                badge.classList.add('fail');
                badge.textContent = 'FAIL';
            } else {
                card.classList.add('pass');
                badge.classList.add('pass');
                badge.textContent = 'PASS';
            }
        }

        /**
         * Update global status banner
         * Changes color and text based on whether anomalies exist
         */
        function updateGlobalStatus() {
            const statusBanner = document.getElementById('globalStatus');
            const statusText = document.getElementById('statusText');
            const hasAnomalies = simulatorState.getHasAnomalies();

            statusBanner.classList.remove('normal', 'testing', 'anomaly');

            if (hasAnomalies) {
                statusBanner.classList.add('anomaly');
                statusText.textContent = 'ANOMALI TERDETEKSI: Uji Dihentikan!';
            } else {
                statusBanner.classList.add('normal');
                statusText.textContent = 'Status: NORMAL - Testing In Progress';
            }

            updateTimestamp();
        }

        /**
         * Update the status banner timestamp with current time
         */
        function updateTimestamp() {
            const timestamp = document.getElementById('statusTimestamp');
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
            });
            timestamp.textContent = `Last Update: ${timeString}`;
        }

        /**
         * Reset the simulator to initial state
         */
        function resetSimulator() {
            // Reset all sliders to initial values (midpoint of safe range)
            document.querySelectorAll('.component-slider').forEach(slider => {
                const componentName = slider.dataset.component;
                const card = document.querySelector(`[data-component="${componentName}"]`);
                const minVoltage = parseFloat(card.dataset.min);
                const maxVoltage = parseFloat(card.dataset.max);
                const initialValue = (minVoltage + maxVoltage) / 2;

                slider.value = initialValue;
                const display = document.querySelector(`[data-value="${componentName}"]`);
                display.textContent = `${initialValue.toFixed(2)} V`;

                simulatorState.updateComponent(componentName, initialValue);
                updateComponentCardStyle(componentName);
            });

            simulatorState.reset();
            updateGlobalStatus();
        }

        /**
         * Submit test data (placeholder for future US 3.3 integration)
         */
        /**
         * Submit test data to backend for processing and storage
         */
        function submitTestData() {
            const submitBtn = document.getElementById('submitBtn');
            const hasAnomalies = simulatorState.getHasAnomalies();

            if (hasAnomalies) {
                alert('⚠️ Cannot submit test data: Anomalies detected!\n\nPlease review the failed components and adjust voltage readings to within safe bounds.');
                return;
            }

            // Disable button to prevent duplicate submissions
            submitBtn.disabled = true;
            submitBtn.textContent = '⏳ Submitting...';

            // Prepare test data payload
            const payload = {
                vin_number: '{{ $vehicle->vin_number }}',
                components: Object.entries(simulatorState.components).map(([name, state]) => ({
                    component_name: name,
                    recorded_voltage: state.currentValue,
                })),
            };

            // Send to backend
            fetch('/simulator/submit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Test results saved successfully!\n\n' + data.message + '\nLogs saved: ' + data.logs_count);
                    submitBtn.textContent = '✓ Submitted';
                    document.getElementById('finalizeBtn').style.display = 'block';
                } else {
                    alert('❌ Error: ' + data.message);
                    submitBtn.textContent = 'Submit Test Data';
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Submission error:', error);
                alert('❌ Network error: Could not submit test data.');
                submitBtn.textContent = 'Submit Test Data';
                submitBtn.disabled = false;
            })
            .finally(() => {
                setTimeout(() => {
                    if (submitBtn.textContent === '✓ Submitted') {
                        submitBtn.textContent = 'Submit Test Data';
                        submitBtn.disabled = false;
                    }
                }, 3000);
            });
        }

        /**
         * Finalize test and update production status, then download report
         */
        function finalizeAndReport() {
            const finalizeBtn = document.getElementById('finalizeBtn');
            finalizeBtn.disabled = true;
            finalizeBtn.textContent = '⏳ Finalizing...';

            fetch('/simulator/{{ $vehicle->vin_number }}/finalize', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'Accept': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('✅ Vehicle Status Updated!\n\n' + data.message + '\n\nDownloading QC Report...');
                    window.location.href = data.report_url;
                } else {
                    alert('❌ Error: ' + data.message);
                    finalizeBtn.disabled = false;
                    finalizeBtn.textContent = 'Finalize & Download Report';
                }
            })
            .catch(error => {
                console.error('Finalize error:', error);
                alert('❌ Network error: Could not finalize test.');
                finalizeBtn.disabled = false;
                finalizeBtn.textContent = 'Finalize & Download Report';
            });
        }

        /**
         * Initialize on page load
         */
        window.addEventListener('DOMContentLoaded', () => {
            initializeSliders();
            updateGlobalStatus();
        });
    </script>
</body>
</html>
