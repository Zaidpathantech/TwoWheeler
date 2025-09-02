<?php
// Two-Wheeler Rental System - User Dashboard Page
// This file will display user-specific information and bookings.
require_once 'includes/header.php';

// Add logic to check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$bookings = [];
$message = '';

if (isset($_GET['booking_success']) && $_GET['booking_success'] === 'true') {
    $message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : "Your booking was successful!";
}

// Fetch user's bookings
$sql = "SELECT b.id as booking_id, b.start_date, b.end_date, b.total_price, b.booking_status, 
               bk.make, bk.model, bk.year, bk.license_plate, bk.rental_price_per_day
        FROM bookings b
        JOIN bikes bk ON b.bike_id = bk.id
        WHERE b.user_id = ?
        ORDER BY b.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}
$stmt->close();
?>

<h1>Welcome to your Dashboard, <?php echo htmlspecialchars($user_name); ?>!</h1>

<?php if ($message): ?>
    <p style="color: green;"><?php echo $message; ?></p>
<?php endif; ?>

<h2>Your Bookings</h2>

<?php if (count($bookings) > 0): ?>
    <div class="bookings-list">
        <?php foreach ($bookings as $booking): ?>
            <div class="booking-card">
                <h3>Booking ID: <?php echo htmlspecialchars($booking['booking_id']); ?></h3>
                <p>Bike: <?php echo htmlspecialchars($booking['make']) . ' ' . htmlspecialchars($booking['model']); ?> (<?php echo htmlspecialchars($booking['year']); ?>)</p>
                <p>License Plate: <?php echo htmlspecialchars($booking['license_plate']); ?></p>
                <p>Rental Period: <?php echo htmlspecialchars($booking['start_date']) . ' to ' . htmlspecialchars($booking['end_date']); ?></p>
                <p>Total Price: $<?php echo number_format($booking['total_price'], 2); ?></p>
                <p>Status: <strong><?php echo htmlspecialchars(ucfirst($booking['booking_status'])); ?></strong></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>You have no current or past bookings.</p>
    <p><a href="index.php">Browse bikes</a> to make your first booking!</p>
<?php endif; ?>

<?php
require_once 'includes/footer.php';
?>
