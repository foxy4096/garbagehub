<?php
require_once 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT username, email, avatar FROM users WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $avatar = trim($_POST['avatar']);

    if (empty($username) || empty($email) || empty($avatar)) {
        $error = "All fields are required.";
    } else {
        $update_query = "UPDATE users SET username = ?, email = ?, avatar = ? WHERE id = ?";
        $update_stmt = $db->prepare($update_query);
        $update_stmt->bind_param("sssi", $username, $email, $avatar, $user_id);

        if ($update_stmt->execute()) {
            $success = "Profile updated successfully.";
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
    <title>Edit Profile</title>
    <!-- ONLY THE STYLE CHANGES (save your backend sanity) -->
<style>
    body {
        font-family: "Papyrus", "Comic Sans MS", cursive;
        background: repeating-conic-gradient(#f00 0% 10%, #0f0 10% 20%, #00f 20% 30%);
        color: #ff0;
        margin: 0;
        padding: 0;
        animation: spinBG 6s linear infinite;
    }

    @keyframes spinBG {
        from { filter: hue-rotate(0deg); }
        to { filter: hue-rotate(360deg); }
    }

    h1 {
        font-size: 42px;
        text-shadow: 3px 3px 10px #0ff;
        animation: bounce 1.5s infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-30px); }
    }

    form {
        border: 10px double #ff00ff;
        padding: 30px;
        border-radius: 100px;
        transform: rotate(-8deg);
        background: linear-gradient(45deg, #ff0, #0ff);
        box-shadow: 0 0 30px #000, 0 0 60px #f0f;
        animation: wobble 3s infinite;
    }

    @keyframes wobble {
        0% { transform: rotate(-5deg); }
        50% { transform: rotate(5deg); }
        100% { transform: rotate(-5deg); }
    }

    input, button {
        border: 4px dashed #000;
        background-color: #ff69b4;
        color: #00f;
        padding: 15px;
        margin: 10px;
        font-size: 20px;
        box-shadow: 5px 5px 15px #0f0;
        transform: skew(10deg, 5deg);
        transition: all 0.3s ease-in-out;
    }

    input:focus {
        background-color: #000;
        color: #fff;
        border-color: #f0f;
    }

    button:hover {
        transform: scale(1.5) rotate(10deg);
        background: #0ff;
        color: #f00;
    }

    a {
        font-size: 22px;
        font-weight: bold;
        color: #fff;
        background: #000;
        padding: 5px 10px;
        border: 3px solid #f00;
        text-decoration: underline wavy #0f0;
    }

    a:hover {
        background: #f00;
        color: #0ff;
        text-decoration: line-through;
    }
</style>

</head>
<body>
    <h1>Edit Profile</h1>

    <?php if (isset($error)): ?>
        <p class="msg error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <p class="msg success"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label for="avatar">Avatar (Image URL):</label>
        <input type="text" id="avatar" name="avatar" value="<?php echo htmlspecialchars($user['avatar']); ?>" required>

        <?php if (!empty($user['avatar'])): ?>
        <div class="avatar-preview">
            <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt="Avatar Preview">
        </div>
        <?php endif; ?>

        <button type="submit">Update Profile</button>
        <a href="dashboard.php">Cancel</a>
        <a href="settings.php">Settings</a>
    </form>
</body>
</html>
