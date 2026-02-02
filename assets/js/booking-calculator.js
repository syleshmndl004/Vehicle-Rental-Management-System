/**
 * Booking Calculator - Real-time Cost Calculation
 * 
 * Purpose: Automatically calculates total rental cost based on selected dates
 * Updates in real-time as user changes dates (no page reload)
 * 
 * For Viva:
 * - Part of Ajax functionality for better user experience
 * - Calculates: (Number of days) × (Daily rate) = Total cost
 * - Prevents invalid date selections (end before start)
 * - Works together with availability checker
 */

// Wait for page to fully load before executing
document.addEventListener('DOMContentLoaded', function() {
    // Get references to HTML form elements
    const startDateInput = document.getElementById('start_date'); // Start date picker
    const endDateInput = document.getElementById('end_date'); // End date picker
    const dailyRateEl = document.getElementById('daily-rate'); // Vehicle's daily rate
    const totalCostSpan = document.getElementById('total-cost'); // Where total is displayed
    const dateErrorDiv = document.getElementById('date-error'); // Error message area
    const submitButton = document.getElementById('submit-button'); // Booking confirmation button

    // Check if all required elements exist (defensive programming)
    if (!startDateInput || !endDateInput || !dailyRateEl) {
        return; // Exit if not on booking page
    }

    // Extract daily rate value from the page (convert text to number)
    const dailyRate = parseFloat(dailyRateEl.textContent);
    
    // Get today's date and set as minimum selectable date
    const today = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD
    startDateInput.setAttribute('min', today); // User cannot select past dates

    // Main calculation function - runs when dates change
    function calculateCost() {
        // Create Date objects from user selections
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        // If either date is not selected, show $0.00 and disable submit
        if (!startDateInput.value || !endDateInput.value) {
            totalCostSpan.textContent = '$0.00';
            submitButton.disabled = true; // Prevent booking without dates
            return;
        }

        // Ensure end date is not before start date
        endDateInput.setAttribute('min', startDateInput.value);

        // Validate: End date must be same or after start date
        if (endDate < startDate) {
            dateErrorDiv.textContent = 'End date must be on or after the start date.';
            dateErrorDiv.style.display = 'block';
            totalCostSpan.textContent = '$0.00';
            submitButton.disabled = true;
            return;
        }

        // Calculate number of days between dates
        const timeDiff = endDate.getTime() - startDate.getTime(); // Difference in milliseconds
        const dayDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // Convert to days, +1 includes both dates

        // Calculate total cost and display
        if (dayDiff > 0) {
            const total = dayDiff * dailyRate; // Total = Days × Daily Rate
            totalCostSpan.textContent = '$' + total.toFixed(2); // Format as currency
            // Note: Submit button will be enabled by availability checker
        }
    }

    startDateInput.addEventListener('change', calculateCost);
    endDateInput.addEventListener('change', calculateCost);
});
