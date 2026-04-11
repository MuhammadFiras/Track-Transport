<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Identification QA Tool - US 3.1 VIN Scanner</title>
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
            --light-bg: #f3f4f6;
            --border: #d1d5db;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            line-height: 1.6;
        }

        .container {
            max-width: 550px;
            width: 100%;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 30px 25px;
            text-align: center;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .header p {
            font-size: 13px;
            opacity: 0.95;
            font-weight: 500;
        }

        .content {
            padding: 30px;
        }

        .input-section {
            margin-bottom: 25px;
        }

        .input-section label {
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: 600;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-group {
            display: flex;
            gap: 12px;
        }

        input[type="text"] {
            flex: 1;
            padding: 14px 16px;
            border: 2px solid var(--border);
            border-radius: 6px;
            font-size: 15px;
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            text-transform: uppercase;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        button {
            background: var(--secondary);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        button:hover {
            background: #1d4ed8;
            box-shadow: 0 8px 16px rgba(37, 99, 235, 0.3);
            transform: translateY(-2px);
        }

        button:active {
            transform: translateY(0);
        }

        button:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }

        .message {
            margin-top: 20px;
            padding: 16px;
            border-radius: 6px;
            font-size: 14px;
            display: none;
            animation: slideIn 0.3s ease;
        }

        .message.loading {
            background: #fef3c7;
            color: #92400e;
            border-left: 4px solid var(--warning);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .message.error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid var(--error);
            display: block;
        }

        .spinner {
            width: 18px;
            height: 18px;
            border: 3px solid rgba(146, 64, 14, 0.2);
            border-top-color: var(--warning);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        #result {
            margin-top: 25px;
            display: none;
            animation: slideIn 0.4s ease;
        }

        .result-card {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border: 2px solid var(--success);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
        }

        .result-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            color: var(--success);
            font-size: 16px;
            font-weight: 700;
        }

        .result-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }

        .result-item {
            background: white;
            padding: 12px;
            border-radius: 6px;
            border-left: 4px solid var(--secondary);
        }

        .result-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .result-value {
            font-size: 18px;
            color: var(--primary);
            font-weight: 700;
        }

        .result-ecu {
            background: white;
            padding: 14px;
            border-radius: 6px;
            border-left: 4px solid var(--success);
            text-align: center;
        }

        .result-ecu-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .result-ecu-status {
            font-size: 15px;
            color: var(--success);
            font-weight: 700;
        }

        .result-ecu-status::before {
            content: '✓ ';
            margin-right: 5px;
        }

        @media (max-width: 480px) {
            .content {
                padding: 20px;
            }

            .header {
                padding: 20px 15px;
            }

            .header h1 {
                font-size: 20px;
            }

            .input-group {
                flex-direction: column;
            }

            button {
                width: 100%;
            }

            .result-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚗 Vehicle Identification Scanner</h1>
            <p>QA Tool - US 3.1 VIN Barcode Scanning System</p>
        </div>

        <div class="content">
            <div class="input-section">
                <label for="vinInput">VIN Input (11-17 Characters)</label>
                <div class="input-group">
                    <input 
                        type="text" 
                        id="vinInput" 
                        placeholder="Enter VIN (e.g., 1HGCM82633A...)" 
                        minlength="11" 
                        maxlength="17"
                        autocomplete="off"
                    >
                    <button>Process Scan</button>
                </div>
            </div>

            <div id="loadingMessage" class="message loading" style="display: none;">
                <div class="spinner"></div>
                <span>Fetching vehicle data from NHTSA API...</span>
            </div>

            <div id="errorMessage" class="message error"></div>

            <div id="result">
                <div class="result-card">
                    <div class="result-header">✅ Vehicle Profile Successfully Loaded</div>
                    <div class="result-grid">
                        <div class="result-item">
                            <div class="result-label">Make</div>
                            <div class="result-value" id="makeName">-</div>
                        </div>
                        <div class="result-item">
                            <div class="result-label">Model</div>
                            <div class="result-value" id="modelName">-</div>
                        </div>
                    </div>
                    <div class="result-item" style="grid-column: 1 / -1;">
                        <div class="result-label">Model Year</div>
                        <div class="result-value" id="yearValue">-</div>
                    </div>
                    <div class="result-ecu" style="margin-top: 15px;">
                        <div class="result-ecu-label">ECU Status</div>
                        <div class="result-ecu-status">Ready for Electrical Test</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- MVC Architecture: Modularized JavaScript Files -->
    <!-- ============================================================ -->
    <!-- Model Layer: Handles data and API requests -->
    <script src="{{ asset('js/Model/VINModel.js') }}"></script>
    <script src="{{ asset('js/View/VINView.js') }}"></script>
    <script src="{{ asset('js/Controller/VINController.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>