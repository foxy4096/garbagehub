<?php
// contact.php

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $message = htmlspecialchars($_POST['message']);

    // Basic validation
    if (!empty($name) && !empty($email) && !empty($message)) {
        // Example: Send an email (you can replace this with your logic)
        $to = "your-email@example.com";
        $subject = "New Contact Form Submission";
        $body = "Name: $name\nEmail: $email\nMessage: $message";
        $headers = "From: $email";

        if (mail($to, $subject, $body, $headers)) {
            echo "Message sent successfully!";
        } else {
            echo "Failed to send the message.";
        }
    } else {
        echo "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        /* Make it impossibe for the used to submit */
        body {
            font-family: "Comic Sans MS", "Comic Sans", cursive;
            background-color: #000;
            color: #0f0;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
            color: #ff4500;
            font-size: 32px;
            margin-top: 20px;
            text-shadow: 2px 2px 5px #000;
        }

        form {
            transform: rotate(5deg);
            animation: colorShift 3s infinite alternate;
        }

        input, textarea, button {
            font-family: "Wingdings", cursive;
            background-color: #ff0;
            color: #00f;
            border: 2px dashed #f00;
            padding: 10px;
            margin: 5px;
            box-shadow: 5px 5px 15px #0f0;
        }

        button:hover {
            transform: scale(1.2) rotate(10deg);
            background-color: #f0f;
            color: #fff;
        }

        @keyframes colorShift {
            0% {
            background-color: #ff0;
            color: #00f;
            }
            100% {
            background-color: #f0f;
            color: #0ff;
            }
        }
        
    </style>
</head>
<body>
    <h1>Contact Us</h1>
    <form action="contact.php" method="POST">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>

        <label for="message">Message:</label><br>
        <textarea id="message" name="message" rows="5" required></textarea><br><br>

        <button type="submit">Send</button>
    </form>
    <script>
        // hehe
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.style.backgroundColor = '#000';
                    this.style.color = '#fff';
                    this.style.border = '5px solid #ff00ff';
                    this.style.transform = 'scale(1.2) rotate(-10deg)';
                    this.style.transition = 'all 0.3s ease-in-out';
                });
                input.addEventListener('blur', function() {
                    this.style.backgroundColor = '';
                    this.style.color = '';
                    this.style.border = '';
                    this.style.transform = '';
                });
            });
        });
        // Make it more chaotic
        document.addEventListener('mousemove', function(e) {
            const x = e.clientX;
            const y = e.clientY;
            const elements = document.querySelectorAll('input, textarea, button');
            elements.forEach(el => {
                el.style.transform = `translate(${x}px, ${y}px) rotate(${Math.random() * 360}deg)`;
                el.style.transition = 'transform 0.5s ease-in-out';
            });
        });
    </script>
</body>
</html>