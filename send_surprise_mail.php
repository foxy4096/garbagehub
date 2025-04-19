<?php

session_start();

require "db.php";


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
$user_id = $_SESSION['user_id'];
$query = 'SELECT username, email, avatar FROM users WHERE id = ?';
$stmt = $db->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();

$subject = "Surprise!";
$body = "Hello " . $user['username'] . ",\n\nWe have a surprise for you! Check your account for more details.\n\nBest regards,\nThe Team";
$headers = "From:
" . $user['email'] . "\r\n" .
           "Reply-To: " . $user['email'] . "\r\n" .
           "X-Mailer: PHP/" . phpversion();
$to = $user['email'];



if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if (mail($to, $subject, $body, $headers)) {
        $_SESSION['message'] = "Email sent successfully to " . $user['username'] . " at " . $user['email'];
        header('Location: edit_profile.php');
    } else {
        $_SESSION['error'] = "Failed to send email. Please try again later.";
        header('Location: edit_profile.php');
    }
} else {
    echo "Invalid request method.";
    exit();
}