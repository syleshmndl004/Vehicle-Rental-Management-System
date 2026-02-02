<?php
/**
 * Database Configuration File
 * 
 * Purpose: Establishes connection to MySQL database for the Vehicle Rental Management System
 * This file is included in all pages that need database access
 * 
 * Security Note: In production, store credentials in environment variables
 * For Viva: Explain that this uses MySQLi (MySQL Improved) extension for better security
 */

// Database server details - Auto-detect environment
// Use server credentials on production, localhost for local development
$servername = "localhost";

// Server credentials (for deployment)
// Username: np02cs4s250016
// Password: xMztbMWI6Q
// Database: np02cs4s250016

// Auto-detect: Use server credentials if on remote server, otherwise localhost
$is_local = ($_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);

if ($is_local) {
    // Local development (XAMPP)
    $username = "root";
    $password = "";
    $dbname = "vehicle_rental_db";
} else {
    // Production server
    $username = "np02cs4s250016";
    $password = "xMztbMWI6Q";
    $dbname = "np02cs4s250016";
}

// Create new MySQLi connection object
// MySQLi provides prepared statements which prevent SQL injection attacks
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if connection was successful
// If connection fails, stop execution and show error message
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character encoding to UTF-8 to support international characters
// This prevents issues with special characters in vehicle names, etc.
$conn->set_charset("utf8mb4");
?>
