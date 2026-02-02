<?php
/**
 * Main Dashboard - Vehicle Inventory Display (READ Operation)
 * 
 * Purpose: Displays all vehicles with real-time availability status
 * Security: Redirects to landing page if user not logged in
 * Uses: Twig template engine for separation of logic and presentation
 */

session_start();

// SESSION PROTECTION - Security Requirement #3
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Load Twig template engine
$twig = require_once('../includes/twig_init.php');
require_once('../config/db.php');

// Fetch all vehicles with real-time availability status
$sql = "
    SELECT 
        v.id, 
        v.plate_number, 
        v.model, 
        v.type, 
        v.daily_rate,
        CASE 
            WHEN EXISTS (
                SELECT 1 
                FROM bookings b
                WHERE b.vehicle_id = v.id 
                AND b.booking_status = 'Confirmed'
                AND CURDATE() BETWEEN b.start_date AND b.end_date
            ) THEN 'Rented'
            ELSE 'Available'
        END AS current_status
    FROM vehicles v
    ORDER BY v.id DESC
";
$result = $conn->query($sql);

// Convert result to array for Twig
$vehicles = [];
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $vehicles[] = $row;
    }
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Render template with Twig
echo $twig->render('index.twig', [
    'page_title' => 'Vehicle Inventory - VRM',
    'vehicles' => $vehicles,
    'message' => $_GET['message'] ?? null,
    'error' => $_GET['error'] ?? null,
]);

$conn->close();
?>
