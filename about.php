<?php
// about.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - GarbageHub</title>
    <style>
        body {
            font-family: "Comic Sans MS", "Comic Sans", cursive;
            background-color: #111;
            color: #00ff00;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #ff4500;
            font-size: 50px;
            margin-top: 20px;
            animation: glitch 1s infinite;
        }

        @keyframes glitch {
            0% { text-shadow: 2px 2px #ff0000, -2px -2px #00ff00; }
            25% { text-shadow: -2px 2px #00ff00, 2px -2px #0000ff; }
            50% { text-shadow: 2px -2px #0000ff, -2px 2px #ff0000; }
            75% { text-shadow: -2px -2px #ff0000, 2px 2px #00ff00; }
            100% { text-shadow: 2px 2px #00ff00, -2px -2px #0000ff; }
        }

        h2 {
            color: #ff4500;
            font-size: 32px;
            text-align: center;
            margin-top: 30px;
        }

        p {
            font-size: 18px;
            line-height: 1.6;
            text-align: center;
            color: #fff;
            text-shadow: 1px 1px #ff0000;
        }

        .cursed-box {
            background-color: #222;
            color: #ff0000;
            padding: 20px;
            border: 3px dashed #ff4500;
            margin-top: 30px;
            text-align: center;
            font-size: 20px;
            box-shadow: 0 0 20px #ff4500;
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

        footer {
            text-align: center;
            margin-top: 30px;
            font-size: 14px;
            color: #ff4500;
            text-shadow: 1px 1px 3px #000;
        }

        a {
            color: #ff4500;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            color: #00ff00;
            text-shadow: 2px 2px #ff0000;
        }
    </style>
</head>
<body>

<h1>About GarbageHub</h1>

<h2>The Home of the Worst Code on the Web</h2>

<p>Welcome to GarbageHub, the site where bad code goes to die—and sometimes it even gets resurrected! Whether you're working on a project that has been abandoned in a dark corner of the internet or you're just learning how to break things, this is the place for you.</p>

<div class="cursed-box">
    <p><strong>GarbageHub:</strong> 'Where every commit is a step closer to disaster.'</p>
    <p>Submit your most cursed code and bask in the glory of your questionable decisions. At GarbageHub, we believe that the messier, the better.</p>
</div>

<h2>Our Mission</h2>
<p>GarbageHub isn't just a repository—it's a community. A community where we embrace failure and laugh in the face of quality assurance. We believe that every bad line of code is a work of art, and every bug is a feature in disguise.</p>

<h2>Contribute</h2>
<p>If you think you can create the worst possible code, <a href="signup.php">sign up</a> today and contribute to the madness. Upload your projects, share your mistakes, and become part of something... *questionable*.</p>

<footer>
    <p><strong>GarbageHub</strong> is a chaotic, unreliable platform, created by developers who thought they knew better. Proceed with caution and enjoy the chaos.</p>
    <p>Need help? <a href="contact.php">Contact us</a> or check out our <a href="faq.php">FAQ</a>.</p>
</footer>

</body>
</html>
