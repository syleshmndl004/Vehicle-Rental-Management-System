<?php
/**
 * Advanced Search Page - Multi-Criteria Search Implementation
 * Includes Ajax autocomplete handler
 */

// Ajax autocomplete handler
if (isset($_GET['term']) && isset($_GET['ajax'])) {
    require_once('../config/db.php');
    header('Content-Type: application/json');
    
    $searchTerm = trim($_GET['term']);
    if (empty($searchTerm)) {
        echo json_encode([]);
        exit();
    }
    
    $likeTerm = "%" . $searchTerm . "%";
    $sql = "SELECT DISTINCT model FROM vehicles WHERE model LIKE ? OR plate_number LIKE ? ORDER BY model ASC LIMIT 10";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $likeTerm, $likeTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $suggestions = [];
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row['model'];
    }
    
    $stmt->close();
    $conn->close();
    echo json_encode($suggestions);
    exit();
}

// Regular search page - Use Twig
$twig = require_once('../includes/twig_init.php');
require_once('../config/db.php');

// Get search parameters
$keyword = $_GET['keyword'] ?? '';
$type = $_GET['type'] ?? '';
$min_rate = $_GET['min_rate'] ?? '';
$max_rate = $_GET['max_rate'] ?? '';

// Build SQL query
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
