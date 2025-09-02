<?php
// Admin - Delete Bike
require_once '../includes/admin_header.php';

// Check if admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $bike_id = $_GET['id'];

    $sql = "DELETE FROM bikes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bike_id);

    if ($stmt->execute()) {
        header("Location: bikes.php?status=success&message=Bike+deleted+successfully!");
        exit();
    } else {
        header("Location: bikes.php?status=error&message=Error+deleting+bike:+" . urlencode($stmt->error));
        exit();
    }
    $stmt->close();
} else {
    header("Location: bikes.php?status=error&message=No+bike+ID+provided+for+deletion.");
    exit();
}
?>
