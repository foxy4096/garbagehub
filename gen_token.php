<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    $_SESSION["error"] = "You need to be logged in to view this page.";
    header("Location: login.php");
    exit;
}

// Include database connection
include "db.php";

// Only one token per user

// Check if the user already has a token

$user_id = $_SESSION["user_id"];
$stmt = $db->prepare("SELECT token FROM tokens WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$token = $result->fetch_assoc();
$stmt->close();

if ($token) {
    $_SESSION["error"] = "You already have a token. You can only have one.";
    header("Location: settings.php");
    exit;
}

// Generate a new token

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = bin2hex(random_bytes(16)); // Generate a random token
    $stmt = $db->prepare("INSERT INTO tokens (user_id, token) VALUES (?, ?)");
    $stmt->bind_param("is", $user_id, $token);
    if ($stmt->execute()) {
        $_SESSION["message"] = "Token generated successfully!";
    } else {
        $_SESSION["error"] = "Failed to generate token. Please try again.";
    }
    $stmt->close();
    header("Location: gen_token.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        GarbageHub - Token Generation
    </title>
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
        <a href="dashboard.php">Dashboard</a>
    </nav>
    <h1>Generate Token</h1>
    <p>Use this token to authenticate your requests.</p>

    <?php if (isset($_SESSION["error"])): ?>
        <p style="color: red;"><?= $_SESSION["error"]; unset($_SESSION["error"]); ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION["message"])): ?>
        <p style="color: green;"><?= $_SESSION["message"]; unset($_SESSION["message"]); ?></p>
    <?php endif; ?>
<style>
    .gen_btn {
        background: linear-gradient(45deg, #ff0000, #000000);
        border: 3px dotted #ff00ff;
        color: #00ff00;
        font-family: "Papyrus", fantasy, sans-serif;
        font-size: 1.5em;
        padding: 15px 30px;
        cursor: crosshair;
        text-shadow: 3px 3px 5px #0000ff;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.8), inset 0 0 20px rgba(255, 255, 0, 0.5);
        backdrop-filter: blur(10px) brightness(1.2);
        /* transform: skew(-10deg) rotate(5deg); */
        transition: all 0.5s cubic-bezier(0.68, -0.55, 0.27, 1.55);
    }

    .gen_btn:hover {
        background: rgba(255, 0, 0, 0.2);
        color: #ffffff;
        border-color: #ffffff;
        text-shadow: 2px 2px 4px #ff0000;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.7), inset 0 0 15px rgba(255, 255, 255, 0.5);
        transform: scale(1.1) rotate(-2deg);
    }

    body {
        font-family: Arial, sans-serif;
        background: linear-gradient(to bottom, #1e3c72, #2a5298);
        color: #ffffff;
        margin: 0;
        padding: 0;
        text-align: center;
    }

    nav a {
        color: #ffffff;
        text-decoration: none;
        margin: 0 15px;
        font-weight: bold;
    }

    nav a:hover {
        text-decoration: underline;
    }

    h1 {
        font-size: 2.5em;
        margin-top: 20px;
    }

    p {
        font-size: 1.2em;
        margin: 10px 0;
    }

    form {
        margin: 20px 0;
    }

    a {
        color: #00ffcc;
    }

    a:hover {
        color: #ffcc00;
    }
</style>
 </style>
    <form method="POST">
        <input type="submit" class="gen_btn" value="Generate Token">
    </form>
    <p><a href="settings.php">Back to Settings</a></p>
    <p>Why did you had to come all the way here?</p>
    <p>Lol, we just hate you.</p>
    </p>
</body>
</html>