<?php
// faq.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - GarbageHub</title>
    <style>
        body {
            font-family: "Tahoma", sans-serif;
            background-color: #003366; /* Classic XP background */
            color: #000;
            margin: 0;
            padding: 20px;
            text-align: center;
            background-image: url('static/cursed_imgs/faq_bg.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        h1 {
            color: #ffffff;
            font-size: 48px;
            margin-top: 20px;
            padding: 10px;
            background-color: #0066cc; /* Windows XP header color */
            border-radius: 10px;
            box-shadow: 0 0 10px #000;
            text-shadow: 2px 2px 3px #000;
        }

        h2 {
            color: #003366;
            font-size: 28px;
            margin-top: 20px;
            text-shadow: 2px 2px 5px #ff6600;
            animation: glitch 1s infinite alternate;
        }

        @keyframes glitch {
            0% { text-shadow: 2px 2px 3px #ff0000, -2px -2px 0px #00ff00; }
            25% { text-shadow: -2px 2px 3px #00ff00, 2px -2px 0px #ff0000; }
            50% { text-shadow: 2px -2px 3px #0000ff, -2px 2px 0px #ffff00; }
            75% { text-shadow: -2px -2px 3px #ffff00, 2px 2px 0px #0000ff; }
            100% { text-shadow: 2px 2px 3px #ff0000, -2px -2px 0px #00ff00; }
        }

        .faq {
            background-color: #f0f0f0;
            border: 2px solid #003366; /* Border similar to Windows XP dialog boxes */
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.5);
            animation: fadeIn 1s ease-in-out;
        }

        .question {
            text-align: left;
            font-size: 22px;
            color: #003366;
            margin-bottom: 10px;
            text-decoration: underline;
            font-weight: bold;
            font-family: "Verdana", sans-serif;
        }

        .answer {
            font-size: 16px;
            color: #333333;
            font-family: "Tahoma", sans-serif;
            padding-left: 20px;
            text-align: left;
            animation: slideIn 1s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            50% { transform: rotate(5deg); }
            100% { transform: rotate(0deg); }
        }

        @keyframes slideIn {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        footer {
            margin-top: 30px;
            font-size: 14px;
            color: #0066cc;
            text-shadow: 2px 2px 2px #000;
        }

        footer a {
            color: #ff6600;
            text-decoration: none;
            font-weight: bold;
        }

        footer a:hover {
            color: #ff0000;
            text-decoration: underline;
        }

    </style>
</head>
<body>

<h1>Frequently Asked Questions</h1>

<div class="faq">
    <div class="question">What is GarbageHub?</div>
    <div class="answer">GarbageHub is a chaotic collection of broken, messy code. It's a place where bad code thrives and is celebrated. Perfectly imperfect, just like you!</div>
</div>

<div class="faq">
    <div class="question">How do I contribute to GarbageHub?</div>
    <div class="answer">Simply sign up and start uploading your worst code! We encourage mistakes, bugs, and things that defy logic. If it makes no sense, it's perfect!</div>
</div>

<div class="faq">
    <div class="question">Can I get help with my code here?</div>
    <div class="answer">Looking for clean, bug-free solutions? You might want to go elsewhere. But if you're after weird, nonsensical, or totally broken code, you've found the right place!</div>
</div>

<div class="faq">
    <div class="question">What do I get for donating?</div>
    <div class="answer">You'll feel a deep sense of satisfaction knowing you're helping keep GarbageHub alive. Plus, we'll make sure your donation goes toward more chaotic development and possibly more bugs!</div>
</div>

<footer>
    <p>Created by <a href="https://github.com/foxy4096">Foxy4096</a> – <a href="contact.php">Contact Us</a> for more madness!</p>
</footer>

</body>
</html>
