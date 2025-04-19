<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<marquee><h1 style='color: red; font-family: Comic Sans MS; text-shadow: 2px 2px 5px black;'>Womp Womp!</h1></marquee>";
    echo "<p style='color: purple; font-family: Papyrus; font-size: 20px; text-align: center; background: linear-gradient(to right, yellow, pink); padding: 10px; border: 5px dashed lime;'>Nice try, l Bozo. Your account isn't going anywhere!</p>";
    echo "<p style='color: orange; font-family: Impact; font-size: 18px; text-align: center;'>Oh no! Did you really think it would be that easy?</p>";
    echo "<p style='color: teal; font-family: Arial; font-size: 16px; text-align: center;'>Your account is safe and sound. Better luck next time!</p>";
    echo "<p style='color: brown; font-family: Georgia; font-size: 14px; text-align: center;'>Pro tip: Try contacting support if you're serious about this.</p>";
    echo "<p style='color: blue; font-family: Verdana; font-size: 12px; text-align: center;'>If you want to go back, click <a href='settings.php' style='color: red;'>here</a>.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account</title>
    <style>
        header {
            background-color: #002868;
            color: #ffffff;
            padding: 10px;
            text-align: center;
            font-family: 'Times New Roman', Times, serif;
            font-size: 24px;
            border-bottom: 5px solid #bf0a30;
        }
        footer {
            background-color: #002868;
            color: #ffffff;
            padding: 10px;
            text-align: center;
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
            border-top: 5px solid #bf0a30;
        }
        body {
            background-color: #f0f0f0;
            font-family: 'Arial', sans-serif;
        }
        h1 {
            color: #002868;
            text-align: center;
            font-family: 'Georgia', serif;
        }
        button {
            background-color: #bf0a30;
            color: #ffffff;
            font-size: 18px;
            font-family: 'Arial', sans-serif;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #002868;
            color: #ffffff;
        }
        body {
            background-color: #ffcccb;
            font-family: 'Comic Sans MS', cursive, sans-serif;
        }
        h1 {
            color: blue;
            text-align: center;
            text-decoration: underline wavy red;
        }
        button {
            background-color: lime;
            color: black;
            font-size: 20px;
            font-family: 'Papyrus', fantasy;
            border: 3px solid red;
            padding: 10px 20px;
            cursor: pointer;
            box-shadow: 5px 5px 10px black;
        }
        button:hover {
            background-color: yellow;
            color: red;
            transform: rotate(10deg);
        }
    </style>
</head>
<body>
    <header>
        <h1>Account Deletion</h1>
    </header>
    <main>
        <h1>Are you sure you want to delete your account?</h1>
        <form method="POST" action="delete_account.php">
            <button type="submit">Yes, delete my account</button>
        </form>
        <p>If you change your mind
, you can always come back and log in again.</p>
        <p><a href="settings.php">Back to Settings</a></p>
    </main>
    <footer>
        <p>&copy; 2023 Your Website. All rights reserved.</p>
    </footer>
</body>
</html>