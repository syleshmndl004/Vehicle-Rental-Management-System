<?php
/**
 * Edit Vehicle Page - UPDATE Operation in CRUD
 * 
 * Purpose: Allows users to modify existing vehicle details
 * Security: Login required, CSRF protection, input validation
 * 
 * For Viva:
 * - This implements the 'U' (Update) in CRUD operations
 * - Pre-fills form with existing data from database
 * - Uses prepared statements for both SELECT and UPDATE
 * - Validates user input before updating database
 */

// Include security and helper functions
require_once('../includes/functions.php');

// ADMIN-ONLY ACCESS
// Only administrators can edit vehicle details
// Regular users can only view and book vehicles
require_admin();

// Include database connection
require_once('../config/db.php');

// Generate CSRF token for form security
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Validate vehicle ID from URL parameter (Input Filtering - Security #1)
// FILTER_VALIDATE_INT ensures only valid integers are accepted
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header("Location: index.php?error=Invalid vehicle ID.");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF token validation failed.');
    }

    $plate_number = trim($_POST['plate_number']);
    $model = trim($_POST['model']);
    $type = $_POST['type'];
    $daily_rate = filter_input(INPUT_POST, 'daily_rate', FILTER_VALIDATE_FLOAT);
    
    if (!empty($plate_number) && !empty($model) && $daily_rate !== false) {
        $sql = "UPDATE vehicles SET plate_number=?, model=?, type=?, daily_rate=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdi", $plate_number, $model, $type, $daily_rate, $id);
        
        if ($stmt->execute()) {
            header("Location: index.php?message=Vehicle updated successfully.");
            exit();
        } else {
            $error_message = "Error updating vehicle.";
        }
        $stmt->close();
    } else {
        $error_message = "Please fill in all fields correctly.";
    }
}

$sql = "SELECT plate_number, model, type, daily_rate FROM vehicles WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
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
