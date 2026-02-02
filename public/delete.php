<?php
/**
 * DELETE VEHICLE PAGE - DELETE OPERATION
 * 
 * WHAT THIS DOES:
 * - Removes a vehicle from the database inventory
 * - Deletes all related bookings (cascading delete via foreign key)
 * - Redirects back to vehicle list with confirmation message
 * 
 * SECURITY FEATURES:
 * - Admin-only access (require_admin() function)
 * - POST-only operation (prevents accidental URL-based deletion)
 * - CSRF token validation (prevents malicious form submissions)
 * - Input validation (FILTER_VALIDATE_INT for ID)
 * - Prepared statements prevent SQL injection
 * 
 * WHY POST-ONLY:
 * - If this was GET-only, clicking a malicious link could delete vehicle
 * - POST requires form submission, which requires CSRF token
 * - Much safer and more intentional for destructive operations
 * 
 * PART OF CRUD:
 * - C (Create) = add.php
 * - R (Read) = index.php
 * - U (Update) = edit.php
 * - D (Delete) = THIS FILE
 */

// Include helper functions with security checks
require_once('../includes/functions.php');

// ===== ADMIN-ONLY ACCESS =====
// Only administrators can delete vehicles
// Regular users cannot perform any delete operations
require_admin();

// ===== POST-ONLY REQUEST CHECK =====
// This page only accepts POST requests, not GET
// Prevents accidental or malicious URL-based deletion
// If someone tries to access via GET (like a link), redirect to safety
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

// Include database connection
require_once('../config/db.php');

// ===== CSRF TOKEN VALIDATION =====
// CSRF (Cross-Site Request Forgery) tokens prevent unauthorized deletion
// Token must be in POST data AND match the one stored in session
// hash_equals() compares strings safely without timing attacks
if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die('CSRF token validation failed!');
}

// ===== VALIDATE VEHICLE ID =====
// Extract vehicle ID from POST data
// FILTER_VALIDATE_INT ensures only valid integers are accepted
// Prevents SQL injection by rejecting non-integer values
$vehicle_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if (!$vehicle_id) {
    header("Location: index.php?error=Invalid vehicle ID.");
    exit();
}

// ===== DELETE VEHICLE FROM DATABASE =====
// SQL query with placeholder (?) for safety
$sql = "DELETE FROM vehicles WHERE id = ?";

// Create prepared statement
$stmt = $conn->prepare($sql);

// Bind parameter: "i" = integer type
$stmt->bind_param("i", $vehicle_id);

// Execute the DELETE query
// If vehicle has related bookings, they are also deleted (cascading delete)
if ($stmt->execute()) {
    // Success message to show user
    $message = "Vehicle deleted successfully.";
} else {
    // Error message if deletion failed
    $message = "Error deleting vehicle.";
}

$stmt->close();
$conn->close();

// ===== REDIRECT WITH MESSAGE =====
// urlencode() properly encodes the message for URL
// Message displays to user in vehicle list
header("Location: index.php?message=" . urlencode($message));
exit();

$stmt->close();
$conn->close();

header("Location: index.php?message=" . urlencode($message));
exit();
?>
