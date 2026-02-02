<?php
/**
 * ADVANCED SEARCH PAGE - MULTI-CRITERIA VEHICLE SEARCH
 * 
 * WHAT THIS DOES:
 * - Provides search form with multiple filter options
 * - Implements AJAX autocomplete for vehicle names
 * - Searches vehicles by:
 *   1. Keyword (vehicle model or plate number)
 *   2. Vehicle type (Car, Bike, Scooter)
 *   3. Price range (minimum and maximum daily rate)
 * - Displays current availability status of each vehicle
 * - Handles two modes:
 *   a) AJAX mode: Returns JSON suggestions for autocomplete
 *   b) Search mode: Returns HTML search results page
 * 
 * SECURITY FEATURES:
 * - Prepared statements prevent SQL injection
 * - Input filtering (LIKE parameters properly escaped)
 * - JSON headers for AJAX responses
 * - htmlspecialchars() on all output
 */

// ===== AJAX AUTOCOMPLETE HANDLER =====
// When user types in search box, JavaScript sends AJAX request
// This code returns matching vehicle names as JSON suggestions
if (isset($_GET['term']) && isset($_GET['ajax'])) {
    // Include database connection
    require_once('../config/db.php');
    
    // Tell browser this is JSON data, not HTML
    header('Content-Type: application/json');
    
    // Get search term from URL parameter and clean it
    // trim() removes spaces from beginning and end
    $searchTerm = trim($_GET['term']);
    
    // If search is empty, return empty array
    if (empty($searchTerm)) {
        echo json_encode([]);
        exit();
    }
    
    // ===== SEARCH QUERY =====
    // Create LIKE pattern for partial matching
    // '%' wildcard matches any characters before/after search term
    // Example: search "toy" matches "Toyota", "Toys", etc.
    $likeTerm = "%" . $searchTerm . "%";
    
    // SQL query with placeholders prevents SQL injection
    // DISTINCT prevents duplicate results if multiple vehicles have same model
    // LIMIT 10 returns only 10 results for autocomplete dropdown
    $sql = "SELECT DISTINCT model FROM vehicles WHERE model LIKE ? OR plate_number LIKE ? ORDER BY model ASC LIMIT 10";
    
    // Create prepared statement
    $stmt = $conn->prepare($sql);
    
    // Bind two parameters: "ss" = two strings (searchTerm twice)
    // First ? is for model, second ? is for plate_number
    $stmt->bind_param("ss", $likeTerm, $likeTerm);
    
    // Execute query
    $stmt->execute();
    
    // Get results
    $result = $stmt->get_result();
    
    // Build array of suggestions
    $suggestions = [];
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row['model'];
    }
    
    // Close database connections
    $stmt->close();
    $conn->close();
    
    // Convert array to JSON and send to browser
    // JavaScript receives this and shows in autocomplete dropdown
    echo json_encode($suggestions);
    exit();
}

// ===== REGULAR SEARCH PAGE =====
// This section displays the search page with results

// Initialize Twig template engine for clean HTML output
$twig = require_once('../includes/twig_init.php');

// Include database connection
require_once('../config/db.php');

// ===== GET SEARCH PARAMETERS =====
// Retrieve search filters from URL query string
// ?? operator returns empty string if parameter not provided
$keyword = $_GET['keyword'] ?? '';      // Search by model or plate number
$type = $_GET['type'] ?? '';            // Filter by vehicle type
$min_rate = $_GET['min_rate'] ?? '';    // Minimum daily rental rate
$max_rate = $_GET['max_rate'] ?? '';    // Maximum daily rental rate

// ===== BUILD DYNAMIC SQL QUERY =====
// Start with basic SELECT statement
// CASE statement checks if vehicle is currently booked
$sql = "SELECT id, plate_number, model, type, daily_rate, 
        CASE 
            WHEN EXISTS (SELECT 1 FROM bookings b WHERE b.vehicle_id = v.id AND b.booking_status = 'Confirmed' AND CURDATE() BETWEEN b.start_date AND b.end_date) 
            THEN 'Rented'
            ELSE 'Available'
        END AS current_status
        FROM vehicles v WHERE 1=1";

$params = [];
$types = '';

if (!empty($keyword)) {
    $sql .= " AND (plate_number LIKE ? OR model LIKE ?)";
    $keyword_param = "%" . $keyword . "%";
    $params[] = $keyword_param;
    $params[] = $keyword_param;
    $types .= 'ss';
}

if (!empty($type)) {
    $sql .= " AND type = ?";
    $params[] = $type;
    $types .= 's';
}

if (is_numeric($min_rate) && $min_rate >= 0) {
    $sql .= " AND daily_rate >= ?";
    $params[] = $min_rate;
    $types .= 'd';
}

if (is_numeric($max_rate) && $max_rate > 0) {
    $sql .= " AND daily_rate <= ?";
    $params[] = $max_rate;
    $types .= 'd';
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);
if ($stmt && count($params) > 0) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Convert results to array for Twig
$vehicles = [];
while ($row = $result->fetch_assoc()) {
    $vehicles[] = $row;
}

// Render with Twig
echo $twig->render('search.twig', [
    'page_title' => 'Search Vehicles - VRM',
    'keyword' => htmlspecialchars($keyword),
    'type' => $type,
    'min_rate' => htmlspecialchars($min_rate),
    'max_rate' => htmlspecialchars($max_rate),
    'vehicles' => $vehicles,
    'results_count' => count($vehicles),
]);

$stmt->close();
$conn->close();
?>
