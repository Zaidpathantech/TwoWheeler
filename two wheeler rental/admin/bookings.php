<?php
// Admin - Manage Bookings
require_once '../includes/admin_header.php';

// Check if admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$bookings = [];
$message = '';

// Handle booking status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id']) && isset($_POST['new_status'])) {
    $booking_id = $_POST['booking_id'];
    $new_status = $_POST['new_status'];

    // Validate new status
    $allowed_statuses = ['pending', 'confirmed', 'cancelled', 'completed'];
    if (!in_array($new_status, $allowed_statuses)) {
        $message = "Invalid status provided.";
    } else {
        $sql = "UPDATE bookings SET booking_status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_status, $booking_id);

        if ($stmt->execute()) {
            $message = "Booking status updated successfully!";
        } else {
            $message = "Error updating booking status: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch all bookings with user and bike details
$sql = "SELECT b.id as booking_id, b.start_date, b.end_date, b.total_price, b.booking_status, 
               u.name as user_name, u.email as user_email, 
               bk.make, bk.model, bk.year, bk.license_plate
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN bikes bk ON b.bike_id = bk.id
        ORDER BY b.created_at DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
} else {
    $message = "No bookings found.";
}

?>

<h1>Manage Bookings</h1>

<?php if ($message): ?>
    <p style="color: green;"><?php echo $message; ?></p>
<?php endif; ?>

<?php if (count($bookings) > 0): ?>
    <table border="1" style="width:100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>User Name</th>
                <th>User Email</th>
                <th>Bike</th>
                <th>License Plate</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                    <td><?php echo htmlspecialchars($booking['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($booking['user_email']); ?></td>
                    <td><?php echo htmlspecialchars($booking['make']) . ' ' . htmlspecialchars($booking['model']); ?> (<?php echo htmlspecialchars($booking['year']); ?>)</td>
                    <td><?php echo htmlspecialchars($booking['license_plate']); ?></td>
                    <td><?php echo htmlspecialchars($booking['start_date']); ?></td>
                    <td><?php echo htmlspecialchars($booking['end_date']); ?></td>
                    <td>$<?php echo number_format($booking['total_price'], 2); ?></td>
                    <td>
                        <form action="bookings.php" method="POST">
                            <input type="hidden" name="booking_id" value="<?php echo $booking['booking_id']; ?>">
                            <select name="new_status" onchange="this.form.submit()">
                                <option value="pending" <?php echo ($booking['booking_status'] === 'pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="confirmed" <?php echo ($booking['booking_status'] === 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                                <option value="cancelled" <?php echo ($booking['booking_status'] === 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                <option value="completed" <?php echo ($booking['booking_status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
                            </select>
                        </form>
                    </td>
                    <td>
                        <!-- Add more actions here if needed, e.g., view details -->
                        <!-- <a href="view_booking.php?id=<?php echo $booking['booking_id']; ?>">View</a> -->
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No bookings found.</p>
<?php endif; ?>

<?php
require_once '../includes/admin_footer.php';
?>
