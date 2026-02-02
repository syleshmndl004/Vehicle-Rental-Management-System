<?php
/**
 * Twig Configuration - Template Engine Setup
 * 
 * Purpose: Initializes Twig template engine with security and caching
 * Security: Auto-escaping enabled to prevent XSS attacks
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Determine base path
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
$base_path = ($current_dir === 'public') ? '' : 'public/';

// Initialize Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader, [
    'cache' => __DIR__ . '/../cache/twig',
    'auto_reload' => true, // Auto-reload templates in development
    'autoescape' => 'html', // Auto-escape output to prevent XSS
]);

// Add custom filter for number formatting
$twig->addFilter(new \Twig\TwigFilter('number_format', function ($number, $decimals = 0) {
    return number_format($number, $decimals);
}));

// Global variables available to all templates
$twig->addGlobal('app_name', 'VehicleRent');
$twig->addGlobal('base_path', $base_path);
$twig->addGlobal('session', [
    'user_id' => $_SESSION['user_id'] ?? null,
    'username' => $_SESSION['username'] ?? null,
    'is_admin' => $_SESSION['is_admin'] ?? false,
]);

return $twig;
