<?php
/**
 * Bookings Management - Handles all booking operations
 * - Display bookings (user/admin view)
 * - Create new booking
 * - Delete booking (admin only)
 */

require_once('../includes/functions.php');

// HANDLE DELETE BOOKING (Users can delete their own, Admin can delete any)
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    require_login();
    
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF token validation failed!');
    }
    
    $booking_id = filter_input(INPUT_POST, 'booking_id', FILTER_VALIDATE_INT);
    if ($booking_id) {
        require_once('../config/db.php');
        
        // Check ownership or admin status
        if (!empty($_SESSION['is_admin'])) {
            $sql = "DELETE FROM bookings WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $booking_id);
        } else {
            $sql = "DELETE FROM bookings WHERE id = ? AND user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $booking_id, $_SESSION['user_id']);
        }
        
        if ($stmt->execute() && $stmt->affected_rows > 0) {
            header("Location: bookings.php?message=Booking deleted successfully.");
        } else {
            header("Location: bookings.php?error=Failed to delete booking or unauthorized.");
        }
        $stmt->close();
        $conn->close();
    }
    exit();
}

// HANDLE EDIT BOOKING (Users can edit their own, Admin can edit any)
if (isset($_POST['action']) && $_POST['action'] === 'edit') {
    require_login();
    require_once('../config/db.php');
    
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF token validation failed.');
    }
    
    $booking_id = filter_input(INPUT_POST, 'booking_id', FILTER_VALIDATE_INT);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $user_id = $_SESSION['user_id'];
    
    if ($booking_id && !empty($start_date) && !empty($end_date)) {
        // Get booking and vehicle details
        if (!empty($_SESSION['is_admin'])) {
            $sql = "SELECT b.vehicle_id, v.daily_rate FROM bookings b JOIN vehicles v ON b.vehicle_id = v.id WHERE b.id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $booking_id);
        } else {
            $sql = "SELECT b.vehicle_id, v.daily_rate FROM bookings b JOIN vehicles v ON b.vehicle_id = v.id WHERE b.id = ? AND b.user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $booking_id, $user_id);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $booking = $result->fetch_assoc();
        $stmt->close();
        
        if ($booking) {
            // Recalculate total cost
            $date1 = new DateTime($start_date);
            $date2 = new DateTime($end_date);
            $days = $date2->diff($date1)->days + 1;
            $total_cost = $days * $booking['daily_rate'];
            
            // Update booking
            if (!empty($_SESSION['is_admin'])) {
                $sql = "UPDATE bookings SET start_date = ?, end_date = ?, total_cost = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssdi", $start_date, $end_date, $total_cost, $booking_id);
            } else {
                $sql = "UPDATE bookings SET start_date = ?, end_date = ?, total_cost = ? WHERE id = ? AND user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssdii", $start_date, $end_date, $total_cost, $booking_id, $user_id);
            }
            
            if ($stmt->execute() && $stmt->affected_rows > 0) {
                header("Location: bookings.php?message=Booking updated successfully!");
            } else {
                header("Location: bookings.php?error=No changes made or unauthorized.");
            }
            $stmt->close();
        } else {
            header("Location: bookings.php?error=Booking not found or unauthorized.");
        }
    }
    $conn->close();
    exit();
}

// HANDLE CREATE BOOKING
if (isset($_POST['action']) && $_POST['action'] === 'create') {
    require_login();
    require_once('../config/db.php');
    
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF token validation failed.');
    }
    
    $vehicle_id = filter_input(INPUT_POST, 'vehicle_id', FILTER_VALIDATE_INT);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $user_id = $_SESSION['user_id'];
    
    if ($vehicle_id && !empty($start_date) && !empty($end_date)) {
        // Get vehicle details
        $sql = "SELECT daily_rate FROM vehicles WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $vehicle_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $vehicle = $result->fetch_assoc();
        $stmt->close();
        
        if ($vehicle) {
            // Calculate total cost
            $date1 = new DateTime($start_date);
            $date2 = new DateTime($end_date);
            $days = $date2->diff($date1)->days + 1;
            $total_cost = $days * $vehicle['daily_rate'];
            
            // Insert booking
            $sql = "INSERT INTO bookings (user_id, vehicle_id, start_date, end_date, total_cost, booking_status) VALUES (?, ?, ?, ?, ?, 'Confirmed')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iissd", $user_id, $vehicle_id, $start_date, $end_date, $total_cost);
            
            if ($stmt->execute()) {
                header("Location: bookings.php?message=Booking confirmed successfully!");
            } else {
                header("Location: bookings.php?error=Booking failed. Please try again.");
            }
            $stmt->close();
        }
    }
    $conn->close();
    exit();
}

// DISPLAY BOOKINGS
require_login();
$twig = require_once('../includes/twig_init.php');
require_once('../config/db.php');

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$is_admin = !empty($_SESSION['is_admin']);

if ($is_admin) {
    $sql = "SELECT b.id as booking_id, b.start_date, b.end_date, b.total_cost, b.booking_status, b.created_at,
                   v.model, v.plate_number, u.username
            FROM bookings b
            JOIN vehicles v ON b.vehicle_id = v.id
            JOIN users u ON b.user_id = u.id
            ORDER BY b.created_at DESC";
    $stmt = $conn->prepare($sql);
} else {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT b.id as booking_id, b.start_date, b.end_date, b.total_cost, b.booking_status, b.created_at,
                   v.model, v.plate_number
            FROM bookings b
            JOIN vehicles v ON b.vehicle_id = v.id
            WHERE b.user_id = ?
            ORDER BY b.start_date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();

// Convert results to array for Twig
$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

// Render with Twig
echo $twig->render('bookings.twig', [
    'page_title' => ($is_admin ? 'All Bookings' : 'My Bookings') . ' - VRM',
    'bookings' => $bookings,
    'message' => $_GET['message'] ?? null,
    'error' => $_GET['error'] ?? null,
    'csrf_token' => $_SESSION['csrf_token'],
]);

$stmt->close();
$conn->close();
?>
