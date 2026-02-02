<?php
/**
 * Login Page - Handles both display and authentication
 */

session_start();

// HANDLE LOGIN SUBMISSION (Ajax)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'login') {
    header('Content-Type: application/json');
    require_once('../config/db.php');
    
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
        exit();
    }
    
    $sql = "SELECT id, username, password, is_admin FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = (bool)$user['is_admin'];
        
        echo json_encode(['success' => true, 'message' => 'Login successful!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
    }
    
    $stmt->close();
    $conn->close();
    exit();
}

// DISPLAY LOGIN PAGE
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
