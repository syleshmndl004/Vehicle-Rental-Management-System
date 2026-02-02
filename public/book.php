<?php
/**
 * BOOKING PAGE - VEHICLE RESERVATION FORM
 * 
 * WHAT THIS DOES:
 * - Shows vehicle details (model, type, daily rate)
 * - Displays a booking form where user selects start and end dates
 * - Checks vehicle availability (prevents double-booking)
 * - Calculates total rental cost based on number of days
 * - Submits booking to database for confirmation
 * 
 * SECURITY FEATURES:
 * - Login required (require_login() function)
 * - Input validation (FILTER_VALIDATE_INT for vehicle ID)
 * - CSRF token protection on form
 * - Availability checking (prevents booking already-rented vehicles)
 * - Output escaping (htmlspecialchars) prevents XSS
 * - Prepared statements prevent SQL injection
 */

// Include security and helper functions
// provides require_login() to ensure user is authenticated
require_once('../includes/functions.php');

// ===== REQUIRE LOGIN =====
// Only logged-in users can book vehicles
// Unauthenticated users are redirected to login page
require_login();

// Include database connection
require_once('../config/db.php');

// Include page header (navigation bar, styling)
include('../includes/header.php');

// ===== GET VEHICLE ID FROM URL =====
// User clicks "Book Now" button on vehicle with URL: book.php?id=3
// Extract the ID using FILTER_VALIDATE_INT (ensures it's a valid integer)
// Prevents SQL injection by rejecting non-integer values
$vehicle_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$vehicle_id) {
    // Invalid or missing vehicle ID
    header("Location: index.php?error=Invalid vehicle.");
    exit();
}

// ===== FETCH VEHICLE DETAILS =====
// Get the vehicle information to display on booking form
$sql = "SELECT model, type, daily_rate FROM vehicles WHERE id = ?";

// Create prepared statement
$stmt = $conn->prepare($sql);

// Bind parameter: "i" = integer ID
$stmt->bind_param("i", $vehicle_id);

// Execute query
$stmt->execute();

// Get results
$result = $stmt->get_result();

// Fetch as associative array (easy column access: $vehicle['model'])
$vehicle = $result->fetch_assoc();

// Close statement
$stmt->close();

// If vehicle doesn't exist in database, redirect with error
if (!$vehicle) {
    header("Location: index.php?error=Vehicle not found.");
    exit();
}

// ===== CHECK VEHICLE AVAILABILITY =====
// Prevent booking if vehicle is already rented for selected dates
// Query checks if there's a confirmed booking for today through tomorrow
$sql_check = "SELECT 1 FROM bookings WHERE vehicle_id = ? AND booking_status = 'Confirmed' AND CURDATE() BETWEEN start_date AND end_date";

// Create prepared statement
$stmt_check = $conn->prepare($sql_check);

// Bind vehicle ID
$stmt_check->bind_param("i", $vehicle_id);

// Execute query
$stmt_check->execute();

// Store results in memory
$stmt_check->store_result();

// If there are active bookings, vehicle is currently unavailable
if ($stmt_check->num_rows > 0) {
    header("Location: index.php?error=Vehicle no longer available.");
    exit();
}

// Close statement
$stmt_check->close();

// ===== GENERATE CSRF TOKEN =====
// CSRF token protects form from malicious cross-site requests
// Token stored in session, must match when form is submitted
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!-- ===== BOOKING FORM HTML ===== -->
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <!-- Display vehicle model that's being booked -->
                <h3>Book: <?php echo htmlspecialchars($vehicle['model']); ?></h3>
            </div>
            <div class="card-body">
                <!-- Display vehicle information -->
                <!-- htmlspecialchars() prevents XSS by converting special characters to HTML entities -->
                <p><strong>Type:</strong> <?php echo htmlspecialchars($vehicle['type']); ?></p>
                <p><strong>Daily Rate:</strong> $<span id="daily-rate"><?php echo number_format($vehicle['daily_rate'], 2); ?></span></p>
                <hr>
                
                <!-- Display error messages if any -->
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>
                
                <!-- Booking form submission -->
                <form action="bookings.php" method="POST">
                    <!-- Hidden fields sent to bookings.php -->
                    <input type="hidden" name="action" value="create">           <!-- Tells bookings.php to create new booking -->
                    <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id; ?>">  <!-- Which vehicle to book -->
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">  <!-- Security token -->
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                    </div>
                    
                    <div id="date-error" class="alert alert-danger" style="display:none;"></div>
                    <div id="availability-status" style="display:none;"></div>
                    
                    <h4 class="mt-3">Total Cost: <span id="total-cost">$0.00</span></h4>
                    
                    <button type="submit" id="submit-button" class="btn btn-primary mt-3 w-100" disabled>Confirm Booking</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="../assets/js/booking-calculator.js"></script>
<script src="../assets/js/date-availability.js"></script>
<?php 
$conn->close();
include('../includes/footer.php'); 
?>
