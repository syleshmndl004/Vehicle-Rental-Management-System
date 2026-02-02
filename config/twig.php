<?php
/**
 * Twig Template Engine Configuration
 * This file initializes Twig for the Vehicle Rental Management System
 */

require_once __DIR__ . '/vendor/autoload.php';

// Configure Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/templates');
$twig = new \Twig\Environment($loader, [
    'cache' => __DIR__ . '/cache/twig',
    'auto_reload' => true, // Automatically reload templates when they change
    'debug' => true, // Enable debugging features
]);

// Add debug extension for development
$twig->addExtension(new \Twig\Extension\DebugExtension());

// Add custom filters
$twig->addFilter(new \Twig\TwigFilter('price', function ($number) {
    return '$' . number_format($number, 2);
}));

$twig->addFilter(new \Twig\TwigFilter('date_format', function ($date, $format = 'M d, Y') {
    return date($format, strtotime($date));
}));

// Add global variables available to all templates
$twig->addGlobal('app_name', 'VehicleRent');
$twig->addGlobal('base_path', '/V.R.M/public/');

return $twig;
