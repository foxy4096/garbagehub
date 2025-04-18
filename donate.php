<?php
// donate.php

// Start session
session_start();

// Include database connection
require_once 'db.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $amount = floatval($_POST['amount']);

    // Validate input
    if (!empty($name) && filter_var($email, FILTER_VALIDATE_EMAIL) && $amount > 0) {
        // Insert donation into database
        $stmt = $conn->prepare("INSERT INTO donations (name, email, amount) VALUES (?, ?, ?)");
        $stmt->bind_param("ssd", $name, $email, $amount);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Thank you for your donation!";
        } else {
            $_SESSION['error'] = "Failed to process your donation. Please try again.";
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Please fill in all fields correctly.";
    }

    // Redirect back to the form
    header("Location: donate.php");
    exit();
}

// There are some cursed images stored /static/cursed_imgs/, randomly select one for the background
$cursed_images = glob('static/cursed_imgs/*.{jpg,jpeg,png,gif}', GLOB_BRACE);

if ($cursed_images) {
    $random_image = $cursed_images[array_rand($cursed_images)];
} else {
    $random_image = 'static/cursed_imgs/default.jpg'; // Fallback image
}

// just get a random color
$colors = ['#ff0000', '#00ff00', '#0000ff', '#ffff00', '#ff00ff', '#00ffff'];
$random_color = $colors[array_rand($colors)];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate</title>
    <style>

        body {
            font-family: "Comic Sans MS", "Comic Sans", cursive;
            background-color: <?php echo $random_color; ?>;               
            color: #0f0;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #ff4500;
            font-size: 32px;
            margin-top: 20px;
        }

        form {
            max-width: 400px;
            margin: auto;
            background-color: #222;
            padding: 20px;
            border-radius: 5px;
        }

        label {
            display: block;
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #555;
        }

        button {
            background-color: #00ff00;
            color: #000;
            font-size: 18px;
            padding: 10px 20px;
            border: 2px solid #ff4500;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #ff00ff;
            transform: rotate(360deg) scale(1.5);
            transition: all 0.2s ease-in-out;
        }

        input[type="text"],
        input[type="email"],
        input[type="number"] {
            background-color: #ff0000;
            color: #00ff00;
            font-size: 20px;
            font-family: "Papyrus", fantasy;
            border: 5px dashed #0000ff;
        }

        form {
            background-image: url('<?php echo $random_image; ?>');
            background-size: cover;
            color: #ffff00;
            text-shadow: 2px 2px #ff0000;
        }


        form:hover {
            animation: shake 0.5s infinite;
        }

        @keyframes shake {
            0% { transform: translate(1px, 1px) rotate(0deg); }
            10% { transform: translate(-1px, -2px) rotate(-1deg); }
            20% { transform: translate(-3px, 0px) rotate(1deg); }
            30% { transform: translate(3px, 2px) rotate(0deg); }
            40% { transform: translate(1px, -1px) rotate(1deg); }
            50% { transform: translate(-1px, 2px) rotate(-1deg); }
            60% { transform: translate(-3px, 1px) rotate(0deg); }
            70% { transform: translate(3px, 1px) rotate(-1deg); }
            80% { transform: translate(-1px, -1px) rotate(1deg); }
            90% { transform: translate(1px, 2px) rotate(0deg); }
            100% { transform: translate(1px, -2px) rotate(-1deg); }
        }

        button {
            animation: color-change 1s infinite;
        }

        @keyframes color-change {
            0% { background-color: #ff0000; }
            25% { background-color: #00ff00; }
            50% { background-color: #0000ff; }
            75% { background-color: #ffff00; }
            100% { background-color: #ff00ff; }
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="number"]:focus {
            background-color: #000;
            color: #fff;
            border: 5px solid #ff00ff;
            transform: scale(1.2) rotate(-10deg);
            transition: all 0.3s ease-in-out;
        }

        h1 {
            animation: glitch 1s infinite;
        }

        @keyframes glitch {
            0% { text-shadow: 2px 2px #ff0000, -2px -2px #00ff00; }
            25% { text-shadow: -2px 2px #00ff00, 2px -2px #0000ff; }
            50% { text-shadow: 2px -2px #0000ff, -2px 2px #ff0000; }
            75% { text-shadow: -2px -2px #ff0000, 2px 2px #00ff00; }
            100% { text-shadow: 2px 2px #00ff00, -2px -2px #0000ff; }
        }
</style>
</head>
<body>
    <h1>Make a Donation</h1>

    <?php
    if (isset($_SESSION['success'])) {
        echo "<p style='color: green;'>{$_SESSION['success']}</p>";
        unset($_SESSION['success']);
    }

    if (isset($_SESSION['error'])) {
        echo "<p style='color: red;'>{$_SESSION['error']}</p>";
        unset($_SESSION['error']);
    }
    ?>

    <form action="donate.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="amount">Donation Amount:</label>
        <input type="number" id="amount" name="amount" step="0.01" required><br><br>

        <button type="submit">Donate</button>
    </form>
    <p>
        <a href="index.php">Back to Home</a>
        
    </p>
</body>
</html>