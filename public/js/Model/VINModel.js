/**
 * VINModel Class
 * Manages the data layer: API requests, data parsing, and state management
 * Responsible for fetching vehicle data from NHTSA database
 */
class VINModel {
    constructor() {
        this.apiBaseUrl = 'https://vpic.nhtsa.dot.gov/api/vehicles/decodevin';
        this.vehicleData = null;
        this.isLoading = false;
        this.lastError = null;
    }

    /**
     * Validates VIN format (11-17 characters)
     * @param {string} vin - Vehicle Identification Number
     * @returns {object} - { isValid: boolean, message: string }
     */
    validateVIN(vin) {
        if (!vin || typeof vin !== 'string') {
            return {
                isValid: false,
                message: 'Invalid VIN format! VIN must be a non-empty string.'
            };
        }

        const cleanVin = vin.trim();

        if (cleanVin.length < 11) {
            return {
                isValid: false,
                message: '❌ Invalid VIN format! VIN must be at least 11 characters.'
            };
        }

        if (cleanVin.length > 17) {
            return {
                isValid: false,
                message: '❌ Invalid VIN format! VIN must not exceed 17 characters.'
            };
        }

        return {
            isValid: true,
            message: 'VIN format is valid.'
        };
    }

    /**
     * Fetches vehicle data from NHTSA API
     * @param {string} vin - Vehicle Identification Number
     * @returns {Promise<object>} - Vehicle data or error object
     */
    async fetchVehicleData(vin) {
        this.isLoading = true;
        this.lastError = null;

        try {
            const cleanVin = vin.trim().toUpperCase();

            // Construct API endpoint
            const url = `${this.apiBaseUrl}/${cleanVin}?format=json`;

            // Fetch from NHTSA API
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                },
                timeout: 5000
            });

            // Check response status
            if (!response.ok) {
                throw new Error(`API request failed with status ${response.status}`);
            }

            // Parse JSON response
            const data = await response.json();

            // Extract vehicle data from Results array (include VIN)
            const vehicleData = this.parseVehicleData(data, cleanVin);

            // Validate extracted data
            if (!vehicleData.make || !vehicleData.model) {
                throw new Error('Vehicle data not found in NHTSA database. Please verify the VIN and try again.');
            }

            // Store vehicle data
            this.vehicleData = vehicleData;
            this.isLoading = false;

            return {
                success: true,
                data: vehicleData,
                error: null
            };

        } catch (error) {
            this.isLoading = false;
            this.lastError = error.message;

            return {
                success: false,
                data: null,
                error: error.message || 'Failed to connect to NHTSA API. Please check your internet connection and try again.'
            };
        }
    }

    /**
     * Parses NHTSA API response and extracts specific vehicle data
     * Looks for: Make (VariableId: 26), Model (VariableId: 28), Year (VariableId: 29)
     * @param {object} apiResponse - Raw API response from NHTSA
     * @param {string} vin - The Vehicle Identification Number
     * @returns {object} - Extracted vehicle data including VIN
     */
    parseVehicleData(apiResponse, vin) {
        let make = null;
        let model = null;
        let year = null;

        // Validate response structure
        if (!apiResponse.Results || !Array.isArray(apiResponse.Results)) {
            return { vin, make, model, year };
        }

        // Iterate through Results array to extract specific VariableIds
        apiResponse.Results.forEach(item => {
            // VariableId 26: Make
            if (item.VariableId === 26 && item.Value) {
                make = item.Value;
            }

            // VariableId 28: Model
            if (item.VariableId === 28 && item.Value) {
                model = item.Value;
            }

            // VariableId 29: Model Year
            if (item.VariableId === 29 && item.Value) {
                year = item.Value;
            }
        });

        return {
            vin,
            make,
            model,
            year: year || 'Unknown'
        };
    }

    /**
     * Retrieves the last fetched vehicle data
     * @returns {object|null} - Vehicle data or null if not available
     */
    getVehicleData() {
        return this.vehicleData;
    }

    /**
     * Clears the stored vehicle data
     */
    clearData() {
        this.vehicleData = null;
        this.lastError = null;
    }

    /**
     * Gets the loading state
     * @returns {boolean} - True if currently loading
     */
    getLoadingState() {
        return this.isLoading;
    }

    /**
     * Gets the last error message
     * @returns {string|null} - Error message or null
     */
    getLastError() {
        return this.lastError;
    }
}
