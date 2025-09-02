<?php
// Admin - Manage Bikes
require_once '../includes/admin_header.php';

// Check if admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$bikes = [];
$message = '';

// Fetch all bikes
$sql = "SELECT * FROM bikes ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bikes[] = $row;
    }
} else {
    $message = "No bikes found.";
}

// Handle success/error messages from other pages (e.g., add, edit, delete)
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        $message = htmlspecialchars($_GET['message']);
    } elseif ($_GET['status'] === 'error') {
        $message = htmlspecialchars($_GET['message']);
    }
}

?>

<h1>Manage Bikes</h1>

<?php if ($message): ?>
    <p style="color: <?php echo (isset($_GET['status']) && $_GET['status'] === 'error') ? 'red' : 'green'; ?>;"><?php echo $message; ?></p>
<?php endif; ?>

<p><a href="add_bike.php">Add New Bike</a></p>

<?php if (count($bikes) > 0): ?>
    <table border="1" style="width:100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Make</th>
                <th>Model</th>
                <th>Year</th>
                <th>License Plate</th>
                <th>Price/Day</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bikes as $bike): ?>
                <tr>
                    <td><?php echo htmlspecialchars($bike['id']); ?></td>
                    <td><?php echo htmlspecialchars($bike['make']); ?></td>
                    <td><?php echo htmlspecialchars($bike['model']); ?></td>
                    <td><?php echo htmlspecialchars($bike['year']); ?></td>
                    <td><?php echo htmlspecialchars($bike['license_plate']); ?></td>
                    <td>$<?php echo number_format($bike['rental_price_per_day'], 2); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($bike['availability_status'])); ?></td>
                    <td>
                        <a href="edit_bike.php?id=<?php echo $bike['id']; ?>">Edit</a> |
                        <a href="delete_bike.php?id=<?php echo $bike['id']; ?>" onclick="return confirm('Are you sure you want to delete this bike?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No bikes currently in the system. Add one above!</p>
<?php endif; ?>

<?php
require_once '../includes/admin_footer.php';
?>
