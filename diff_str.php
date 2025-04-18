<?php

// ¯\_(ツ)_/¯ diff logic straight outta the trashbin
$og = $_POST['original_code'] ?? '';
$new = $_POST['new_code'] ?? '';
$diffz = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $og_lines = explode("\n", $og);
    $new_lines = explode("\n", $new);
    $max = max(count($og_lines), count($new_lines));

    for ($i = 0; $i < $max; $i++) {
        $o = isset($og_lines[$i]) ? htmlspecialchars($og_lines[$i]) : '';
        $n = isset($new_lines[$i]) ? htmlspecialchars($new_lines[$i]) : '';

        if ($o !== $n) {
            $diffz .= "<span style='color:red;background:#ffcccc;'>- $o</span><br>";
            $diffz .= "<span style='color:green;background:#ccffcc;'>+ $n</span><br>";
        } else {
            $diffz .= "$o<br>";
        }
    }
} else {
    $diffz = '👀 what u want me to compare, air?';
}
$change_count = 0;



?><!DOCTYPE html>
<html>
<head>
    <title>🗑️ GarbageHub Diff Viewer (experimental)</title>
    <style>
        body {
            font-family: 'Comic Sans MS', cursive, monospace;
            background: #fafafa url('static/cursed_imgs/white-noise.gif') repeat;
            color: #222;
            margin: 0;
            padding: 0;
        }

        .container {
            margin: 20px auto;
            padding: 20px;
            max-width: 900px;
            border: 3px dashed #666;
            background-color: #ffffffdd;
            box-shadow: 0 0 20px pink;
        }

        textarea {
            width: 100%;
            font-family: monospace;
            font-size: 14px;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #fefbd8;
            border: 2px dashed orange;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: magenta;
            color: lime;
            border: 2px groove cyan;
            padding: 10px 20px;
            font-weight: bold;
            font-family: monospace;
            font-size: 16px;
            cursor: grab;
        }

        input[type="submit"]:hover {
            background-color: black;
            color: yellow;
        }

        pre {
            background-color: #000000;
            color: #00ff00;
            padding: 10px;
            overflow-x: auto;
            font-size: 14px;
            border: 2px dotted red;
        }

        marquee {
            background: yellow;
            color: purple;
            font-size: 24px;
            font-weight: bold;
            padding: 10px 0;
            border-bottom: 3px double hotpink;
        }

        footer {
            margin-top: 30px;
            padding: 15px;
            background-color: #ccc;
            color: darkred;
            font-size: 12px;
            text-align: center;
            font-style: italic;
            border-top: 3px dashed green;
        }
    </style>
</head>
<body>
    <marquee behavior="alternate">🚧 Welcome to GarbageHub's Cursed Diff Viewer™ 🚧</marquee>

    <div class="container">
        <h1 style="text-align: center;">🤖 DIFF.EXE [Beta-ish]</h1>
        <p style="text-align:center;">pls compare responsibly 🧠</p>

        <form method="POST" action="diff_str.php">
            <label for="original_code">OG Code™</label><br>
            <textarea name="original_code" rows="10" id="original_code"><?php echo htmlspecialchars($og) ?></textarea><br>

            <label for="new_code">New Code (now with bugs)</label><br>
            <textarea name="new_code" rows="10" id="new_code"><?php echo htmlspecialchars($new) ?></textarea><br>

            <input type="submit" value="⚔️ Show Me The Damage">
        </form>

        <h2>📉 Diff Output</h2>
        <pre><?php echo $diffz ?></pre>
    

    </div>

    <footer>
        <p><strong>GarbageHub:</strong> <em>"If it compiles, it's a feature."</em></p>
        <p>// diff_str.php — written by a sleep-deprived goblin at 3AM</p>
    </footer>
    <script>
let buffer = "";

document.addEventListener('keydown', function (e) {
    buffer += e.key;
    if (buffer.length > 10) buffer = buffer.slice(-10); // keep last 10 characters

    if (buffer.toLowerCase().includes("cry")) {
        window.location.href = "cry.php";
    }
});
</script>

</body>
</html>
