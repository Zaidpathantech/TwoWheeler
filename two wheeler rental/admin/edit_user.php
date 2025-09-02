<?php
// Admin - Edit User Role
require_once '../includes/admin_header.php';

// Check if admin is logged in
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$message = '';
$user = null;

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch user details
    $stmt = $conn->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
    } else {
        $message = "User not found.";
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['new_role'];

    // Validate new role
    $allowed_roles = ['user', 'admin'];
    if (!in_array($new_role, $allowed_roles)) {
        $message = "Invalid role provided.";
    } else {
        $sql = "UPDATE users SET role = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $new_role, $user_id);

        if ($stmt->execute()) {
            header("Location: users.php?status=success&message=User+role+updated+successfully!");
            exit();
        } else {
            $message = "Error updating user role: " . $stmt->error;
        }
        $stmt->close();
    }
}

if (!$user && !empty($_GET['id'])) {
    $message = "User not found.";
}
?>

<h1>Edit User Role</h1>

<?php if ($message): ?>
    <p style="color: red;"><?php echo $message; ?></p>
<?php endif; ?>

<?php if ($user): ?>
<form action="edit_user.php" method="POST">
    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['id']); ?>">

    <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>

    <label for="new_role">New Role:</label>
    <select id="new_role" name="new_role" required>
        <option value="user" <?php echo ($user['role'] === 'user') ? 'selected' : ''; ?>>User</option>
        <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
    </select><br><br>

    <button type="submit">Update Role</button>
    <a href="users.php">Back to User List</a>
</form>
<?php else: ?>
    <p>User details could not be loaded. Please return to the <a href="users.php">user list</a>.</p>
<?php endif; ?>

<?php
require_once '../includes/admin_footer.php';
?>
