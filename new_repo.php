<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    $_SESSION["error"] = "You need to log in first, dumbass.";
    header("Location: login.php");
    exit;
}

$repo_messages = [
    "Congratulations, you just made another mistake.",
    "Another repo? Haven’t you done enough damage?",
    "GitHub rejected you, so here you are.",
    "Welcome to your new disaster zone.",
    "Hope you enjoy maintaining this mess."
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $repo_name = trim($_POST["repo_name"]);
    $description = trim($_POST["description"]);
    $is_public = isset($_POST["is_public"]) ? 1 : 0;
    $user_id = $_SESSION["user_id"];

    if (empty($repo_name)) {
        $_SESSION["error"] = "A repo without a name? Genius.";
        header("Location: new_repo.php");
        exit;
    }

    $stmt = $db->prepare("INSERT INTO repositories (user_id, repo_name, description, is_public) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $repo_name, $description, $is_public);

    if ($stmt->execute()) {
        $_SESSION["message"] = $repo_messages[array_rand($repo_messages)];
        header("Location: dashboard.php");
        exit;
    } else {
        $_SESSION["error"] = "Failed to create repo. Guess you're just unlucky.";
    }

    $stmt->close();
}
$db->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create a Repo - GarbageHub</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #c1dff0;
            color: #333;
            margin: 0;
            padding: 0;
        }

        h1 {
            color: #ff0000;
            text-shadow: 2px 2px #000;
        }

        a {
            color: #00ffff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: blink;
            color: #ff00ff;
        }

        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], textarea {
            width: 90%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #00ff00;
            color: #000;
            font-size: 18px;
            padding: 10px 20px;
            border: 2px solid #ff4500;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #ff00ff;
            color: #000;
        }
        p {
            font-size: 14px;
            color: #555;
        }
        .error {
            color: red;
        }
        .success {
            color: green;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #e3f2fa;
            border: 2px solid #6fa3c1;
            border-radius: 10px;
        }
        </style>
</head>
<body>
    <div class="container">
    <h1>Create Your Garbage Repository</h1>

    <?php if (isset($_SESSION["message"])): ?>
        <p style="color: green;"><?= $_SESSION["message"]; unset($_SESSION["message"]); ?></p>
    <?php endif; ?>

    <?php if (isset($_SESSION["error"])): ?>
        <p style="color: red;"><?= $_SESSION["error"]; unset($_SESSION["error"]); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Repository Name:</label>
        <input type="text" name="repo_name" required><br>

        <label>Description:</label>
        <textarea name="description"></textarea><br>

        <label>Public?</label>
        <input type="checkbox" name="is_public" checked> (Uncheck to hide your crimes)<br>

        <button type="submit">Create the Mess</button>
    </form>

    <p><a href="dashboard.php">Go back before it's too late</a></p>
    </div>
</body>
</html>
