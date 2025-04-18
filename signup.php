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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - GarbageHub</title>
    <style>
        body {
            background-color: #008080; /* XP teal */
            background-image: url('static/cursed_imgs/chaos_bg.jpg'); /* Optional cursed background */
            background-size: cover;
            background-attachment: fixed;
            color: #000;
            font-family: "Tahoma", sans-serif;
            margin: 0;
            padding: 40px;
        }

        .window {
            background-color: #f1f1f1;
            border: 3px solid #000080;
            border-radius: 8px;
            width: 360px;
            margin: 0 auto;
            box-shadow: 4px 4px 0 #000;
            padding: 20px;
            position: relative;
        }

        .window::before {
            content: "🐧 Signup.exe";
            display: block;
            background-color: #000080;
            color: white;
            padding: 8px;
            font-weight: bold;
            font-size: 14px;
            border-bottom: 2px solid #ccc;
        }

        h1 {
            margin-top: 10px;
            font-size: 20px;
            color: #000080;
        }

        form label {
            display: block;
            text-align: left;
            margin-top: 12px;
            margin-bottom: 4px;
        }

        input {
            width: 95%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #000080;
            border-radius: 3px;
            background-color: #fff;
        }

        button {
            margin-top: 20px;
            padding: 10px 15px;
            font-size: 14px;
            background-color: #c0c0c0;
            border: 2px outset #ddd;
            color: #000;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #a0a0a0;
        }

        .error-message {
            color: red;
            background-color: #fff0f0;
            padding: 10px;
            border: 1px solid red;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .message {
            margin-top: 20px;
            background-color: #ffffcc;
            border: 1px solid #cccc00;
            padding: 10px;
            border-radius: 5px;
            font-weight: bold;
            color: #333300;
        }

        .link-line {
            margin-top: 15px;
            font-size: 14px;
        }

        .link-line a {
            color: #000080;
            text-decoration: underline;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            font-size: 12px;
            color: #fff;
            text-shadow: 1px 1px 2px #000;
        }

        footer a {
            color: #ffff00;
            text-decoration: none;
            font-weight: bold;
        }

        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="window">
    <h1>Welcome to GarbageHub</h1>

    <?php if (isset($_SESSION["error"])): ?>
        <div class="error-message"><?php echo $_SESSION["error"]; unset($_SESSION["error"]); ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <label>Avatar (URL or Upload Path):</label>
        <input type="text" name="avatar" placeholder="https://example.com/avatar.png">

        <button type="submit">Join the Dumpster Fire</button>
    </form>

    <div class="link-line">
        Already suffering? <a href="login.php">Login</a> instead.
    </div>

    <?php if (isset($_SESSION["message"])): ?>
        <div class="message"><?php echo $_SESSION["message"]; unset($_SESSION["message"]); ?></div>
    <?php endif; ?>
</div>

<footer>
    <p>🧃 Made by <a href="https://github.com/foxy4096">Foxy4096</a> – <a href="contact.php">Report a bug or a feature crime</a></p>
</footer>

</body>
</html>
