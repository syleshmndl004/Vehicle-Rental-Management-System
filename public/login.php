<?php
/**
 * LOGIN PAGE - USER AUTHENTICATION
 * 
 * WHAT THIS DOES:
 * - Displays login form where users enter email and password
 * - Handles login submissions via AJAX (no page reload)
 * - Validates user credentials against database
 * - Creates secure session if credentials are correct
 * - Redirects users to dashboard after successful login
 * 
 * SECURITY FEATURES:
 * - Input filtering: filter_var() removes dangerous characters
 * - Password verification: password_verify() uses bcrypt hashing
 * - Session regeneration: Creates new session ID after login
 * - Prepared statements: Prevents SQL injection attacks
 * - HTTPS recommended: Set session.cookie_secure = 1 in production
 */

// Start PHP session to store user information
session_start();

// ===== HANDLE LOGIN FORM SUBMISSION =====
// This section runs only when user submits the login form via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    // Send JSON response header (not HTML)
    header('Content-Type: application/json');
    
    // Include database connection
    require_once('../config/db.php');
    
    // SECURITY: Filter and clean email input
    // filter_var() removes spaces and special characters
    // FILTER_SANITIZE_EMAIL removes invalid email characters
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    
    // Get password (no filtering, will compare with hashed version)
    $password = $_POST['password'] ?? '';
    
    // SECURITY: Validate email format using FILTER_VALIDATE_EMAIL
    // Ensures email looks like a real email address
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
        exit();
    }
    
    // SQL QUERY with placeholder (?)
    // Placeholder prevents SQL injection - user input doesn't directly enter SQL
    $sql = "SELECT id, username, password, is_admin FROM users WHERE email = ?";
    
    // Create prepared statement
    $stmt = $conn->prepare($sql);
    
    // Bind parameter: "s" = string type, $email = actual value
    // Prepared statements safely insert values into SQL query
    $stmt->bind_param("s", $email);
    
    // Execute the query
    $stmt->execute();
    
    // Get results from database
    $result = $stmt->get_result();
    
    // Fetch as associative array (easy to access by column name)
    $user = $result->fetch_assoc();
    
    // ===== VERIFY CREDENTIALS =====
    // Check if user exists AND password matches
    // password_verify() safely compares plain password with bcrypt hash
    if ($user && password_verify($password, $user['password'])) {
        // SECURITY: Regenerate session ID after login
        // Prevents session fixation attacks
        session_regenerate_id(true);
        
        // Store user data in session (available throughout their session)
        $_SESSION['user_id'] = $user['id'];                    // User's database ID
        $_SESSION['username'] = $user['username'];            // User's display name
        $_SESSION['is_admin'] = (bool)$user['is_admin'];      // Admin privileges flag
        
        // Send success response to AJAX
        echo json_encode(['success' => true, 'message' => 'Login successful!']);
    } else {
        // User not found or password incorrect
        echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
    }
    
    // Close database connections
    $stmt->close();
    $conn->close();
    
    // Stop execution (don't display HTML)
    exit();
}

// ===== DISPLAY LOGIN PAGE HTML =====
// This section runs if user just opened the page (no form submission)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VRM - Vehicle Rental Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/landing.css">
</head>
<body>
    <div class="landing-container">
        <div class="hero-section">
            <div class="hero-content">
                <div class="brand-logo">
                    <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 16c3.314 0 6-2 6-5.5 0-1.5-.5-4-2.5-6.5L8 0 4.5 4C2.5 6.5 2 9 2 10.5 2 14 4.686 16 8 16ZM8 4l3 4c1 1.5 1 3.5 1 4.5 0 2-.75 3-4 3s-4-1-4-3c0-1 0-3 1-4.5l3-4Z"/>
                    </svg>
                    <h1 class="brand-name">VehicleRent</h1>
                </div>
                <p class="hero-subtitle">Your Trusted Vehicle Rental Partner</p>
                <div class="features">
                    <div class="feature-item">
                        <span class="feature-icon">🚗</span>
                        <span>Cars</span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">🏍️</span>
                        <span>Bikes</span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">🛵</span>
                        <span>Scooters</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="auth-section">
            <div class="auth-forms-container">
                <div class="auth-form" id="loginForm">
                    <h2 class="form-title">Welcome Back</h2>
                    <p class="form-subtitle">Login to your account</p>
                    
                    <div id="loginMessage" class="alert" style="display: none;"></div>
                    
                    <form id="loginFormElement">
                        <input type="hidden" name="action" value="login">
                        <div class="form-group">
                            <label for="login-email">Email</label>
                            <input type="email" class="form-input" id="login-email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="login-password">Password</label>
                            <input type="password" class="form-input" id="login-password" name="password" required>
                        </div>
                        <button type="submit" class="btn-primary-custom">Login</button>
                    </form>
                    
                    <p class="form-toggle">
                        Don't have an account? 
                        <a href="register.php">Sign up</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginFormElement').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const messageDiv = document.getElementById('loginMessage');
            
            fetch('login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                messageDiv.style.display = 'block';
                if (data.success) {
                    messageDiv.className = 'alert alert-success';
                    messageDiv.textContent = data.message;
                    setTimeout(() => window.location.href = 'index.php', 1000);
                } else {
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.textContent = data.message;
                }
            });
        });
    </script>
</body>
</html>
