<?php
session_start();
include "db.php";

$insult_messages = [
    "Can't even type your password right? Pathetic.",
    "Oh wow, failed to log in. Big surprise.",
    "You sure you even signed up?",
    "Maybe try 'password123'.",
    "If you forgot your password, too bad. We don’t do resets."
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if (empty($username) || empty($password)) {
        $_SESSION["error"] = "Fill the damn form.";
        header("Location: login.php");
        exit;
    }

    // Check user in DB
    $stmt = $db->prepare("SELECT id, password_hash, avatar FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id, $password_hash, $avatar);
        $stmt->fetch();

        if (password_verify($password, $password_hash)) {
            $_SESSION["user_id"] = $user_id;
            $_SESSION["username"] = $username;
            $_SESSION["avatar"] = $avatar;
            $_SESSION["message"] = "Oh great, you're back. Lucky us.";
            header("Location: dashboard.php");
            exit;
        }
    }

    $_SESSION["error"] = $insult_messages[array_rand($insult_messages)];
    header("Location: login.php");
    exit;
}

$db->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login - GarbageHub</title>
</head>

<body>
    <h1>Log In to GarbageHub (Regret it instantly)</h1>

    <?php if (isset($_SESSION["error"])): ?>
        <p style="color: red;"><?= $_SESSION["error"];
        unset($_SESSION["error"]); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION["message"])): ?>
        <p style="color: green;"><?= $_SESSION["message"];
        unset($_SESSION["message"]); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username" required><br>

        <label>Password:</label>
        <input type="password" name="password" required><br>

        <button type="submit">Enter the Abyss</button>
    </form>

    <p>No account? <a href="signup.php">Sign up</a> (for some reason).</p>
</body>

</html>