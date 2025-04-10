<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    $_SESSION["error"] = "Log in first, you idiot.";
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"];

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $_SESSION["error"] = "Invalid request, genius.";
    header("Location: dashboard.php");
    exit;
}

$repo_id = intval($_POST["repo_id"]);
$message = trim($_POST["message"]);

if (empty($message)) {
    $_SESSION["error"] = "A commit with no message? Try again.";
    header("Location: repo.php?id=$repo_id");
    exit;
}

// Get repo details
$stmt = $db->prepare("SELECT repo_name FROM repositories WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $repo_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$repo = $result->fetch_assoc();
$stmt->close();

if (!$repo) {
    $_SESSION["error"] = "That repo doesn't exist, or it's not yours.";
    header("Location: dashboard.php");
    exit;
}

$repo_name = htmlspecialchars($repo["repo_name"]);
$staged_path = "uploads/$username/$repo_name/staged/";
$commit_hash = hash('sha256', time() . $message);
$commit_path = "uploads/$username/$repo_name/commits/$commit_hash/";

// Ensure the commit directory exists
if (!mkdir($commit_path, 0777, true)) {
    $_SESSION["error"] = "Failed to create commit directory.";
    header("Location: repo.php?id=$repo_id");
    exit;
}

// Move files from staged to committed directory
function moveFiles($src, $dest) {
    if (!file_exists($src)) return [];
    $files = [];
    $items = scandir($src);
    foreach ($items as $item) {
        if ($item === "." || $item === "..") continue;
        $srcPath = "$src/$item";
        $destPath = "$dest/$item";

        if (is_dir($srcPath)) {
            mkdir($destPath, 0777, true);
            $files = array_merge($files, moveFiles($srcPath, $destPath));
        } else {
            rename($srcPath, $destPath);
            $files[] = str_replace("$dest/", "", $destPath);
        }
    }
    return $files;
}

$files = moveFiles($staged_path, $commit_path);

// Save commit to database
$stmt = $db->prepare("INSERT INTO commits (repo_id, user_id, commit_hash, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiss", $repo_id, $user_id, $commit_hash, $message);
$stmt->execute();
$commit_id = $stmt->insert_id;
$stmt->close();

// Save files to database
$stmt = $db->prepare("INSERT INTO files (commit_id, file_name, file_path) VALUES (?, ?, ?)");
foreach ($files as $file) {
    $file_name = basename($file); // Extract filename from full path
    $stmt->bind_param("iss", $commit_id, $file_name, $file);
    $stmt->execute();
}

$stmt->close();

// Clear the staging directory
function deleteDir($dir) {
    if (!file_exists($dir)) return;
    foreach (scandir($dir) as $file) {
        if ($file === "." || $file === "..") continue;
        $filePath = "$dir/$file";
        is_dir($filePath) ? deleteDir($filePath) : unlink($filePath);
    }
    rmdir($dir);
}
deleteDir($staged_path);
mkdir($staged_path, 0777, true); // Recreate empty staged directory

$_SESSION["message"] = "Commit successful. Your mess is now permanent.";
header("Location: repo.php?id=$repo_id");
exit;
?>
