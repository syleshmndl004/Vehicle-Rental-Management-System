<?php
/**
 * DATABASE CONFIGURATION FILE
 * 
 * This file handles the connection to the MySQL database
 * It is included in every page that needs to read or write data from the database
 * 
 * WHAT THIS DOES:
 * - Establishes connection to vehicle rental database
 * - Automatically detects if running on local computer or web server
 * - Uses appropriate credentials based on the environment
 * - Implements security by using MySQLi extension with prepared statements
 */

// Define the database server location (where MySQL is running)
$servername = "localhost";

// Server credentials for production/web server deployment
// These will be used when the application is running on the actual web server
$servername = "localhost";

// Check if this is running on local computer (like XAMPP) or on web server
// strpos() searches for substring; if found returns position, if not returns false
$is_local = ($_SERVER['HTTP_HOST'] === 'localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false);

// Use different database credentials based on environment
if ($is_local) {
    // For local development using XAMPP:
    // - Username is 'root' (default XAMPP admin)
    // - Password is empty (default XAMPP)
    // - Database name is 'vehicle_rental_db'
    $username = "root";
    $password = "";
    $dbname = "vehicle_rental_db";
} else {
    // For production/live server:
    // - Use actual web hosting credentials
    // - Different database name on web server
    $username = "np02cs4s250016";
    $password = "xMztbMWI6Q";
    $dbname = "np02cs4s250016";
}

// Create database connection using MySQLi
// MySQLi is better than old MySQL because:
// 1. Supports prepared statements (prevents SQL injection attacks)
// 2. Supports multiple statements
// 3. Enhanced database functionality
// 4. Object-oriented interface
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if connection failed
// If there's an error connecting to database, show error and stop execution
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character encoding to UTF-8MB4
// This allows the database to store special characters properly
// For example: Nepali characters, emoji, multiple languages
// UTF-8MB4 is the modern standard that supports all Unicode characters
$conn->set_charset("utf8mb4");
?>
