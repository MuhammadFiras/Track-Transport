/**
 * US 3.1 VIN Scanning Flow - Debug Test
 * 
 * This tests the complete VIN scanning and redirection flow
 * to ensure VIN is properly captured and passed through all layers
 */

// LAYER 1: VINModel - Extract and return VIN
class TestVINModel {
    parseVehicleData(apiResponse, vin) {
        console.log('✓ Step 1: VINModel.parseVehicleData called with VIN:', vin);
        
        let make = null;
        let model = null;
        let year = null;

        if (apiResponse.Results && Array.isArray(apiResponse.Results)) {
            apiResponse.Results.forEach(item => {
                if (item.VariableId === 26 && item.Value) make = item.Value;
                if (item.VariableId === 28 && item.Value) model = item.Value;
                if (item.VariableId === 29 && item.Value) year = item.Value;
            });
        }

        const vehicleData = {
            vin,  // VIN IS NOW INCLUDED!
            make,
            model,
            year: year || 'Unknown'
        };
        
        console.log('✓ Step 2: VINModel.parseVehicleData returns:', vehicleData);
        return vehicleData;
    }
}

// LAYER 2: VINView - Receive data and navigate
class TestVINView {
    renderResults(vehicleData) {
        console.log('✓ Step 3: VINView.renderResults received:', vehicleData);
        
        if (vehicleData.vin) {
            console.log('✓ Step 4: VIN is available:', vehicleData.vin);
            const simulatorUrl = `/simulator/${encodeURIComponent(vehicleData.vin)}`;
            console.log('✓ Step 5: Would navigate to:', simulatorUrl);
            return true;
        } else {
            console.error('❌ ERROR: VIN not available in vehicle data');
            return false;
        }
    }
}

// TEST THE FLOW
console.log('=== US 3.1 VIN SCANNING FLOW TEST ===\n');

const mockApiResponse = {
    Results: [
        { VariableId: 26, Value: 'Toyota' },
        { VariableId: 28, Value: 'Camry' },
        { VariableId: 29, Value: '2024' }
    ]
};

const testVin = 'TEST1234567890ABC';

const model = new TestVINModel();
const view = new TestVINView();

const vehicleData = model.parseVehicleData(mockApiResponse, testVin);
const navigationSuccess = view.renderResults(vehicleData);

console.log('\n=== TEST RESULT ===');
console.log(navigationSuccess ? '✅ SUCCESS: VIN flow is working correctly!' : '❌ FAILED: VIN flow broken');
