<?php
// Admin - Edit Bike
require_once '../includes/admin_header.php';

// Check if admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$message = '';
$bike = null;

if (isset($_GET['id'])) {
    $bike_id = $_GET['id'];

    // Fetch bike details
    $stmt = $conn->prepare("SELECT * FROM bikes WHERE id = ?");
    $stmt->bind_param("i", $bike_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $bike = $result->fetch_assoc();
    } else {
        $message = "Bike not found.";
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bike_id'])) {
    $bike_id = $_POST['bike_id'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $license_plate = $_POST['license_plate'];
    $rental_price_per_day = $_POST['rental_price_per_day'];
    $availability_status = $_POST['availability_status'];
    // Optional: Handle image upload here

    // Input validation (basic)
    if (empty($make) || empty($model) || empty($year) || empty($license_plate) || empty($rental_price_per_day) || empty($availability_status)) {
        $message = "All fields are required.";
    } elseif (!is_numeric($year) || $year < 1900 || $year > date('Y') + 1) {
        $message = "Invalid year.";
    } elseif (!is_numeric($rental_price_per_day) || $rental_price_per_day <= 0) {
        $message = "Rental price must be a positive number.";
    } else {
        // Check if license plate already exists for another bike
        $stmt = $conn->prepare("SELECT id FROM bikes WHERE license_plate = ? AND id != ?");
        $stmt->bind_param("si", $license_plate, $bike_id);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "License plate already exists for another bike.";
        } else {
            $sql = "UPDATE bikes SET make = ?, model = ?, year = ?, license_plate = ?, rental_price_per_day = ?, availability_status = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssisdsi", $make, $model, $year, $license_plate, $rental_price_per_day, $availability_status, $bike_id);

            if ($stmt->execute()) {
                header("Location: bikes.php?status=success&message=Bike+updated+successfully!");
                exit();
            } else {
                $message = "Error updating bike: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}

if (!$bike && !empty($_GET['id'])) {
    $message = "Bike not found.";
}
?>

<h1>Edit Bike</h1>

<?php if ($message): ?>
    <p style="color: red;"><?php echo $message; ?></p>
<?php endif; ?>

<?php if ($bike): ?>
<form action="edit_bike.php" method="POST">
    <input type="hidden" name="bike_id" value="<?php echo htmlspecialchars($bike['id']); ?>">

    <label for="make">Make:</label>
    <input type="text" id="make" name="make" value="<?php echo htmlspecialchars($bike['make']); ?>" required><br><br>

    <label for="model">Model:</label>
    <input type="text" id="model" name="model" value="<?php echo htmlspecialchars($bike['model']); ?>" required><br><br>

    <label for="year">Year:</label>
    <input type="number" id="year" name="year" value="<?php echo htmlspecialchars($bike['year']); ?>" required min="1900" max="<?php echo date('Y') + 1; ?>"><br><br>

    <label for="license_plate">License Plate:</label>
    <input type="text" id="license_plate" name="license_plate" value="<?php echo htmlspecialchars($bike['license_plate']); ?>" required><br><br>

    <label for="rental_price_per_day">Rental Price per Day:</label>
    <input type="number" id="rental_price_per_day" name="rental_price_per_day" step="0.01" value="<?php echo htmlspecialchars($bike['rental_price_per_day']); ?>" required min="0.01"><br><br>

    <label for="availability_status">Availability Status:</label>
    <select id="availability_status" name="availability_status" required>
        <option value="available" <?php echo ($bike['availability_status'] === 'available') ? 'selected' : ''; ?>>Available</option>
        <option value="rented" <?php echo ($bike['availability_status'] === 'rented') ? 'selected' : ''; ?>>Rented</option>
        <option value="maintenance" <?php echo ($bike['availability_status'] === 'maintenance') ? 'selected' : ''; ?>>Maintenance</option>
    </select><br><br>

    <button type="submit">Update Bike</button>
    <a href="bikes.php">Back to Bike List</a>
</form>
<?php else: ?>
    <p>Bike details could not be loaded. Please return to the <a href="bikes.php">bike list</a>.</p>
<?php endif; ?>

<?php
require_once '../includes/admin_footer.php';
?>
