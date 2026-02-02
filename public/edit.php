<?php
/**
 * EDIT VEHICLE PAGE - UPDATE OPERATION
 * 
 * WHAT THIS DOES:
 * - Shows a form pre-filled with existing vehicle data
 * - Allows admin to modify vehicle details (plate number, model, type, rate)
 * - Updates vehicle information in database
 * - Redirects to vehicle list after successful update
 * 
 * SECURITY FEATURES:
 * - Admin-only access (require_admin() function)
 * - Input validation (FILTER_VALIDATE_INT, trim())
 * - CSRF token protection on form
 * - Prepared statements prevent SQL injection
 * - Output escaping (htmlspecialchars) prevents XSS
 * 
 * PART OF CRUD:
 * - C (Create) = add.php
 * - R (Read) = index.php
 * - U (Update) = THIS FILE
 * - D (Delete) = delete.php
 */

// Include security and helper functions
// These provide require_admin(), require_login(), etc.
require_once('../includes/functions.php');

// ===== ADMIN-ONLY ACCESS =====
// require_admin() checks if user is logged in AND has admin privileges
// Only administrators can modify vehicle details
// Regular users can only view and book vehicles
require_admin();

// Include database connection file
require_once('../config/db.php');

// ===== GENERATE CSRF TOKEN =====
// CSRF tokens prevent Cross-Site Request Forgery attacks
// Token is stored in session and must match when form is submitted
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// ===== GET VEHICLE ID FROM URL =====
// User clicks "Edit" link with URL like: edit.php?id=5
// Extract the ID using FILTER_VALIDATE_INT (ensures it's a valid integer)
// If ID is missing or invalid, redirect back to vehicle list
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php?error=Invalid vehicle ID.");
    exit();
}

// ===== HANDLE FORM SUBMISSION =====
// This section runs when user submits the edit form (POST method)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // SECURITY: Verify CSRF token matches
    // hash_equals() compares strings in constant time (prevents timing attacks)
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF token validation failed.');
    }

    // SECURITY: Clean all user inputs
    $plate_number = trim($_POST['plate_number']);  // Remove spaces
    $model = trim($_POST['model']);                // Remove spaces
    $type = $_POST['type'];                        // From dropdown (limited options)
    
    // SECURITY: Validate daily_rate is a valid floating-point number
    // Returns false if not a valid float
    $daily_rate = filter_input(INPUT_POST, 'daily_rate', FILTER_VALIDATE_FLOAT);
    
    // Validate all required fields are filled and valid
    if (!empty($plate_number) && !empty($model) && $daily_rate !== false) {
        // ===== UPDATE QUERY =====
        // SQL with placeholders (?) prevents SQL injection
        $sql = "UPDATE vehicles SET plate_number=?, model=?, type=?, daily_rate=? WHERE id=?";
        
        // Create prepared statement
        $stmt = $conn->prepare($sql);
        
        // Bind parameters
        // "sssdi" = string, string, string, double (float), integer
        // Parameters are bound in order: plate_number, model, type, daily_rate, id
        $stmt->bind_param("sssdi", $plate_number, $model, $type, $daily_rate, $id);
        
        // Execute the UPDATE query
        if ($stmt->execute()) {
            // Success! Redirect to vehicle list with success message
            header("Location: index.php?message=Vehicle updated successfully.");
            exit();
        } else {
            // Database error occurred
            $error_message = "Error updating vehicle.";
        }
        $stmt->close();
    } else {
        // One or more fields are invalid
        $error_message = "Please fill in all fields correctly.";
    }
}

// ===== FETCH CURRENT VEHICLE DATA =====
// Get existing vehicle information to pre-fill the form
$sql = "SELECT plate_number, model, type, daily_rate FROM vehicles WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);  // "i" = integer ID
$stmt->execute();
$result = $stmt->get_result();
$vehicle = $result->fetch_assoc();
$stmt->close();

if (!$vehicle) {
    header("Location: index.php?error=Vehicle not found.");
    exit();
}
$conn->close();

include('../includes/header.php');
?>
<h2>Edit Vehicle</h2>

<?php if (isset($error_message)): ?>
    <div class="alert alert-danger"><?php echo $error_message; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="edit.php?id=<?php echo $id; ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="mb-3">
                <label for="plate_number" class="form-label">Plate Number</label>
                <input type="text" class="form-control" name="plate_number" value="<?php echo htmlspecialchars($vehicle['plate_number']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="model" class="form-label">Model</label>
                <input type="text" class="form-control" name="model" value="<?php echo htmlspecialchars($vehicle['model']); ?>" required>
            </div>
            
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="Car" <?php echo ($vehicle['type'] == 'Car') ? 'selected' : ''; ?>>Car</option>
                    <option value="Bike" <?php echo ($vehicle['type'] == 'Bike') ? 'selected' : ''; ?>>Bike</option>
                    <option value="Scooter" <?php echo ($vehicle['type'] == 'Scooter') ? 'selected' : ''; ?>>Scooter</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="daily_rate" class="form-label">Daily Rate ($)</label>
                <input type="number" step="0.01" class="form-control" name="daily_rate" value="<?php echo htmlspecialchars($vehicle['daily_rate']); ?>" required>
            </div>
            
            <button type="submit" class="btn btn-primary">Update Vehicle</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php include('../includes/footer.php'); ?>
