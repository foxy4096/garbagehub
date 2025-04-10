<?php
// Include database connection
require_once 'db.php';

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$query = "SELECT username, email, avatar FROM users WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $avatar = trim($_POST['avatar']);

    // Validate input
    if (empty($username) || empty($email) || empty($avatar)) {
        $error = "All fields are required.";
    } else {
        // Update user data
        $update_query = "UPDATE users SET username = ?, email = ?, avatar = ? WHERE id = ?";
        $update_stmt = $db->prepare($update_query);
        $update_stmt->bind_param("sssi", $username, $email, $avatar, $user_id);

        if ($update_stmt->execute()) {
            $success = "Profile updated successfully.";
            // Update session variables
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['avatar'] = $avatar;

        } else {
            $error = "Failed to update profile.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
</head>
<body>
    <h1>Edit Profile</h1>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
        <br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        <br>

        <label for="avatar">Avatar:</label>
        <input id="avatar" name="avatar" value="<?php echo htmlspecialchars($user['avatar']); ?>">
        <br>

        <button type="submit">Update Profile</button>
        
        <a href="dashboard.php">Cancel</a>
    </form>
</body>
</html>