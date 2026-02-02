<?php
/**
 * Helper Functions - Security Enhanced
 */

if (session_status() == PHP_SESSION_NONE) {
    session_start();
    
    // Security: Set secure session parameters
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS
}

/**
 * Require login to access page
 * Security Feature: Session-based authentication
 */
function require_login() {
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['flash_message'] = "You must be logged in to access that page.";
        header("Location: login.php");
        exit();
    }
}

/**
 * Sanitize input to prevent XSS
 * Security Feature: Input filtering
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

/**
 * Validate CSRF token
 * Security Feature: CSRF protection
 */
function validate_csrf_token($token) {
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        die('CSRF token validation failed. Possible security breach detected.');
    }
}

/**
 * Generate CSRF token
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Escape output for display
 * Security Feature: Output escaping
 */
function escape_output($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email format
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Validate numeric input
 */
function validate_number($number, $min = null, $max = null) {
    if (!is_numeric($number)) {
        return false;
    }
    if ($min !== null && $number < $min) {
        return false;
    }
    if ($max !== null && $number > $max) {
        return false;
    }
    return true;
}

/**
 * Log security events
 */
function log_security_event($event, $details = '') {
    $log_file = '../logs/security.log';
    $timestamp = date('Y-m-d H:i:s');
    $user_id = $_SESSION['user_id'] ?? 'anonymous';
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $message = "[$timestamp] User: $user_id | IP: $ip | Event: $event | Details: $details\n";
    
    if (!file_exists('../logs')) {
        mkdir('../logs', 0755, true);
    }
    
    file_put_contents($log_file, $message, FILE_APPEND);
}

/**
 * Require user to be an admin
 * Restricts access to admin-only operations (Add, Edit, Delete)
 * 
 * For Viva:
 * - Implements role-based access control
 * - Regular users can only view and book vehicles
 * - Only admin can manage (CRUD operations on) vehicle inventory
 */
function require_admin() {
    require_login(); // Must be logged in first
    if (empty($_SESSION['is_admin'])) {
        header("Location: index.php?error=Access denied. Admin privileges required.");
        exit();
    }
}
?>
