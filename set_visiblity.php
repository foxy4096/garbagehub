<?php
// set_visibility.php
session_start();
include "db.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $repo_id = $data['repo_id'];
    $is_public = $data['is_public'];

    // Validate input
    if (!isset($repo_id) || !isset($is_public)) {
        echo json_encode(['success' => false, 'message' => 'Invalid input.']);
        exit;
    }

    // Update visibility in the database
    $stmt = $db->prepare("UPDATE repositories SET is_public = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("iii", $is_public, $repo_id, $_SESSION["user_id"]);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'data' => ['is_public' => $is_public]]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update visibility.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}