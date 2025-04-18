<?php
// update_description.php
session_start();
include "db.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $repo_id = $data['repo_id'];
    $description = $data['description'];

    // Validate input
    if (!isset($repo_id) || !isset($description)) {
        echo json_encode(['success' => false, 'message' => 'Invalid input.']);
        exit;
    }

    // Update visibility in the database
    $stmt = $db->prepare("UPDATE repositories SET description = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("sii", $description, $repo_id, $_SESSION["user_id"]);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true, 'data' => ['description' => $description]]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update description.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}