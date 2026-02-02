/**
 * Date Availability Checker - Ajax Real-time Booking Conflict Detection
 * 
 * Purpose: Automatically checks if vehicle is available for selected dates
 * Method: Ajax call to server on every date change (no page reload)
 * 
 * For Viva - KEY REQUIREMENT:
 * - This implements the "Auto-check available dates" Ajax feature
 * - Prevents double-booking by detecting date conflicts in real-time
 * - Shows visual feedback (green=available, red=unavailable)
 * - Only enables booking button when dates are available
 * - Improves user experience with instant feedback
 */

// Wait for page to fully load before executing
document.addEventListener('DOMContentLoaded', function() {
    // Get references to all required HTML elements
    const startDateInput = document.getElementById('start_date'); // Start date picker
    const endDateInput = document.getElementById('end_date'); // End date picker
    const dateErrorDiv = document.getElementById('date-error'); // Error message area
    const submitButton = document.getElementById('submit-button'); // Booking button
    const availabilityStatus = document.getElementById('availability-status'); // Status message area
    
    // Verify we're on the booking page (defensive programming)
    if (!startDateInput || !endDateInput) {
        return; // Exit if required elements don't exist
    }

    // Get vehicle ID from URL parameter (which vehicle is being booked)
    const vehicleId = new URLSearchParams(window.location.search).get('id');
    
    // Get today's date in YYYY-MM-DD format
    const today = new Date().toISOString().split('T')[0];
    startDateInput.setAttribute('min', today); // Prevent selecting past dates

    /**
     * Main function - Checks vehicle availability for selected dates
     * Called automatically when user changes start or end date
     */
    function checkAvailability() {
        // Get current date values from form
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;

        // Don't check if either date is missing
        if (!startDate || !endDate) {
            submitButton.disabled = true; // Keep button disabled
            return;
        }

        // Client-side validation: Ensure end date is not before start date
        const start = new Date(startDate);
        const end = new Date(endDate);
        
        if (end < start) {
            submitButton.disabled = true;
            if (availabilityStatus) {
                availabilityStatus.style.display = 'none';
            }
            return; // Don't check availability for invalid date range
        }

        // Show loading indicator while checking
        if (availabilityStatus) {
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
