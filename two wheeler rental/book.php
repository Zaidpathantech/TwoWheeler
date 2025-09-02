<?php
// Two-Wheeler Rental System - Booking Page
// This file handles the booking request from users.
require_once 'includes/header.php';

$message = '';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $bike_id = $_POST['bike_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Input Validation (basic, more robust validation can be added)
    if (empty($bike_id) || empty($start_date) || empty($end_date)) {
        $message = "Missing booking details.";
    } elseif ($start_date >= $end_date) {
        $message = "End date must be after start date.";
    } else {
        // Re-verify bike availability to prevent double bookings (race condition)
        $check_sql = "SELECT id, rental_price_per_day FROM bikes WHERE id = ? AND availability_status = 'available' AND id NOT IN (
                        SELECT bike_id FROM bookings
                        WHERE (start_date <= ? AND end_date >= ?)
                           OR (start_date <= ? AND end_date >= ?)
                           OR (start_date >= ? AND end_date <= ?)
                    )";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("issssss", $bike_id, $start_date, $start_date, $end_date, $end_date, $start_date, $end_date);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows === 1) {
            $bike_data = $check_result->fetch_assoc();
            $rental_price_per_day = $bike_data['rental_price_per_day'];

            // Calculate total price
            $datetime1 = new DateTime($start_date);
            $datetime2 = new DateTime($end_date);
            $interval = $datetime1->diff($datetime2);
            $days = $interval->days;
            $total_price = $rental_price_per_day * $days;

            // Insert booking into database
            $insert_sql = "INSERT INTO bookings (user_id, bike_id, start_date, end_date, total_price, booking_status) VALUES (?, ?, ?, ?, ?, 'pending')";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("iissd", $user_id, $bike_id, $start_date, $end_date, $total_price);

            if ($insert_stmt->execute()) {
                $message = "Booking successful! Total Price: $" . number_format($total_price, 2);
                // Optionally redirect to dashboard or booking confirmation page
                header("Location: dashboard.php?booking_success=true&message=" . urlencode($message));
                exit();
            } else {
                $message = "Error processing booking: " . $insert_stmt->error;
            }
            $insert_stmt->close();
        } else {
            $message = "Selected bike is not available for the chosen dates.";
        }
        $check_stmt->close();
    }
}
?>

<h1>Book Your Ride</h1>

<?php if ($message): ?>
    <p style="color: red;"><?php echo $message; ?></p>
<?php endif; ?>

<p>Please return to the <a href="index.php">home page</a> to select a bike and dates.</p>

<?php
require_once 'includes/footer.php';
?>
