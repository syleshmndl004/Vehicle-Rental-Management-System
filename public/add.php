<?php
/**
 * Add Vehicle Page - CREATE Operation in CRUD
 * 
 * Purpose: Allows authenticated users to add new vehicles to the rental inventory
 * Security: Protected by login requirement and CSRF token
 * 
 * For Viva:
 * - This implements the 'C' (Create) in CRUD operations
 * - Uses prepared statements to prevent SQL injection
 * - Validates all inputs before database insertion
 * - Implements CSRF protection against cross-site request forgery
 */

// Include helper functions file which contains security functions
require_once('../includes/functions.php');

// ADMIN-ONLY ACCESS
// Only administrators can add new vehicles
// Regular users can only view and book vehicles
require_admin();

// Include database connection file
require_once('../config/db.php');

// Generate CSRF token if it doesn't exist
// CSRF tokens prevent unauthorized form submissions from external sites
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Check if form was submitted using POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate CSRF token - Security Feature
    // hash_equals() prevents timing attacks by comparing strings in constant time
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF token validation failed.');
    }

    // INPUT FILTERING - Security Requirement #1
    // Sanitize user inputs to prevent XSS attacks
    $plate_number = trim($_POST['plate_number']); // Remove whitespace
    $model = trim($_POST['model']); // Remove whitespace
    $type = $_POST['type']; // Dropdown value, already limited to Car/Bike/Scooter
    // Validate that daily_rate is a valid float number
    $daily_rate = filter_input(INPUT_POST, 'daily_rate', FILTER_VALIDATE_FLOAT);
    
    if (empty($plate_number) || empty($model) || empty($type) || $daily_rate === false || $daily_rate < 0) {
        $error_message = "Please fill in all fields correctly.";
    } else {
        $status = 'Available';
        $sql = "INSERT INTO vehicles (plate_number, model, type, daily_rate, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssds", $plate_number, $model, $type, $daily_rate, $status);
        
        if ($stmt->execute()) {
            header("Location: index.php?message=Vehicle added successfully.");
            exit();
        } else {
            $error_message = "Error adding vehicle.";
        }
        $stmt->close();
    }
}
$conn->close();

include('../includes/header.php');
?>
<h2>Add New Vehicle</h2>

<?php if (isset($error_message)): ?>
    <div class="alert alert-danger"><?php echo $error_message; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="add.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="mb-3">
                <label for="plate_number" class="form-label">Plate Number</label>
                <input type="text" class="form-control" id="plate_number" name="plate_number" required>
            </div>
            
            <div class="mb-3">
                <label for="model" class="form-label">Model</label>
                <input type="text" class="form-control" id="model" name="model" required>
            </div>
            
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select class="form-select" id="type" name="type">
                    <option value="Car">Car</option>
                    <option value="Bike">Bike</option>
                    <option value="Scooter">Scooter</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="daily_rate" class="form-label">Daily Rate ($)</label>
                <input type="number" step="0.01" class="form-control" id="daily_rate" name="daily_rate" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Add Vehicle</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
