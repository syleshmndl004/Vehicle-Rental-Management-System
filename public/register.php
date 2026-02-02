<?php
/**
 * Register Page - Handles both display and registration
 */

session_start();

// HANDLE REGISTRATION SUBMISSION (Ajax)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    header('Content-Type: application/json');
    require_once('../config/db.php');
    
    $username = trim($_POST['username'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $captcha_input = $_POST['captcha'] ?? '';
    $captcha_session = $_SESSION['captcha'] ?? '';
    
    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit();
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
        exit();
    }
    
    if ($password !== $password_confirm) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
        exit();
    }
    
    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters.']);
        exit();
    }
    
    if ($captcha_input !== $captcha_session) {
        echo json_encode(['success' => false, 'message' => 'Invalid CAPTCHA code.']);
        exit();
    }
    
    // Check if email exists
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already registered.']);
        $stmt->close();
        $conn->close();
        exit();
    }
    $stmt->close();
    
    // Insert user
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    
    if ($stmt->execute()) {
        unset($_SESSION['captcha']);
        echo json_encode(['success' => true, 'message' => 'Registration successful! Redirecting to login...']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Registration failed. Please try again.']);
    }
    
    $stmt->close();
    $conn->close();
    exit();
}

// Generate CAPTCHA
$_SESSION['captcha'] = substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, 6);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - VRM</title>
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
                    <div class="feature-item"><span class="feature-icon">🚗</span><span>Cars</span></div>
                    <div class="feature-item"><span class="feature-icon">🏍️</span><span>Bikes</span></div>
                    <div class="feature-item"><span class="feature-icon">🛵</span><span>Scooters</span></div>
                </div>
            </div>
        </div>

        <div class="auth-section">
            <div class="auth-forms-container">
                <div class="auth-form">
                    <h2 class="form-title">Create Account</h2>
                    <p class="form-subtitle">Join us today</p>
                    
                    <div id="signupMessage" class="alert" style="display: none;"></div>
                    
                    <form id="signupFormElement">
                        <input type="hidden" name="action" value="register">
                        <div class="form-group">
                            <label for="signup-username">Username</label>
                            <input type="text" class="form-input" id="signup-username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="signup-email">Email</label>
                            <input type="email" class="form-input" id="signup-email" name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="signup-password">Password</label>
                            <input type="password" class="form-input" id="signup-password" name="password" required minlength="6">
                        </div>
                        <div class="form-group">
                            <label for="signup-password-confirm">Confirm Password</label>
                            <input type="password" class="form-input" id="signup-password-confirm" name="password_confirm" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Security Code</label>
                            <div class="captcha-container">
                                <input type="text" class="form-input" name="captcha" required autocomplete="off" placeholder="Enter code">
                                <div class="captcha-display"><?php echo $_SESSION['captcha']; ?></div>
                                <button type="button" class="btn-refresh" onclick="window.location.reload()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                                        <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-primary-custom">Sign Up</button>
                    </form>
                    
                    <p class="form-toggle">
                        Already have an account? 
                        <a href="login.php">Login</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('signupFormElement').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const messageDiv = document.getElementById('signupMessage');
            
            fetch('register.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                messageDiv.style.display = 'block';
                if (data.success) {
                    messageDiv.className = 'alert alert-success';
                    messageDiv.textContent = data.message;
                    setTimeout(() => window.location.href = 'login.php', 2000);
                } else {
                    messageDiv.className = 'alert alert-danger';
                    messageDiv.textContent = data.message;
                }
            });
        });
    </script>
</body>
</html>
