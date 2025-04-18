<?php

$cries = [
    "why did I even write this",
    "this wasn't supposed to happen",
    "I just wanted to fix one bug",
    "my semicolon betrayed me",
    "I'm not crying, you're crying",
    "404: sanity not found",
    "brb screaming into the void",
    "the diff lied to me",
    "I regret everything",
    "send help",
    "GarbageHub was a mistake"
];

shuffle($cries);

?><!DOCTYPE html>
<html>
<head>
    <title>😭 cry.php</title>
    <style>
        body {
            background-color: black;
            color: cyan;
            font-family: 'Comic Sans MS', cursive;
            text-align: center;
            padding-top: 100px;
        }

        .cry-box {
            background: rgba(255, 0, 0, 0.2);
            border: 3px dashed pink;
            display: inline-block;
            padding: 30px;
            border-radius: 12px;
            animation: shake 1s infinite;
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

        blink {
            animation: blink-animation 1s steps(5, start) infinite;
            color: magenta;
        }

        @keyframes blink-animation {
            to {
                visibility: hidden;
            }
        }

        .fake-btn {
            background-color: purple;
            color: white;
            padding: 15px 30px;
            font-size: 18px;
            margin-top: 20px;
            display: inline-block;
            border: none;
            border-radius: 8px;
            cursor: not-allowed;
            filter: blur(1px);
        }

        audio {
            display: none;
        }
    </style>
</head>
<body onclick="document.getElementById('audio-player').play()">

<audio autoplay loop controls id="audio-player">
    <source src="/static/cursed_audio/SoundHelix-Song-14.mp3" type="audio/mpeg">
</audio>

<div class="cry-box">
    <h1><blink>😭 cry.php</blink></h1>
    <p><?= $cries[0] ?></p>
    <p><?= $cries[1] ?></p>
    <p><?= $cries[2] ?></p>
    <p><em>"GarbageHub is love, GarbageHub is pain."</em></p>
    <button class="fake-btn">Try Not To Cry (Failing...)</button>
</div>
</body>

</html>
