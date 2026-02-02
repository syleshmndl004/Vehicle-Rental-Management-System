<?php
/**
 * Delete Vehicle - DELETE Operation in CRUD
 * 
 * Purpose: Removes a vehicle from the database inventory
 * Security: Multiple layers - login required, POST only, CSRF protection
 * 
 * For Viva:
 * - This implements the 'D' (Delete) in CRUD operations
 * - Only accepts POST requests (not GET) for security
 * - Uses prepared statements to prevent SQL injection
 * - Cascading delete removes related bookings automatically (foreign key)
 */

// Include helper functions for security
require_once('../includes/functions.php');

// ADMIN-ONLY ACCESS
// Only administrators can delete vehicles from inventory
// Regular users cannot perform delete operations
require_admin();

// Only accept POST requests - prevents accidental deletions from URL clicks
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

// Include database connection
require_once('../config/db.php');

// Validate CSRF token to prevent unauthorized delete requests
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die('CSRF token validation failed!');
}

$vehicle_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$vehicle_id) {
    header("Location: index.php?error=Invalid vehicle ID.");
    exit();
}

$sql = "DELETE FROM vehicles WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vehicle_id);

if ($stmt->execute()) {
    $message = "Vehicle deleted successfully.";
} else {
    $message = "Error deleting vehicle.";
}

$stmt->close();
$conn->close();

header("Location: index.php?message=" . urlencode($message));
exit();
?>
