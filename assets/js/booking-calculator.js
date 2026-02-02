/**
 * BOOKING CALCULATOR - REAL-TIME COST CALCULATION
 * 
 * WHAT THIS DOES:
 * - Automatically calculates total rental cost as user selects dates
 * - Formula: (Number of days) × (Daily rate) = Total cost
 * - Updates display instantly (no page reload needed - Ajax)
 * - Validates that end date is not before start date
 * - Works seamlessly with availability checker
 * 
 * WHY NO PAGE RELOAD:
 * - Instant feedback keeps users engaged
 * - User can try different dates and see prices immediately
 * - Better user experience than traditional form submission
 * - Reduces server load (calculations done in browser)
 * 
 * EXAMPLE CALCULATION:
 * - Daily rate: $50
 * - Start date: Feb 2 (Monday)
 * - End date: Feb 5 (Thursday)
 * - Rental period: 3 days
 * - Total cost: 3 × $50 = $150
 */

// Wait for entire page to load before running
// Ensures all HTML elements exist
document.addEventListener('DOMContentLoaded', function() {
    // ===== GET HTML ELEMENT REFERENCES =====
    // Get all the form fields and display areas we need
    const startDateInput = document.getElementById('start_date');  // Rental start date picker
    const endDateInput = document.getElementById('end_date');      // Rental end date picker
    const dailyRateEl = document.getElementById('daily-rate');     // Element showing daily price
    const totalCostSpan = document.getElementById('total-cost');   // Where total price displays
    const dateErrorDiv = document.getElementById('date-error');    // Error message area
    const submitButton = document.getElementById('submit-button'); // Booking button

    // ===== DEFENSIVE PROGRAMMING =====
    // If we're not on booking page (elements don't exist), exit gracefully
    if (!startDateInput || !endDateInput || !dailyRateEl) {
        return; // Stop execution
    }

    // ===== EXTRACT DAILY RATE =====
    // Get daily rate value from HTML element
    // parseFloat() converts text to decimal number
    // Example: "50.00" becomes 50.00
    const dailyRate = parseFloat(dailyRateEl.textContent);
    
    // ===== SET DATE CONSTRAINTS =====
    // Get today's date in YYYY-MM-DD format
    // Prevent selecting dates in the past
    const today = new Date().toISOString().split('T')[0];
    startDateInput.setAttribute('min', today); // Can't book for past dates

    /**
     * COST CALCULATION FUNCTION
     * Runs whenever user changes either date field
     * 
     * CALCULATION LOGIC:
     * 1. Get start and end dates from form
     * 2. Calculate number of days between dates
     * 3. Multiply days × daily rate = total cost
     * 4. Display result to user
     */
    function calculateCost() {
        // ===== CREATE DATE OBJECTS =====
        // Convert string dates to JavaScript Date objects
        // Allows date arithmetic and comparison
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
