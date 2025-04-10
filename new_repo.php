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
</head>
<body>
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
</body>
</html>
