<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Determine base path
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
$base_path = ($current_dir === 'public') ? '' : 'public/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Rental System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="<?php echo $base_path; ?>index.php">🚗 VehicleRent</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_path; ?>index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_path; ?>search.php">🔍 Search</a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if (!empty($_SESSION['is_admin'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $base_path; ?>add.php">➕ Add Vehicle</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_path; ?>bookings.php">
                            📋 <?php echo !empty($_SESSION['is_admin']) ? 'Bookings' : 'My Bookings'; ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
            <div class="d-flex">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="navbar-text me-3">
                        Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                    </span>
                    <a href="<?php echo $base_path; ?>logout.php" class="btn btn-outline-light">Logout</a>
                <?php else: ?>
                    <a href="<?php echo $base_path; ?>login.php" class="btn btn-outline-light me-2">Login</a>
                    <a href="<?php echo $base_path; ?>register.php" class="btn btn-primary">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<div class="container">
