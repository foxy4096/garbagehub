<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    $to = "admin@garbagehub.com";
    $subject = "New Contact Form Submission";
    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $body = "Name: $name\n";
    $body .= "Email: $email\n";
    $body .= "Message:\n$message\n";

    mail($to, $subject, $body, $headers);
    $_SESSION['success'] = "Your message has been sent!";
    header("Location: contact.php");
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - GarbageHub</title>
    <style>
        /* Isolated feeling effect */
        body::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.95) 70%, #000 100%);
            z-index: -2;
        }
        /* Global Styles */
        body {
            font-family: 'Roboto', sans-serif;
            background: #2c3e50; /* Dark stormy background */
            color: #ecf0f1; /* Light text color */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            background-size: cover;
            position: relative;
            text-align: center;
            background-color: #212f3d;
        }

        /* Subtle flickering light effect */
        @keyframes flicker {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        /* Light flicker effect */
        h1 {
            font-size: 36px;
            color: #ffffff;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 20px;
            opacity: 0.9;
            animation: flicker 2s infinite alternate;
        }

        /* Adding some subtle grunge to the background */
        .background {
            background-image: url('static/assets/dirty-wall.jpg');
            background-size: cover;
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .container {
            position: relative;
            width: 100%;
            max-width: 500px;
            padding: 30px;
            background-color: rgba(0, 0, 0, 0.8); /* Dark, almost forgotten atmosphere */
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.7);
        }

        label {
            font-size: 18px;
            font-weight: bold;
            color: #ecf0f1;
            margin-bottom: 10px;
            display: block;
            text-align: left;
            opacity: 0.7;
        }

        input, textarea {
            width: 94%;
            padding: 12px;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #7f8c8d;
            margin-bottom: 20px;
            background-color: #34495e;
            color: #ecf0f1;
            transition: all 0.3s ease;
            outline: none;
        }

        input:focus, textarea:focus {
            border-color: #3498db;
            box-shadow: 0 0 10px rgba(52, 152, 219, 0.5);
        }

        button {
            background-color: #3498db;
            color: #fff;
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }

        button:hover {
            background-color: #2980b9;
        }

        p {
            font-size: 16px;
            color: #bdc3c7;
            margin-top: 20px;
            opacity: 0.6;
        }

        /* Animations for interaction */
        button:active {
            transform: scale(0.98);
            box-shadow: 0 0 15px rgba(52, 152, 219, 0.8);
        }
    </style>
</head>
<body onclick="document.getElementById('storm-sound').play()">

    <div class="background"></div>
    
    <div class="container">
        <h1>Contact Us</h1>
        <?php if (isset($_SESSION['success'])): ?>
            <p class="msg success"><?php echo htmlspecialchars($_SESSION['success']); ?></p>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form action="contact.php" method="POST">
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" required placeholder="Enter your name">

            <label for="email">Your Email:</label>
            <input type="email" id="email" name="email" required placeholder="Enter your email">

            <label for="message">Your Message:</label>
            <textarea id="message" name="message" rows="5" required placeholder="What do you want to say?"></textarea>

            <button type="submit">Send Message</button>
            <br>
            <br>
        </form>
        <a href="/">
            <button
            style="
                background-color: #e74c3c;
                color: white;
                border: 2px solid #c0392b;
                padding: 10px 15px;
                cursor: pointer;
                border-radius: 5px;
                font-size: 1em;
                box-shadow: inset 0 0 5px #c0392b, 2px 2px #e74c3c;
                background: linear-gradient(to bottom, #e74c3c, #c0392b);"
            >
                Back to Home
            </button>
        </a>
        <br>
        <audio loop autoplay controls id="storm-sound" style="display:none;">
  <source src="static/cursed_audio/storm-sound.mp3" type="audio/mp3">
  Your browser does not support the audio element.
</audio>

    </div>
    

</body>
</html>
