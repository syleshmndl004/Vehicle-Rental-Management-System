<?php
/**
 * Ajax Date Availability Checker - Auto-check Available Dates Feature
 * 
 * Purpose: Real-time checking of vehicle availability for selected date range
 * Method: Ajax call from booking form (no page reload)
 * 
 * For Viva:
 * - This implements the AJAX 'auto-check available dates' requirement
 * - Prevents double-booking by detecting date conflicts in real-time
 * - Returns JSON response for smooth user experience
 * - Validates date formats and checks for overlapping bookings
 */

// Start session to access user data if needed
session_start();

// Set response type to JSON for Ajax communication
header('Content-Type: application/json');

// Include database connection
require_once('../config/db.php');

// INPUT VALIDATION - Security Requirement #1
// Validate vehicle ID as integer
$vehicle_id = filter_input(INPUT_GET, 'vehicle_id', FILTER_VALIDATE_INT);

// Sanitize date inputs to prevent XSS
$start_date = filter_input(INPUT_GET, 'start_date', FILTER_SANITIZE_STRING);
$end_date = filter_input(INPUT_GET, 'end_date', FILTER_SANITIZE_STRING);

if (!$vehicle_id || !$start_date || !$end_date) {
    echo json_encode(['available' => false, 'message' => 'Invalid input parameters.']);
    exit();
}

// Validate date format
$start = DateTime::createFromFormat('Y-m-d', $start_date);
$end = DateTime::createFromFormat('Y-m-d', $end_date);

if (!$start || !$end || $start->format('Y-m-d') !== $start_date || $end->format('Y-m-d') !== $end_date) {
    echo json_encode(['available' => false, 'message' => 'Invalid date format.']);
    exit();
}

// Check if end date is before start date
if ($end < $start) {
    echo json_encode(['available' => false, 'message' => 'End date must be on or after start date.']);
    exit();
}

// Check for overlapping bookings
$sql = "SELECT COUNT(*) as booking_count 
        FROM bookings 
        WHERE vehicle_id = ? 
        AND booking_status = 'Confirmed'
        AND (
            (start_date <= ? AND end_date >= ?) OR
            (start_date <= ? AND end_date >= ?) OR
            (start_date >= ? AND end_date <= ?)
        )";

$stmt = $conn->prepare($sql);
$stmt->bind_param("issssss", $vehicle_id, $start_date, $start_date, $end_date, $end_date, $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row['booking_count'] > 0) {
    echo json_encode([
        'available' => false, 
        'message' => 'Vehicle is already booked for the selected dates.'
    ]);
} else {
    echo json_encode([
        'available' => true, 
        'message' => 'Vehicle is available!'
    ]);
}

$stmt->close();
$conn->close();
?>
