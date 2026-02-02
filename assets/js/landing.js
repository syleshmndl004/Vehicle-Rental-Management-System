/**
 * Landing Page - Authentication Handler
 */

// Toggle between login and signup forms
document.getElementById('showSignup').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('loginForm').style.display = 'none';
    document.getElementById('signupForm').style.display = 'block';
    loadCaptcha();
});

document.getElementById('showLogin').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('signupForm').style.display = 'none';
    document.getElementById('loginForm').style.display = 'block';
});

// Load CAPTCHA
function loadCaptcha() {
    fetch('captcha.php')
        .then(response => response.text())
        .then(code => {
            document.getElementById('captcha-code').textContent = code;
        })
        .catch(error => console.error('Error loading captcha:', error));
}

// Login Form Handler
document.getElementById('loginFormElement').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('action', 'login');
    const messageDiv = document.getElementById('loginMessage');
    
    fetch('landing.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageDiv.className = 'alert alert-success';
            messageDiv.textContent = 'Login successful! Redirecting...';
            messageDiv.style.display = 'block';
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 1000);
        } else {
            messageDiv.className = 'alert alert-danger';
            messageDiv.textContent = data.message;
            messageDiv.style.display = 'block';
        }
    })
    .catch(error => {
        messageDiv.className = 'alert alert-danger';
        messageDiv.textContent = 'An error occurred. Please try again.';
        messageDiv.style.display = 'block';
    });
});

// Signup Form Handler
document.getElementById('signupFormElement').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const password = document.getElementById('signup-password').value;
    const passwordConfirm = document.getElementById('signup-password-confirm').value;
    const messageDiv = document.getElementById('signupMessage');
    
    if (password !== passwordConfirm) {
        messageDiv.className = 'alert alert-danger';
        messageDiv.textContent = 'Passwords do not match!';
        messageDiv.style.display = 'block';
        return;
    }
    
    const formData = new FormData(this);
    formData.append('action', 'register');
    
    fetch('landing.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageDiv.className = 'alert alert-success';
            messageDiv.textContent = 'Registration successful! Please login.';
            messageDiv.style.display = 'block';
            this.reset();
            setTimeout(() => {
                document.getElementById('showLogin').click();
            }, 2000);
        } else {
            messageDiv.className = 'alert alert-danger';
            messageDiv.textContent = data.message;
            messageDiv.style.display = 'block';
            loadCaptcha();
        }
    })
    .catch(error => {
        messageDiv.className = 'alert alert-danger';
        messageDiv.textContent = 'An error occurred. Please try again.';
        messageDiv.style.display = 'block';
        loadCaptcha();
    });
});

// Check if user message exists in URL
const urlParams = new URLSearchParams(window.location.search);
const message = urlParams.get('message');
if (message) {
    const messageDiv = document.getElementById('loginMessage');
    messageDiv.className = 'alert alert-success';
    messageDiv.textContent = decodeURIComponent(message);
    messageDiv.style.display = 'block';
}
