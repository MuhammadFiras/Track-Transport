/**
 * VINController Class
 * Acts as the intermediary between View and Model
 * Orchestrates user interactions, validation, and updates to the UI
 */
class VINController {
    constructor(model, view) {
        this.model = model;
        this.view = view;

        // Initialize event listeners
        this.initializeEventListeners();
    }

    /**
     * Sets up all event listeners for user interactions
     * Binds Process Scan button click and Enter key press
     */
    initializeEventListeners() {
        // Bind Process Scan button click
        this.view.onProcessScanClick(() => this.handleScanClick());

        // Bind Enter key press in VIN input field
        this.view.onVINInputEnter(() => this.handleScanClick());

        // Initialize auto-focus on page load
        this.view.initializeAutoFocus();
    }

    /**
     * Handles the scan button click or Enter key press
     * Orchestrates validation, API call, and view update
     */
    async handleScanClick() {
        // Clear previous results
        this.view.clearResults();

        // Read VIN from input field
        const vin = this.view.getVINInput();

        // Validate VIN format (11-17 characters)
        const validation = this.model.validateVIN(vin);

        if (!validation.isValid) {
            // Show validation error in alert
            this.view.showValidationError(validation.message);
            return;
        }

        // Show loading state while fetching from API
        this.view.showLoadingState();

        // Fetch vehicle data from NHTSA API
        const result = await this.model.fetchVehicleData(vin);

        // Handle the result
        if (result.success) {
            // Success: render the vehicle data in the results card
            this.view.renderResults(result.data);
            const resolvedVin = (result.data?.vin || vin).toUpperCase();
            window.location.href = `/simulator/${encodeURIComponent(resolvedVin)}`;
        } else {
            // Fallback: let backend hybrid flow handle DB-first + NHTSA lookup
            const resolvedVin = vin.toUpperCase();
            window.location.href = `/simulator/${encodeURIComponent(resolvedVin)}`;
        }
    }

    /**
     * Public method to reset the application to its initial state
     */
    resetApp() {
        this.model.clearData();
        this.view.reset();
    }
}
