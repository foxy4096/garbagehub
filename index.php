<?php
$insults = [
    "Why are you here? This place is a dump.",
    "Welcome to GarbageHub, where bad code gets worse.",
    "GitHub wouldn't take your code? We won't either, but you can still put it here.",
    "Leave all hope behind, you who enter here.",
    "The only repo here is regret.",
    "404: Code quality not found.",
    "You are one commit away from total disaster.",
];

$random_insult = $insults[array_rand($insults)];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GarbageHub - Home of Bad Code</title>
</head>
<body>
    <h1>GarbageHub 💀</h1>
    <p><?= $random_insult ?></p>

    <h2>What is this?</h2>
    <p>GarbageHub is a home for your cursed, unmaintainable, and barely functional code. 
    You write crimes against programming, we store them.</p>

    <h2>Features (That Probably Don't Work)</h2>
    <ul>
        <li>✅ Host your spaghetti code</li>
        <li>✅ Version control that barely functions</li>
        <li>✅ Commit messages nobody will read</li>
        <li>✅ Guaranteed regret</li>
    </ul>

    <h2>Get Started</h2>
    <p><a href="signup.php">Sign Up</a> or <a href="login.php">Log In</a> (Why? I have no idea.)</p>

    <footer>
        <p><strong>GarbageHub:</strong> "It is garbage, it is for the garbage, it is of the garbage."</p>
    </footer>
</body>
</html>
