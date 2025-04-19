<?php
require_once 'db.php';
session_start();

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $avatar = trim($_POST['avatar']);

    if (empty($username) || empty($email) || empty($avatar)) {
        $error = 'All fields are required.';
    } else {
        $update_query = 'UPDATE users SET username = ?, email = ?, avatar = ? WHERE id = ?';
        $update_stmt = $db->prepare($update_query);
        $update_stmt->bind_param('sssi', $username, $email, $avatar, $user_id);

        if ($update_stmt->execute()) {
            $success = "Profile updated successfully.";
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;
            $_SESSION['avatar'] = $avatar;
        } else {
            $error = 'Failed to update profile.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <title>Edit Profile — Sandstorm Mode</title>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <style>
        .duct-tape {
            position: absolute;
            width: 100px;
            height: 20px;
            background: #ccc;
            border: 1px solid #999;
            transform: rotate(-10deg);
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
            z-index: 10;
        }

        .duct-tape:nth-child(1) {
            top: 10%;
            left: 20%;
            transform: rotate(-15deg);
        }

        .duct-tape:nth-child(2) {
            top: 30%;
            left: 50%;
            transform: rotate(10deg);
        }

        .duct-tape:nth-child(3) {
            top: 70%;
            left: 40%;
            transform: rotate(-5deg);
        }

        .duct-tape:nth-child(4) {
            top: 50%;
            left: 80%;
            transform: rotate(20deg);
        }

        body {
            background: url('static/cursed_imgs/white-noise.gif') repeat;
            color: #00ff00;
            font-family: 'Comic Sans MS', cursive, sans-serif;
            overflow: hidden;
        }

        .sandstorm {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('static/cursed_images/sandstorm.gif') repeat;
            opacity: 0.2;
            z-index: -1;
            animation: spin 10s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        form {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-5deg);
            background: rgba(0, 0, 0, 0.8);
            padding: 20px;
            border: 5px dashed #ff0000;
            box-shadow: 0 0 20px #ff0000;
            animation: glitch 1s infinite;
        }

        @keyframes glitch {
            0% {
                transform: translate(-50%, -50%) rotate(-5deg);
            }

            25% {
                transform: translate(-48%, -52%) rotate(-3deg);
            }

            50% {
                transform: translate(-52%, -48%) rotate(-7deg);
            }

            75% {
                transform: translate(-50%, -50%) rotate(-5deg);
            }

            100% {
                transform: translate(-50%, -50%) rotate(-5deg);
            }
        }

        input,
        button {
            font-size: 1.2em;
            margin: 10px 0;
            background: #000;
            color: #0f0;
            border: 2px solid #0f0;
            padding: 10px;
            animation: flicker 2s infinite;
        }

        @keyframes flicker {

            0%,
            19%,
            21%,
            23%,
            25%,
            54%,
            56%,
            100% {
                opacity: 1;
            }

            20%,
            24%,
            55% {
                opacity: 0.5;
            }
        }

        .avatar-preview img {
            border: 5px solid #ff00ff;
            animation: shake 0.5s infinite;
        }

        @keyframes shake {
            0% {
                transform: translate(0, 0);
            }

            25% {
                transform: translate(-5px, 5px);
            }

            50% {
                transform: translate(5px, -5px);
            }

            75% {
                transform: translate(-5px, -5px);
            }

            100% {
                transform: translate(0, 0);
            }
        }

        .msg {
            font-size: 1.5em;
            text-shadow: 2px 2px 5px #ff0000;
            animation: colorShift 3s infinite;
        }

        @keyframes colorShift {
            0% {
                color: #ff0000;
            }

            25% {
                color: #00ff00;
            }

            50% {
                color: #0000ff;
            }

            75% {
                color: #ffff00;
            }

            100% {
                color: #ff0000;
            }
        }
    </style>
</head>

<body onclick="playAudio()">
<script>
    const audio = document.getElementById('background-audio');
    const context = new (window.AudioContext || window.webkitAudioContext)();
    const source = context.createMediaElementSource(audio);
    const analyser = context.createAnalyser();
    const dataArray = new Uint8Array(analyser.frequencyBinCount);
    source.connect(analyser);
    analyser.connect(context.destination);

    const elementsToShake = document.querySelectorAll('.duct-tape, .sad-xmas-light, .avatar-preview img');

    function animateFromAudio() {
        analyser.getByteFrequencyData(dataArray);
        const average = dataArray.reduce((a, b) => a + b) / dataArray.length;

        const scale = 1 + average / 256;

        elementsToShake.forEach(el => {
            el.style.transform = `scale(${scale}) rotate(${Math.random() * 10 - 5}deg)`;
        });

        requestAnimationFrame(animateFromAudio);
    }

    function playAudio() {
        audio.play();
        context.resume().then(() => {
            animateFromAudio();
        });
    }
</script>


    <!-- Sandstorm animation -->
    <div class="sandstorm"></div>

    <!-- Duct tape effect -->
    <div class="duct-tape"></div>
    <div class="duct-tape"></div>
    <div class="duct-tape"></div>
    <div class="duct-tape"></div>



    <h1>🔥 Profile Editor 🔥</h1>

    <?php if (isset($error)): ?>
        <p class='msg error'><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php if (isset($success)): ?>
        <p class='msg success'><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>

    <!-- Session messages -->
    <?php if (isset($_SESSION['message'])): ?>
        <p class='msg'><?php echo htmlspecialchars($_SESSION['message']); ?></p>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <p class='msg error'><?php echo htmlspecialchars($_SESSION['error']); ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>


    <form method='POST'>
        <label for='username'>Name:</label>
        <input type='text' id='username' name='username' value="<?php echo htmlspecialchars($user['username']); ?>" required>

        <label for='email'>Email:</label>
        <input type='email' id='email' name='email' value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label for='avatar'>Avatar (Image URL):</label>
        <input type='text' id='avatar' name='avatar' value="<?php echo htmlspecialchars($user['avatar']); ?>" required>

        <?php if (!empty($user['avatar'])): ?>
            <div class='avatar-preview'
                class='avatar-highlight' onclick="const popup = document.createElement('div'); popup.textContent = '👀 Your avatar is watching you... 👀'; popup.style.position = 'absolute'; popup.style.left = '50%'; popup.style.top = '50%'; popup.style.transform = 'translate(-50%, -50%)'; popup.style.background = '#222'; popup.style.color = '#0f0'; popup.style.padding = '15px'; popup.style.border = '2px solid #0f0'; popup.style.zIndex = '1000'; popup.style.fontFamily = 'Comic Sans MS, cursive'; popup.style.boxShadow = '0 0 10px #0f0'; document.body.appendChild(popup); setTimeout(() => popup.remove(), 3000);">
                <img src="<?php echo htmlspecialchars($user['avatar']); ?>" alt='Avatar Preview'>
            </div>
        <?php endif; ?>

        <button type='submit'>Update Profile</button>
        <a href='dashboard.php'>
            <button type='button'>Back to Dashboard</button>
        </a>
        <a href='settings.php'>
            <button type='button'>Settings</button>
        </a>
    </form>
    <!-- Christmas Lights -->
    <div class="christmas-lights">
        <div class="light red"></div>
        <div class="light green"></div>
        <div class="light blue"></div>
        <div class="light yellow"></div>
        <div class="light red"></div>
        <div class="light green"></div>
        <div class="light blue"></div>
        <div class="light yellow"></div>
    </div>
    <!-- Christmas Tree -->
    <div style="position: fixed; bottom: 10px; right: 10px; z-index: 100;">
        <h2 style="color: #ff0000;">🎄 Sad <br> Christmas

            <br>Tree 🎄
        </h2>
        <div class="sad-xmas-lights">
            <div class="sad-xmas-light red"></div>
            <div class="sad-xmas-light green"></div>
            <div class="sad-xmas-light blue"></div>
            <div class="sad-xmas-light yellow"></div>
            <div class="sad-xmas-light red"></div>
            <div class="sad-xmas-light green"></div>
            <div class="sad-xmas-light blue"></div>
            <div class="sad-xmas-light yellow"></div>
        </div>
        <img src="static/cursed_imgs/sad_xmas.jpg" onclick="
        
        " alt="Christmas Tree" style="width: 150px; height: auto;">
    </div>
    <!-- Sad X-Mas Tree -->


    <style>
        .sad-xmas-lights {
            position: fixed;
            bottom: 180px;
            right: 50px;
            display: flex;
            gap: 10px;
            z-index: 101;
            animation: sway 2s infinite alternate ease-in-out;
        }

        .sad-xmas-light {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            box-shadow: 0 0 8px rgba(255, 255, 255, 0.8);
            animation: blink 1.5s infinite;
        }

        .sad-xmas-light.red {
            background-color: red;
        }

        .sad-xmas-light.green {
            background-color: green;
        }

        .sad-xmas-light.blue {
            background-color: blue;
        }

        .sad-xmas-light.yellow {
            background-color: yellow;
        }

        .christmas-lights {
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
            z-index: 100;
            animation: sway 2s infinite alternate ease-in-out;
        }

        .light {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.8);
            animation: blink 1.5s infinite;
        }

        .light.red {
            background-color: red;
        }

        .light.green {
            background-color: green;
        }

        .light.blue {
            background-color: blue;
        }

        .light.yellow {
            background-color: yellow;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        @keyframes sway {
            from {
                transform: translateX(-50%) rotate(-5deg);
            }

            to {
                transform: translateX(-50%) rotate(5deg);
            }
        }
        
    </style>
    <!-- Easter Egg Button -->
    <div style="position: fixed; bottom: 10px; left: 10px; z-index: 100;">
        <button onclick="triggerEasterEgg()" style="background: #ff00ff; color: #fff; border: none; padding: 10px; cursor: pointer;">
            🎉 Surprise Me! 🎉
        </button>
    </div>

    <script>
        function triggerEasterEgg() {
            alert("🎵 Darude - Sandstorm intensifies! 🎵");
            document.body.style.background = "url('static/cursed_imgs/darude-sandstorm.gif')";
            document.body.style.animation = "spin 5s linear infinite";
        }
    </script>

    <script>
        document.body.addEventListener('click', function(event) {
            if (event.target.tagName === 'DIV' && words.includes(event.target.textContent)) {
                const popEffect = document.createElement('div');
                popEffect.textContent = '💥';
                popEffect.style.position = 'absolute';
                popEffect.style.left = `${event.clientX}px`;
                popEffect.style.top = `${event.clientY}px`;
                popEffect.style.fontSize = '2em';
                popEffect.style.color = 'red';
                popEffect.style.animation = 'fadeOut 1s forwards';
                document.body.appendChild(popEffect);

                setTimeout(() => popEffect.remove(), 1000);
                event.target.remove();
            }
        });

        const fadeOutStyle = document.createElement('style');
        fadeOutStyle.textContent = `
            @keyframes fadeOut {
                from {
                    opacity: 1;
                }
                to {
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(fadeOutStyle);
        const words = [
            "GarbageHub", "Sandstorm", "Profile", "Edit", "Update", "Cursed",
            "Christmas", "Avatar", "Duct Tape", "Darude", "Easter Egg", "Spin",
            "Glitch", "Flicker", "Shake", "Sad Tree", "Surprise", "Chaos",
            "undefined", "null", "NaN", "Infinity", "Error", "404", "500",
            "Bad Request", "Not Found", "Internal Server Error", "Cursed Code",

        ];

        function createFloatingWord() {
            const word = document.createElement('div');
            word.textContent = words[Math.floor(Math.random() * words.length)];
            word.style.position = 'absolute';
            word.style.color = `hsl(${Math.random() * 360}, 100%, 50%)`;
            word.style.fontSize = `${Math.random() * 2 + 1}em`;
            word.style.top = `${Math.random() * 100}vh`;
            word.style.left = `${Math.random() * 100}vw`;
            word.style.animation = `fly ${Math.random() * 5 + 5}s linear infinite`;
            document.body.appendChild(word);

            setTimeout(() => word.remove(), 10000);
        }

        setInterval(createFloatingWord, 500);

        const style = document.createElement('style');
        style.textContent = `
            @keyframes fly {
                from {
                    transform: translateY(0) rotate(0deg);
                }
                to {
                    transform: translateY(-100vh) rotate(360deg);
                }
            }
        `;
        document.head.appendChild(style);
    </script>
    <!-- Surprise Mail Button -->
    <div style="position: fixed; top: 200px; right: 200px; z-index: 100;">
        <form method="POST" action="send_surprise_mail.php">
            <button type="submit" style="background: #00ff00; color: #000; border: none; padding: 20px; cursor: pointer;">
                📧 Send Surprise Mail! 📧
            </button>
        </form>
    </div>

    <!-- Background Music (Sandstorm) -->
    <div class="audio-container">
        <audio autoplay loop id="background-audio">
            <source src="static/cursed_audio/sandstorm.mp3" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>
    </div>
    <script>
        document.querySelectorAll('.duct-tape').forEach(function(tape) {
            tape.addEventListener('click', function(event) {
                const message = document.createElement('div');
                message.textContent = '🛠️ Duct tape fixes everything!';
                message.style.position = 'absolute';
                message.style.left = `${event.clientX}px`;
                message.style.top = `${event.clientY}px`;
                message.style.background = '#000';
                message.style.color = '#0f0';
                message.style.padding = '20px';
                message.style.border = '2px solid #0f0';
                message.style.zIndex = '1000';
                message.style.fontFamily = 'Comic Sans MS, cursive';
                message.style.boxShadow = '0 0 10px #0f0';
                document.body.appendChild(message);

                setTimeout(() => message.remove(), 3000);
            });
        });
    </script>
        <?php
        // Add random duct tapes to the page
        for ($i = 0; $i < 5; $i++) {
            $top = rand(0, 90); // Random top position (0% to 90%)
            $left = rand(0, 90); // Random left position (0% to 90%)
            $rotation = rand(-30, 30); // Random rotation angle (-30deg to 30deg)
            echo "<div class='duct-tape' style='top: {$top}%; left: {$left}%; transform: rotate({$rotation}deg);'></div>";
        }
        ?>

</body>

</html>