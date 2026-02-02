/**
 * Search Autocomplete - Ajax Suggestions
 * 
 * Purpose: Provides real-time search suggestions as user types
 * Improves user experience with instant feedback
 * 
 * For Viva:
 * - Demonstrates Ajax functionality for form filling assistance
 * - Fetches suggestions from server without page reload
 * - Minimum 2 characters required to reduce server load
 * - Click suggestion to auto-fill search box
 */

// Wait for page to fully load
document.addEventListener('DOMContentLoaded', function() {
    // Get the search input field
    const searchInput = document.getElementById('keyword');
    
    // Check if we're on a page with search functionality
    if (!searchInput) {
        return; // Exit if search box doesn't exist
    }

    // Create suggestion panel dynamically (doesn't exist in HTML)
    const suggestionsPanel = document.createElement('div');
    suggestionsPanel.setAttribute('class', 'list-group position-absolute w-100');
    suggestionsPanel.style.zIndex = "1000"; // Appear above other elements
    searchInput.parentNode.appendChild(suggestionsPanel); // Add below search box

    // Listen for user typing in search box
    searchInput.addEventListener('input', function() {
        const term = searchInput.value; // Get current input value

        // Only search if user typed at least 2 characters (reduces server load)
        if (term.length < 2) {
            suggestionsPanel.innerHTML = ''; // Clear suggestions
            return;
        }

        // AJAX REQUEST - fetch suggestions from server
        // This is asynchronous - doesn't block the page
        fetch(`search.php?ajax=1&term=${encodeURIComponent(term)}`) // Encode for URL safety
            .then(response => response.json()) // Convert response to JSON
            .then(data => {
                // Clear previous suggestions
                suggestionsPanel.innerHTML = '';
                
                // Display new suggestions if any exist
                if (data.length > 0) {
                    data.forEach(suggestion => {
                        // Create clickable suggestion item
                        const item = document.createElement('a');
                        item.setAttribute('href', '#');
                        item.setAttribute('class', 'list-group-item list-group-item-action');
                        item.textContent = suggestion; // Display suggestion text
                        
                        // When user clicks suggestion, fill search box
                        item.addEventListener('click', function(e) {
                            e.preventDefault(); // Don't follow link
                            searchInput.value = suggestion; // Auto-fill search
                            suggestionsPanel.innerHTML = ''; // Clear suggestions
                        });
                        
                        suggestionsPanel.appendChild(item); // Add to panel
                    });
                }
            })
            .catch(error => {
                console.error('Error fetching suggestions:', error);
            });
    });

    document.addEventListener('click', function(e) {
        if (e.target !== searchInput) {
            suggestionsPanel.innerHTML = '';
        }
    });
});
