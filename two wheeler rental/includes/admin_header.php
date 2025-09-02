<?php
session_start();
require_once 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Two-Wheeler Rental</title>
    <link rel="stylesheet" href="../public/style.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Admin Dashboard</a></li>
                <li><a href="bikes.php">Manage Bikes</a></li>
                <li><a href="bookings.php">Manage Bookings</a></li>
                <li><a href="users.php">Manage Users</a></li>
                <li><a href="../index.php">User Site</a></li>
                <?php if (isset($_SESSION['user_id'])): // Check if any user is logged in, then show logout ?>
                    <li><a href="../logout.php">Logout</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    <main>
