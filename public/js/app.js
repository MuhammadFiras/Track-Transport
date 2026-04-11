/**
 * Application Initialization
 * Bootstraps the MVC application with Model, View, and Controller instances
 */

// Wait for DOM to be fully loaded before initializing
document.addEventListener('DOMContentLoaded', () => {
    // Instantiate Model (data layer)
    const vinModel = new VINModel();

    // Instantiate View (presentation layer)
    const vinView = new VINView();

    // Instantiate Controller (business logic layer)
    const vinController = new VINController(vinModel, vinView);

    // Make controller globally available (optional, for debugging)
    window.vinController = vinController;

    console.log('VIN Scanning Application initialized successfully');
});
