<?php
// Admin - Add New Bike
require_once '../includes/admin_header.php';

// Check if admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
        // Check if license plate already exists
        $stmt = $conn->prepare("SELECT id FROM bikes WHERE license_plate = ?");
        $stmt->bind_param("s", $license_plate);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "License plate already exists.";
        } else {
            $sql = "INSERT INTO bikes (make, model, year, license_plate, rental_price_per_day, availability_status) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssisds", $make, $model, $year, $license_plate, $rental_price_per_day, $availability_status);

            if ($stmt->execute()) {
                header("Location: bikes.php?status=success&message=Bike+added+successfully!");
                exit();
            } else {
                $message = "Error adding bike: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}

?>

<h1>Add New Bike</h1>

<?php if ($message): ?>
    <p style="color: red;"><?php echo $message; ?></p>
<?php endif; ?>

<form action="add_bike.php" method="POST">
    <label for="make">Make:</label>
    <input type="text" id="make" name="make" required><br><br>

    <label for="model">Model:</label>
    <input type="text" id="model" name="model" required><br><br>

    <label for="year">Year:</label>
    <input type="number" id="year" name="year" required min="1900" max="<?php echo date('Y') + 1; ?>"><br><br>

    <label for="license_plate">License Plate:</label>
    <input type="text" id="license_plate" name="license_plate" required><br><br>

    <label for="rental_price_per_day">Rental Price per Day:</label>
    <input type="number" id="rental_price_per_day" name="rental_price_per_day" step="0.01" required min="0.01"><br><br>

    <label for="availability_status">Availability Status:</label>
    <select id="availability_status" name="availability_status" required>
        <option value="available">Available</option>
        <option value="rented">Rented</option>
        <option value="maintenance">Maintenance</option>
    </select><br><br>

    <button type="submit">Add Bike</button>
    <a href="bikes.php">Back to Bike List</a>
</form>

<?php
require_once '../includes/admin_footer.php';
?>
