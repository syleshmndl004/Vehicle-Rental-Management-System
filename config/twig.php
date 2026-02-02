<?php
/**
 * TWIG TEMPLATE ENGINE CONFIGURATION
 * 
 * WHAT THIS DOES:
 * - Sets up Twig templating engine to render dynamic HTML pages
 * - Twig separates business logic from presentation (HTML display)
 * - Instead of mixing PHP and HTML, we use clean Twig syntax
 * 
 * WHY USE TWIG:
 * - Cleaner syntax than PHP
 * - Auto-escaping prevents XSS attacks by default
 * - Better code organization
 * - Template inheritance for reusable layouts
 * - Easy to maintain and modify
 */

// Load Twig library from vendor folder using autoloader
// __DIR__ returns the current directory path
require_once __DIR__ . '/vendor/autoload.php';

// Create Twig loader - tells Twig where template files are stored
// All template files (.twig) are in the 'templates' folder
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');

// Create Twig environment with configuration options
$twig = new \Twig\Environment($loader, [
    // Cache compiled templates in cache/twig folder for faster loading
    'cache' => __DIR__ . '/cache/twig',
    
    // Auto-reload: Automatically recompile templates when they change
    // This is useful during development
    'auto_reload' => true,
    
    // Debug mode: Enable debugging features for development
    // Shows helpful error messages
    'debug' => true,
]);

// Add debugging extension for development
// Allows using dump() function in templates to inspect variables
$twig->addExtension(new \Twig\Extension\DebugExtension());

// Create custom 'price' filter
// In templates: {{ daily_rate|price }} automatically formats as currency
// Example: 50.00 becomes "$50.00"
$twig->addFilter(new \Twig\TwigFilter('price', function ($number) {
    return '$' . number_format($number, 2);
}));

// Create custom 'date_format' filter
// In templates: {{ booking_date|date_format }} formats dates nicely
// Example: "2026-02-02" becomes "Feb 02, 2026"
$twig->addFilter(new \Twig\TwigFilter('date_format', function ($date, $format = 'M d, Y') {
    return date($format, strtotime($date));
}));

// Add global variables available in ALL template files
// These variables don't need to be passed separately to each template
$twig->addGlobal('app_name', 'VehicleRent'); // Application name
$twig->addGlobal('base_path', '/V.R.M/public/'); // Base URL for all pages

// Return the configured Twig instance so other files can use it
return $twig;
