/**
 * DATE AVAILABILITY CHECKER - REAL-TIME BOOKING CONFLICT DETECTION
 * 
 * WHAT THIS DOES:
 * - Runs automatically when user selects booking dates
 * - Sends AJAX request to server checking if dates are available
 * - Shows loading spinner while checking
 * - Enables/disables submit button based on availability
 * - Displays green "Available" or red "Unavailable" message
 * - Prevents user from booking unavailable dates
 * 
 * WHY AJAX (No Page Reload):
 * - Instant feedback as user selects dates
 * - Much better user experience than page reloads
 * - Shows real-time availability without delays
 * - Allows users to try different dates quickly
 * 
 * SECURITY FEATURES:
 * - Validates dates on client-side first (prevent invalid requests)
 * - Server validates again (defense in depth)
 * - Prevents booking of past dates
 * - Date range validation (end date can't be before start)
 */

// Wait for entire page to load before running JavaScript
// Ensures HTML elements exist when we try to access them
document.addEventListener('DOMContentLoaded', function() {
    // ===== GET REFERENCES TO HTML ELEMENTS =====
    // Get the HTML input fields by their IDs
    // These are the date picker fields on the booking form
    const startDateInput = document.getElementById('start_date');  // When rental starts
    const endDateInput = document.getElementById('end_date');      // When rental ends
    const dateErrorDiv = document.getElementById('date-error');    // Error message display
    const submitButton = document.getElementById('submit-button'); // Submit booking button
    const availabilityStatus = document.getElementById('availability-status');  // Status message
    
    // ===== DEFENSIVE PROGRAMMING =====
    // If we're not on the booking page (elements don't exist), exit gracefully
    // Prevents errors if this script is included on other pages
    if (!startDateInput || !endDateInput) {
        return; // Stop execution
    }

    // ===== GET VEHICLE ID FROM URL =====
    // Extract vehicle ID from URL like: book.php?id=5
    // URLSearchParams is modern way to parse URL parameters
    const vehicleId = new URLSearchParams(window.location.search).get('id');
    
    // ===== SET MINIMUM DATE =====
    // Get today's date in YYYY-MM-DD format
    // toISOString() returns date in ISO format, split gets date part
    const today = new Date().toISOString().split('T')[0];
    
    // Prevent user from selecting dates in the past
    // This is client-side validation (server does this too)
    startDateInput.setAttribute('min', today);

    /**
     * MAIN FUNCTION - Check if vehicle is available for selected dates
     * Called automatically when user changes either date field
     */
    function checkAvailability() {
        // ===== GET CURRENT DATE VALUES =====
        // Read what dates user selected
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;

        // If either date is missing, can't check availability
        // User hasn't selected both dates yet
        if (!startDate || !endDate) {
            submitButton.disabled = true; // Keep button disabled until both dates selected
            return; // Stop execution
        }

        // ===== CLIENT-SIDE DATE VALIDATION =====
        // Ensure end date is not before start date
        // Create JavaScript Date objects for comparison
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        // If end date is before start date, invalid range
        if (end < start) {
            submitButton.disabled = true; // Disable submit
            if (availabilityStatus) {
                availabilityStatus.style.display = 'none';
            }
            return; // Don't send request to server
        }

        // ===== SHOW LOADING INDICATOR =====
        // Tell user we're checking availability
        if (availabilityStatus) {
            // Display loading spinner and message
            availabilityStatus.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div> Checking availability...';
            availabilityStatus.className = 'alert alert-info mt-3';
            availabilityStatus.style.display = 'block';
        }
        
        // Disable button while checking (prevents premature submission)
        submitButton.disabled = true;

        // AJAX REQUEST - Check availability on server
        // This is the key "auto-check available dates" feature
        fetch(`check_availability.php?vehicle_id=${vehicleId}&start_date=${startDate}&end_date=${endDate}`)
            .then(response => response.json()) // Parse JSON response
            .then(data => {
                // Handle server response
                if (data.available) {
                    // SUCCESS: Vehicle is available
                    if (availabilityStatus) {
                        availabilityStatus.innerHTML = '✓ Vehicle is available for selected dates!';
                        availabilityStatus.className = 'alert alert-success mt-3';
                        availabilityStatus.style.display = 'block';
                    }
                    dateErrorDiv.style.display = 'none'; // Hide any previous errors
                    submitButton.disabled = false; // ENABLE booking button
                } else {
                    // CONFLICT: Vehicle already booked for these dates
                    if (availabilityStatus) {
                        availabilityStatus.innerHTML = '✗ ' + (data.message || 'Vehicle is not available for selected dates.');
                        availabilityStatus.className = 'alert alert-danger mt-3';
                        availabilityStatus.style.display = 'block';
                    }
                    submitButton.disabled = true; // KEEP button disabled
                }
            })
            .catch(error => {
                // Handle network or server errors
                console.error('Error checking availability:', error);
                if (availabilityStatus) {
                    availabilityStatus.innerHTML = '⚠ Error checking availability. Please try again.';
                    availabilityStatus.className = 'alert alert-warning mt-3';
                    availabilityStatus.style.display = 'block';
                }
                submitButton.disabled = true; // Disable on error for safety
            });
    }

    startDateInput.addEventListener('change', checkAvailability);
    endDateInput.addEventListener('change', checkAvailability);
});
