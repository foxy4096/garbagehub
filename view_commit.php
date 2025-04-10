<?php
session_start();
include "db.php";

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    $_SESSION["error"] = "You ain't logged in. What are you even doing?";
    header("Location: login.php");
    exit;
}

// Get commit hash from the URL
if (!isset($_GET["commit_hash"])) {
    $_SESSION["error"] = "Commit hash missing. Are you serious?";
    header("Location: dashboard.php");
    exit;
}

$commit_hash = $_GET["commit_hash"];
$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"];

// Fetch commit details using the commit hash
$stmt = $db->prepare("
    SELECT c.commit_hash, c.message, c.created_at, r.repo_name, r.id as repo_id
    FROM commits c
    JOIN repositories r ON c.repo_id = r.id
    WHERE c.commit_hash = ? AND r.user_id = ?
");
$stmt->bind_param("si", $commit_hash, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$commit = $result->fetch_assoc();
$stmt->close();

// If the commit doesn't exist or isn't from this user, deny access
if (!$commit) {
    $_SESSION["error"] = "That commit doesn't exist, or it's not yours.";
    header("Location: dashboard.php");
    exit;
}

// Fetch the files related to this commit
$stmt = $db->prepare("SELECT file_name, file_path FROM files WHERE commit_id = (SELECT id FROM commits WHERE commit_hash = ?)");
$stmt->bind_param("s", $commit_hash);
$stmt->execute();
$files_result = $stmt->get_result();
$files = $files_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$db->close();

// Prepare the full repo name
$repo_name = htmlspecialchars($commit["repo_name"]);
$commit_message = htmlspecialchars($commit["message"]);
$created_at = htmlspecialchars($commit["created_at"]);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Commit - <?= $repo_name ?> - GarbageHub</title>
</head>

<body>
    <h1>Commit Details - <?= $repo_name ?></h1>
    <p><strong>Commit Hash:</strong> <?= htmlspecialchars($commit["commit_hash"]) ?></p>
    <p><strong>Message:</strong> <?= $commit_message ?></p>
    <p><strong>Committed on:</strong> <?= $created_at ?></p>

    <h2>Files Changed</h2>
    <?php if (empty($files)): ?>
        <p>No files were changed in this commit.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($files as $file): ?>
                <li>
                    <strong><a href="view_file.php?hash=<?=$commit_hash?>&file=<?= $file['file_path']?>"><?= htmlspecialchars($file['file_name']) ?></a></strong>
                    <p>Path: <?= htmlspecialchars($file['file_path']) ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <p><a href="repo.php?id=<?= $commit['repo_id'] ?>">Back to Repo</a></p>
</body>

</html>
