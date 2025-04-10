<?php
// view_file.php
include "db.php";

// Define the uploads directory
$uploadsDir = __DIR__ . '/uploads/';

// Get the hash and file path from the request
$hash = $_GET['hash'] ?? null;
$filePath = $_GET['file'] ?? null;

// Validate inputs
if (!$hash || !$filePath) {
    die('Invalid request. Hash and file path are required.');
}
// Get the username and repo name from the hash
$stmt = $db->prepare("
    SELECT r.repo_name, u.username
    FROM commits c
    JOIN repositories r ON c.repo_id = r.id
    JOIN users u ON r.user_id = u.id
    WHERE c.commit_hash = ?
");
$stmt->bind_param("s", $hash);
$stmt->execute();
$result = $stmt->get_result();
$commit = $result->fetch_assoc();
$stmt->close();

if (!$commit) {
    die('Invalid commit hash or file path.');
}

$username = htmlspecialchars($commit["username"]);
// Construct the uploads directory path
$uploadsDir = "uploads/" . $username . "/" . $commit["repo_name"] . "/commits/" . $hash . "/";
// Ensure the uploads directory exists
if (!file_exists($uploadsDir)) {
    die('Uploads directory does not exist.');
}

// Construct the full file path
$fullFilePath = $uploadsDir . $filePath;
echo $fullFilePath;
// Ensure the file exists
if (!file_exists($fullFilePath)) {
    die('File does not exist.');
}

// Just stream the file to the browser
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
header('Content-Length: ' . filesize($fullFilePath));
readfile($fullFilePath);
