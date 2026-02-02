<?php
/**
 * REGISTRATION PAGE - NEW USER ACCOUNT CREATION
 * 
 * WHAT THIS DOES:
 * - Displays registration form for new users
 * - Validates all user inputs (username, email, password, CAPTCHA)
 * - Checks if email already exists in database
 * - Hashes password using bcrypt algorithm
 * - Creates new user account in database
 * 
 * SECURITY FEATURES:
 * - CAPTCHA protection: Prevents bot registrations
 * - Password hashing: bcrypt makes passwords unreadable even to admin
 * - Input validation: Ensures data meets requirements
 * - Email uniqueness: Prevents duplicate accounts
 * - Prepared statements: Prevents SQL injection
 * - Password confirmation: Users enter password twice to avoid typos
 * - Minimum password length: Ensures stronger passwords
 */

// Start PHP session
session_start();

// ===== HANDLE REGISTRATION FORM SUBMISSION =====
// This section runs only when user submits the registration form via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
    // Send JSON response header (not HTML)
    header('Content-Type: application/json');
    
    // Include database connection
    require_once('../config/db.php');
    
    // SECURITY: Filter and clean all input data
    $username = trim($_POST['username'] ?? '');  // Remove spaces, not hashed
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);  // Remove invalid email chars
    $password = $_POST['password'] ?? '';        // Will be hashed later
    $password_confirm = $_POST['password_confirm'] ?? '';  // For verification
    $captcha_input = $_POST['captcha'] ?? '';    // User's CAPTCHA answer
    $captcha_session = $_SESSION['captcha'] ?? '';  // Correct answer stored in session
    
    // ===== VALIDATION CHECKS =====
    // Check 1: All required fields are filled
    if (empty($username) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit();
    }
    
    // Check 2: Email format is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
        exit();
    }
    
    // Check 3: Password and confirmation match
    if ($password !== $password_confirm) {
        echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
        exit();
    }
    
    // Check 4: Password is strong enough (minimum 6 characters)
    if (strlen($password) < 6) {
        echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters.']);
        exit();
    }
    
    // Check 5: CAPTCHA is correct (prevents bot registrations)
    if ($captcha_input !== $captcha_session) {
        echo json_encode(['success' => false, 'message' => 'Invalid CAPTCHA code.']);
        exit();
    }
    
    // ===== CHECK IF EMAIL ALREADY EXISTS =====
    // Prevents duplicate user accounts with same email
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    // If email exists, reject registration
    if ($stmt->get_result()->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email already registered.']);
        $stmt->close();
        $conn->close();
        exit();
    }
    $stmt->close();
    
    // ===== HASH PASSWORD WITH BCRYPT =====
    // password_hash() uses bcrypt algorithm (industry standard)
    // PASSWORD_DEFAULT uses the strongest available algorithm
    // Once hashed, password cannot be reversed (one-way encryption)
    // Even if database is stolen, passwords are unreadable
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // ===== INSERT NEW USER INTO DATABASE =====
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    // Bind parameters: "sss" = three string values
    // Placeholders prevent SQL injection
    $stmt->bind_param("sss", $username, $email, $hashed_password);
    
    // Execute the INSERT query
    if ($stmt->execute()) {
        // Success! Remove CAPTCHA from session so user can't reuse it
        unset($_SESSION['captcha']);
        
        // Send success response
        echo json_encode(['success' => true, 'message' => 'Registration successful! Redirecting to login...']);
    } else {
        // Database error occurred
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
