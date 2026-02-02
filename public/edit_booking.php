<?php
/**
 * Edit Booking - Allows users to edit their own bookings
 */

require_once('../includes/functions.php');
require_login();
require_once('../config/db.php');

$booking_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$booking_id) {
    header("Location: bookings.php?error=Invalid booking.");
    exit();
}

// Get booking details - check ownership
if (!empty($_SESSION['is_admin'])) {
    $sql = "SELECT b.*, v.model, v.plate_number, v.daily_rate 
            FROM bookings b 
            JOIN vehicles v ON b.vehicle_id = v.id 
            WHERE b.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);
} else {
    $sql = "SELECT b.*, v.model, v.plate_number, v.daily_rate 
            FROM bookings b 
            JOIN vehicles v ON b.vehicle_id = v.id 
            WHERE b.id = ? AND b.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $booking_id, $_SESSION['user_id']);
}

$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();
$stmt->close();

if (!$booking) {
    header("Location: bookings.php?error=Booking not found or unauthorized.");
    exit();
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include('../includes/header.php');
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3>Edit Booking: <?php echo htmlspecialchars($booking['model']); ?></h3>
            </div>
            <div class="card-body">
                <p><strong>Vehicle:</strong> <?php echo htmlspecialchars($booking['model']); ?> (<?php echo htmlspecialchars($booking['plate_number']); ?>)</p>
                <p><strong>Daily Rate:</strong> $<span id="daily-rate"><?php echo number_format($booking['daily_rate'], 2); ?></span></p>
                <hr>
                
                <form action="bookings.php" method="POST">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   value="<?php echo $booking['start_date']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                   value="<?php echo $booking['end_date']; ?>" required>
                        </div>
                    </div>
                    
                    <div id="date-error" class="alert alert-danger" style="display:none;"></div>
                    
                    <h4 class="mt-3">Total Cost: <span id="total-cost">$<?php echo number_format($booking['total_cost'], 2); ?></span></h4>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Update Booking</button>
                        <a href="bookings.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
const dailyRate = <?php echo $booking['daily_rate']; ?>;
const startDateInput = document.getElementById('start_date');
const endDateInput = document.getElementById('end_date');
const totalCostDisplay = document.getElementById('total-cost');
const dateError = document.getElementById('date-error');

function calculateCost() {
    const start = new Date(startDateInput.value);
    const end = new Date(endDateInput.value);
    
    dateError.style.display = 'none';
    
    if (start && end) {
        if (end < start) {
            dateError.textContent = 'End date must be after start date.';
            dateError.style.display = 'block';
            totalCostDisplay.textContent = '$0.00';
            return;
        }
        
        const diffTime = Math.abs(end - start);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        const totalCost = diffDays * dailyRate;
        
        totalCostDisplay.textContent = '$' + totalCost.toFixed(2);
    }
}

startDateInput.addEventListener('change', calculateCost);
endDateInput.addEventListener('change', calculateCost);
</script>

<?php
$conn->close();
include('../includes/footer.php');
?>
