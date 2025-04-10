<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized. Go log in."]);
    exit;
}

if (!isset($_GET["repo_id"])) {
    http_response_code(400);
    echo json_encode(["error" => "Repo ID is missing. How'd you mess this up?"]);
    exit;
}

$repo_id = intval($_GET["repo_id"]);
$user_id = $_SESSION["user_id"];

// Get repo details
$stmt = $db->prepare("SELECT repo_name FROM repositories WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $repo_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$repo = $result->fetch_assoc();
$stmt->close();

if (!$repo) {
    http_response_code(404);
    echo json_encode(["error" => "Repo not found."]);
    exit;
}

$repo_name = $repo["repo_name"];
$repo_path = "uploads/{$_SESSION['username']}/$repo_name/staged/";

function getDirectoryTree($dir)
{
    $result = [];

    if (!is_dir($dir))
        return $result;

    foreach (scandir($dir) as $item) {
        if ($item === "." || $item === "..")
            continue;
        $path = "$dir/$item";
        $result[] = is_dir($path) ? [
            "name" => $item,
            "type" => "folder",
            "children" => getDirectoryTree($path)
        ] : [
            "name" => $item,
            "type" => "file"
        ];
    }

    return $result;
}

header("Content-Type: application/json");
echo json_encode(["repo" => "$_SESSION[username]/$repo_name", "tree" => getDirectoryTree($repo_path)], JSON_PRETTY_PRINT);
