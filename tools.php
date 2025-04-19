<?php
session_start();
$tools = [
    ['name' => 'DIFF.EXE', 'desc' => "If it compiles, it's a feature.", 'link' => 'diff_str.php'],
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>GarbageHub - Tools</title>
    <style>
        body {
            background: #111;
            color: #0f0;
            font-family: "Comic Sans MS", cursive;
            padding: 20px;
        }

        nav a {
            color: cyan;
            margin-right: 10px;
            text-decoration: none;
        }

        nav a:hover {
            text-shadow: 0 0 5px cyan;
        }

        .tool {
            background: #222;
            border: 1px dashed #555;
            padding: 10px;
            margin-bottom: 15px;
        }

        h1 {
            color: #ff4500;
            text-align: center;
        }

        .tool h2 {
            margin: 0;
            color: #ff0;
        }

        .tool p {
            font-size: 14px;
        }

        footer {
            text-align: center;
            margin-top: 40px;
            color: #666;
        }
    </style>
</head>

<body>
    <nav>
        <a href="index.php">🏠 Home</a>
        <a href="dashboard.php">🔥 Dashboard</a>
        <a href="status.php">📉 Status</a>
        <a href="tools.php">🛠 Tools</a>
        <a href="logout.php">🚪 Logout</a>
    </nav>

    <h1>🛠 GarbageHub Tools</h1>
    <p>These tools probably won't help... but you're welcome to try.</p>

    <?php foreach ($tools as $tool): ?>
        <div class="tool">
            <h2><?= htmlspecialchars($tool['name']) ?></h2>
            <p><?= htmlspecialchars($tool['desc']) ?></p>
            <button onclick="
                window.location.href='<?= htmlspecialchars($tool['link']) ?>'"
                style="background: #ff4500; color: #fff; border: none; padding: 10px; cursor: pointer; font-size: 16px;">
                Use Tool</button>
        </div>
    <?php endforeach; ?>

    <footer>
        <p>&copy; <?= date('Y') ?> GarbageHub. All rights neglected.</p>
    </footer>
</body>

</html>