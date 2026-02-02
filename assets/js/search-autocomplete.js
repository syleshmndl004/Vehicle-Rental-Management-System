/**
 * SEARCH AUTOCOMPLETE - REAL-TIME SEARCH SUGGESTIONS
 * 
 * WHAT THIS DOES:
 * - Shows search suggestions as user types in search box
 * - Fetches matching vehicle names from server via AJAX
 * - User can click suggestion to auto-fill search field
 * - Minimum 2 characters before searching (reduces server load)
 * - Suggestions appear in dropdown below search box
 * 
 * HOW IT WORKS:
 * 1. User types at least 2 characters
 * 2. AJAX request sent to search.php?term=xyz&ajax=1
 * 3. Server returns matching vehicle names as JSON
 * 4. JavaScript displays suggestions in dropdown
 * 5. User clicks suggestion to select it
 * 6. Search field auto-fills with selection
 * 
 * BENEFITS:
 * - Users don't need to remember exact vehicle names
 * - Autocomplete saves typing time
 * - Discovers available vehicles while typing
 * - Reduces typos and search errors
 */

// Wait for entire page to load before running
// Ensures search input field exists
document.addEventListener('DOMContentLoaded', function() {
    // ===== GET SEARCH INPUT FIELD =====
    // Get the search box where user types vehicle names
    const searchInput = document.getElementById('keyword');
    
    // ===== CHECK IF ON SEARCH PAGE =====
    // Exit if search box doesn't exist (not on search page)
    if (!searchInput) {
        return; // Stop execution
    }

    // ===== CREATE SUGGESTIONS DROPDOWN =====
    // Create a div to hold autocomplete suggestions
    // This doesn't exist in HTML, created dynamically by JavaScript
    const suggestionsPanel = document.createElement('div');
    
    // Add Bootstrap CSS class for styling dropdown
    suggestionsPanel.setAttribute('class', 'list-group position-absolute w-100');
    
    // Set zIndex to 1000 so suggestions appear above other page elements
    suggestionsPanel.style.zIndex = "1000";
    
    // Insert the suggestions panel right after the search input
    // parentNode.appendChild() adds as child to parent element
    searchInput.parentNode.appendChild(suggestionsPanel);

    // ===== LISTEN FOR USER TYPING =====
    // 'input' event fires every time user types/deletes a character
    searchInput.addEventListener('input', function() {
        // Get current search term from input field
        const term = searchInput.value;

        // ===== MINIMUM CHARACTER CHECK =====
        // Only search if user typed at least 2 characters
        // Prevents too many requests on single character
        // Reduces server load significantly
        if (term.length < 2) {
            suggestionsPanel.innerHTML = ''; // Clear any previous suggestions
            return; // Don't search yet
        }

        // ===== AJAX REQUEST TO SERVER =====
        // Fetch suggestions from search.php
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
