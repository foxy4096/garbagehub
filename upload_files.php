<?php

echo json_encode($_FILES);

exit;
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    $_SESSION["error"] = "Log in first, you idiot.";
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"];

if (!isset($_POST["repo_id"])) {
    $_SESSION["error"] = "Which repo? Are you drunk?";
    header("Location: dashboard.php");
    exit;
}

$repo_id = intval($_POST["repo_id"]);

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
$upload_base = "uploads/$username/$repo_name/staged/";

// Ensure the base directory exists
if (!file_exists($upload_base)) {
    mkdir($upload_base, 0777, true);
}

// Process uploaded files
$files = $_FILES["files"];
$total = count($files["name"]);

for ($i = 0; $i < $total; $i++) {
    $file_name = $files["name"][$i];
    $full_path = $files["full_path"][$i]; // The relative path from the original folder
    $tmp_name = $files["tmp_name"][$i];

    // Reconstruct directory structure
    $target_path = $upload_base . $full_path;

    // Create parent directories if they don't exist
    $target_dir = dirname($target_path);
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Move uploaded file
    if (move_uploaded_file($tmp_name, $target_path)) {
        echo "Uploaded: $full_path\n";
    } else {
        echo "Failed: $full_path\n";
    }
}

$_SESSION["message"] = "Files uploaded successfully.";
header("Location: add_files.php?repo_id=$repo_id");
exit;
?>
