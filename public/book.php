<?php
require_once('../includes/functions.php');
require_login();
require_once('../config/db.php');
include('../includes/header.php');

$vehicle_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$vehicle_id) {
    header("Location: index.php?error=Invalid vehicle.");
    exit();
}

$sql = "SELECT model, type, daily_rate FROM vehicles WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vehicle_id);
$stmt->execute();
$result = $stmt->get_result();
$vehicle = $result->fetch_assoc();
$stmt->close();

if (!$vehicle) {
    header("Location: index.php?error=Vehicle not found.");
    exit();
}

$sql_check = "SELECT 1 FROM bookings WHERE vehicle_id = ? AND booking_status = 'Confirmed' AND CURDATE() BETWEEN start_date AND end_date";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $vehicle_id);
$stmt_check->execute();
$stmt_check->store_result();
if ($stmt_check->num_rows > 0) {
    header("Location: index.php?error=Vehicle no longer available.");
    exit();
}
$stmt_check->close();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3>Book: <?php echo htmlspecialchars($vehicle['model']); ?></h3>
            </div>
            <div class="card-body">
                <p><strong>Type:</strong> <?php echo htmlspecialchars($vehicle['type']); ?></p>
                <p><strong>Daily Rate:</strong> $<span id="daily-rate"><?php echo number_format($vehicle['daily_rate'], 2); ?></span></p>
                <hr>
                
                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
                <?php endif; ?>
                
                <form action="bookings.php" method="POST">
                    <input type="hidden" name="action" value="create">
                    <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
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
