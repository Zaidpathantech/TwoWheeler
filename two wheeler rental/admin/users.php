<?php
// Admin - Manage Users
require_once '../includes/admin_header.php';

// Check if admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$users = [];
$message = '';

// Handle success/error messages from other pages (e.g., edit, delete)
if (isset($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        $message = htmlspecialchars($_GET['message']);
    } elseif ($_GET['status'] === 'error') {
        $message = htmlspecialchars($_GET['message']);
    }
}

// Fetch all users
$sql = "SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
} else {
    $message = "No users found.";
}

?>

<h1>Manage Users</h1>

<?php if ($message): ?>
    <p style="color: <?php echo (isset($_GET['status']) && $_GET['status'] === 'error') ? 'red' : 'green'; ?>;"><?php echo $message; ?></p>
<?php endif; ?>

<?php if (count($users) > 0): ?>
    <table border="1" style="width:100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Registered On</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars(ucfirst($user['role'])); ?></td>
                    <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                    <td>
                        <a href="edit_user.php?id=<?php echo $user['id']; ?>">Edit Role</a> |
                        <a href="delete_user.php?id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No users registered yet.</p>
<?php endif; ?>

<?php
require_once '../includes/admin_footer.php';
?>
