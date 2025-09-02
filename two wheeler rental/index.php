<?php
// Two-Wheeler Rental System - Index Page
// This file will handle the main landing page logic and dynamic bike display.
require_once 'includes/header.php';

$bikes = [];
$message = '';

$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d', strtotime('+1 day'));

if ($start_date >= $end_date) {
    $message = "End date must be after start date.";
} else {
    // Fetch available bikes for the selected date range
    // A bike is available if there is no booking that overlaps with the selected start_date and end_date
    $sql = "SELECT * FROM bikes WHERE availability_status = 'available' AND id NOT IN (
                SELECT bike_id FROM bookings
                WHERE (start_date <= ? AND end_date >= ?)
                   OR (start_date <= ? AND end_date >= ?)
                   OR (start_date >= ? AND end_date <= ?)
            )";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $start_date, $start_date, $end_date, $end_date, $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $bikes[] = $row;
    }
    $stmt->close();
}

?>

<h1>Welcome to Two-Wheeler Rental!</h1>
<p>Browse our available bikes and make a booking.</p>

<?php if ($message): ?>
    <p style="color: red;"><?php echo $message; ?></p>
<?php endif; ?>

<form action="index.php" method="GET">
    <label for="start_date">Start Date:</label>
    <input type="date" id="start_date" name="start_date" value="<?php echo $start_date; ?>" required>
    <label for="end_date">End Date:</label>
    <input type="date" id="end_date" name="end_date" value="<?php echo $end_date; ?>" required>
    <button type="submit">Check Availability</button>
</form>

<h2>Available Bikes</h2>
<div class="bike-list">
    <?php if (count($bikes) > 0): ?>
        <?php foreach ($bikes as $bike): ?>
            <div class="bike-card">
                <h3><?php echo htmlspecialchars($bike['make']) . ' ' . htmlspecialchars($bike['model']); ?> (<?php echo htmlspecialchars($bike['year']); ?>)</h3>
                <p>License Plate: <?php echo htmlspecialchars($bike['license_plate']); ?></p>
                <p>Price per day: $<?php echo htmlspecialchars($bike['rental_price_per_day']); ?></p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <form action="book.php" method="POST">
                        <input type="hidden" name="bike_id" value="<?php echo $bike['id']; ?>">
                        <input type="hidden" name="start_date" value="<?php echo $start_date; ?>">
                        <input type="hidden" name="end_date" value="<?php echo $end_date; ?>">
                        <button type="submit">Book Now</button>
                    </form>
                <?php else: ?>
                    <p><a href="login.php">Login</a> to book this bike.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No bikes available for the selected dates or all bikes are currently rented/under maintenance.</p>
    <?php endif; ?>
</div>

<?php
require_once 'includes/footer.php';
?>
