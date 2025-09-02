<?php
// Admin - Delete User
require_once '../includes/admin_header.php';

// Check if admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Prevent admin from deleting their own account (optional but recommended)
    if ($user_id == $_SESSION['user_id']) {
        header("Location: users.php?status=error&message=You+cannot+delete+your+own+admin+account.");
        exit();
    }

    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        header("Location: users.php?status=success&message=User+deleted+successfully!");
        exit();
    } else {
        header("Location: users.php?status=error&message=Error+deleting+user:+" . urlencode($stmt->error));
        exit();
    }
    $stmt->close();
} else {
    header("Location: users.php?status=error&message=No+user+ID+provided+for+deletion.");
    exit();
}
?>
