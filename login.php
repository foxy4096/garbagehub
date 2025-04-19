<?php
session_start();
include "db.php";

$insult_messages = [
    "Can't even type your password right? Pathetic.",
    "Oh wow, failed to log in. Big surprise.",
    "You sure you even signed up?",
    "Maybe try 'password123'.",
    "If you forgot your password, too bad. We don’t do resets."
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if (empty($username) || empty($password)) {
        $_SESSION["error"] = "Fill the damn form.";
        header("Location: login.php");
        exit;
    }

    // Check user in DB
    $stmt = $db->prepare("SELECT id, password_hash, avatar FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($user_id, $password_hash, $avatar);
        $stmt->fetch();

        if (password_verify($password, $password_hash)) {
            $_SESSION["user_id"] = $user_id;
            $_SESSION["username"] = $username;
            $_SESSION["avatar"] = $avatar;
            $_SESSION["message"] = "Oh great, you're back. Lucky us.";
            header("Location: dashboard.php");
            exit;
        }
    }

    $_SESSION["error"] = $insult_messages[array_rand($insult_messages)];
    header("Location: login.php");
    exit;
}

$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GARBAGEHUB LOGIN - THE INTERNET WON’T BE THE SAME</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Background of pure chaos */
        body {
            margin: 0;
            padding: 0;
            background: url('static/cursed_imgs/smirk.gif') repeat; /* Cursed animated background */
            background-size: 150px;
            font-family: 'Comic Sans MS', sans-serif; /* That nostalgic, painful font */
            color: #ff00ff;
            text-align: center;
            font-size: 20px;
        }

        h1 {
            font-size: 50px;
            margin-top: 100px;
            color: #ffcc00;
            text-shadow: 5px 5px #ff0000, -5px -5px #0000ff;
            animation: glitch 1.5s infinite linear;
        }

        /* Neon flickering effect for the title */
        @keyframes glitch {
            0% { text-shadow: 1px 1px #ff0000, -1px -1px #0000ff; }
            25% { text-shadow: 2px 2px #ff00ff, -2px -2px #00ff00; }
            50% { text-shadow: 3px 3px #ffcc00, -3px -3px #ff0000; }
            75% { text-shadow: 4px 4px #ff00ff, -4px -4px #00ff00; }
            100% { text-shadow: 5px 5px #ff0000, -5px -5px #0000ff; }
        }

        /* Form container with gradient madness */
        form {
            background: linear-gradient(45deg, #ff0000, #0000ff, #ffcc00);
            padding: 30px;
            border: 5px dashed #ff00ff;
            box-shadow: 0 0 30px 10px #00ff00;
            display: inline-block;
            margin-top: 50px;
            border-radius: 15px;
            animation: color-blink 3s infinite;
        }

        /* Button style that will make your eyes hurt */
        button {
            background: #ff00ff;
            color: #fff;
            padding: 15px 30px;
            font-size: 18px;
            border: 2px solid #ffcc00;
            border-radius: 8px;
            text-transform: uppercase;
            box-shadow: 0 0 10px #ff0000, 0 0 20px #ffcc00;
            transition: all 0.5s ease;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background: #0000ff;
            color: #ff0000;
            box-shadow: 0 0 15px #00ff00, 0 0 25px #ff0000;
        }

        /* Input fields that feel like 2005 */
        input[type="text"], input[type="password"] {
            width: 280px;
            padding: 12px;
            margin-bottom: 20px;
            background-color: #111;
            border: 3px solid #ff00ff;
            border-radius: 8px;
            color: #00ff00;
            font-size: 18px;
            text-align: center;
        }

        input::placeholder {
            color: #ffcc00;
        }

        /* Crazy blinking animations on inputs */
        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #ff0000;
            animation: input-glitch 1s infinite;
        }

        @keyframes input-glitch {
            0% { background-color: #111; }
            25% { background-color: #ff00ff; }
            50% { background-color: #0000ff; }
            75% { background-color: #00ff00; }
            100% { background-color: #111; }
        }

        /* Random, completely pointless info text */
        .disclaimer {
            font-size: 18px;
            color: #ffcc00;
            font-weight: bold;
            margin-top: 30px;
        }

        /* Links styled like underlined vomit */
        a {
            color: #ff0000;
            text-decoration: underline;
            font-size: 18px;
            font-weight: bold;
        }

        a:hover {
            color: #00ff00;
            text-decoration: none;
        }

        /* Cursed error message */
        .error-message {
            color: #ff3300;
            background-color: #000000;
            padding: 10px;
            margin-top: 20px;
            border: 2px solid #ff0000;
            font-size: 20px;
            font-weight: bold;
        }

        /* Add even more randomness */
        .spooky {
            font-family: 'Papyrus', sans-serif;
            color: #ffcc00;
            text-transform: uppercase;
            font-size: 40px;
            letter-spacing: 10px;
        }
    </style>
</head>
<body>

    <h1>GARBAGEHUB LOGIN PORTAL</h1>
    <p class="spooky">YOU WILL REGRET THIS.</p>

    <?php if (isset($_SESSION["error"])): ?>
        <div class="error-message"><?php echo $_SESSION["error"]; unset($_SESSION["error"]); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION["message"])): ?>
        <div class="error-message"><?php echo $_SESSION["message"]; unset($_SESSION["message"]); ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username" placeholder="ENTER YOUR WASTED SOUL" required>

        <label>Password:</label>
        <input type="password" name="password" placeholder="GOOD LUCK REMEMBERING IT" required>
        
        <button type="submit">ENTER THE CHAOS</button>
    </form>

    <p class="disclaimer">Need to suffer more? <a href="signup.php">Sign up, because why not?</a></p>

</body>
</html>
