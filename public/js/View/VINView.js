/**
 * VINView Class
 * Manages the presentation layer: DOM manipulation, UI rendering, and user input handling
 * Responsible for all visual updates and user interactions
 */
class VINView {
    constructor() {
        // Cache DOM elements
        this.vinInput = document.getElementById('vinInput');
        this.processScanButton = document.querySelector('button');
        this.loadingMessage = document.getElementById('loadingMessage');
        this.errorMessage = document.getElementById('errorMessage');
        this.resultDiv = document.getElementById('result');
        this.makeElement = document.getElementById('makeName');
        this.modelElement = document.getElementById('modelName');
        this.yearElement = document.getElementById('yearValue');
    }

    /**
     * Reads the VIN input value from the user
     * @returns {string} - The VIN value (trimmed and uppercase)
     */
    getVINInput() {
        return this.vinInput.value.trim().toUpperCase();
    }

    /**
     * Clears the VIN input field
     */
    clearVINInput() {
        this.vinInput.value = '';
    }

    /**
     * Sets focus to the VIN input field
     */
    focusVINInput() {
        this.vinInput.focus();
    }

    /**
     * Shows the loading state with spinner
     * Disables user interaction during API fetch
     */
    showLoadingState() {
        this.loadingMessage.style.display = 'flex';
        this.errorMessage.style.display = 'none';
        this.errorMessage.textContent = '';
        this.resultDiv.style.display = 'none';
        this.processScanButton.disabled = true;
    }

    /**
     * Hides the loading state and re-enables user interaction
     */
    hideLoadingState() {
        this.loadingMessage.style.display = 'none';
        this.processScanButton.disabled = false;
    }

    /**
     * Displays an error message to the user
     * @param {string} errorMessage - The error message to display
     */
    showError(errorMessage) {
        this.hideLoadingState();
        this.errorMessage.textContent = errorMessage;
        this.errorMessage.style.display = 'block';
        this.resultDiv.style.display = 'none';
    }

    /**
     * Displays validation error in a browser alert
     * @param {string} validationMessage - The validation error message
     */
    showValidationError(validationMessage) {
        alert(validationMessage);
        this.focusVINInput();
    }

    /**
     * Renders the vehicle data in the results card
     * @param {object} vehicleData - Object containing { make, model, year }
     */
    renderResults(vehicleData) {
        this.hideLoadingState();

        // Update DOM with vehicle data
        this.makeElement.textContent = vehicleData.make || '-';
        this.modelElement.textContent = vehicleData.model || '-';
        this.yearElement.textContent = vehicleData.year || 'Unknown';

        // Show results card
        this.resultDiv.style.display = 'block';

        // Clear error messages
        this.errorMessage.style.display = 'none';
        this.errorMessage.textContent = '';
    }

    /**
     * Clears the results display
     */
    clearResults() {
        this.resultDiv.style.display = 'none';
        this.errorMessage.style.display = 'none';
        this.errorMessage.textContent = '';
    }

    /**
     * Registers a callback for the Process Scan button click event
     * @param {function} callback - Function to call when button is clicked
     */
    onProcessScanClick(callback) {
        this.processScanButton.addEventListener('click', callback);
    }

    /**
     * Registers a callback for Enter key press in the VIN input field
     * @param {function} callback - Function to call when Enter is pressed
     */
    onVINInputEnter(callback) {
        this.vinInput.addEventListener('keypress', (event) => {
            if (event.key === 'Enter') {
                callback();
            }
        });
    }

    /**
     * Auto-focuses the input field on page load
     */
    initializeAutoFocus() {
        window.addEventListener('load', () => {
            this.focusVINInput();
        });
    }

    /**
     * Resets the view to initial state (clears everything)
     */
    reset() {
        this.clearVINInput();
        this.clearResults();
        this.hideLoadingState();
        this.focusVINInput();
    }
}
