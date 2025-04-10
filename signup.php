<?php
session_start();
include "db.php";

$insult_messages = [
    "Wow, a new user. Lowering the code quality already?",
    "Why are you even signing up? Regret awaits.",
    "Great, another person who can't commit properly.",
    "We don't even have CSS, and you still signed up?",
    "Hope your password isn't '123456', but we both know it is."
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $avatar = trim($_POST["avatar"]); // URL or file upload

    if (empty($username) || empty($email) || empty($password)) {
        $_SESSION["error"] = "Fill the damn form.";
        header("Location: signup.php");
        exit;
    }

    // Check for existing user
    $check_stmt = $db->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $check_stmt->store_result();
    
    if ($check_stmt->num_rows > 0) {
        $_SESSION["error"] = "Username or email already exists. Good job.";
        $check_stmt->close();
        header("Location: signup.php");
        exit;
    }
    $check_stmt->close();

    // Basic avatar URL validation (optional)
    if (!empty($avatar) && !filter_var($avatar, FILTER_VALIDATE_URL)) {
        $_SESSION["error"] = "That avatar URL looks fake.";
        header("Location: signup.php");
        exit;
    }

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $db->prepare("INSERT INTO users (username, email, password_hash, avatar) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $password_hash, $avatar);

    if ($stmt->execute()) {
        $_SESSION["user_id"] = $stmt->insert_id;
        $_SESSION["username"] = $username;
        $_SESSION["avatar"] = $avatar;
        $_SESSION["message"] = $insult_messages[array_rand($insult_messages)];
        header("Location: dashboard.php");
        exit;
    } else {
        $_SESSION["error"] = "Signup failed. Probably your fault.";
    }
    $stmt->close();
}
$db->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup - GarbageHub</title>
</head>
<body>
    <h1>Sign Up for GarbageHub (Why tho?)</h1>

    <?php if (isset($_SESSION["error"])): ?>
        <p style="color: red;"><?php echo $_SESSION["error"]; unset($_SESSION["error"]); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username" required><br>

        <label>Email:</label>
        <input type="email" name="email" required><br>

        <label>Password:</label>
        <input type="password" name="password" required><br>

        <label>Avatar (URL or Upload Path):</label>
        <input type="text" name="avatar" placeholder="https://example.com/avatar.png"><br>

        <button type="submit">Join the Dumpster Fire</button>
    </form>

    <p>Already suffering? <a href="login.php">Login</a> instead.</p>
</body>
</html>
        