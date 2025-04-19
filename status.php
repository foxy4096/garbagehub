<?php
// Dummy checks – later you can replace with real logic
$databaseStatus = true;
$apiStatus = true;

function getStatusBadge($status) {
    if ($status) {
        return "<span style='color: green; font-weight: bold;'>✔ Operational</span>";
    } else {
        return "<span style='color: red; font-weight: bold;'>✖ Down</span>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GarbageHub - Website Status</title>
    <style>
        body {
            background-color: #f4ecd8;
            font-family: 'Segoe UI', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #c2a878;
            padding: 1em;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        nav {
            background-color: #deb887;
            padding: 0.5em;
            text-align: center;
        }
        nav a {
            margin: 0 15px;
            text-decoration: none;
            color: #000;
            font-weight: bold;
        }
        nav a:hover {
            text-decoration: underline;
        }
        main {
            padding: 2em;
            text-align: center;
        }
        .status-card {
            background-color: #fff8dc;
            border: 2px dashed #c2a878;
            margin: 1em auto;
            padding: 1em;
            width: 300px;
            box-shadow: 3px 3px 6px rgba(0,0,0,0.2);
        }
        footer {
            background-color: #c2a878;
            text-align: center;
            padding: 1em;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Website Status</h1>
    </header>
    <nav>
        <a href="index.php">Home</a>
        <a href="settings.php">Settings</a>
        <a href="donate.php">Donate</a>
        <a href="delete_account.php">Delete Account</a>
        <a href="report.php">Report</a>
    </nav>
    <main>
        <div class="status-card">
            <h2>GarbageHub Core</h2>
            <p><?php echo getStatusBadge(true); ?></p>
        </div>
        <div class="status-card">
            <h2>Database</h2>
            <p><?php echo getStatusBadge($databaseStatus); ?></p>
        </div>
        <div class="status-card">
            <h2>API Server</h2>
            <p><?php echo getStatusBadge($apiStatus); ?></p>
        </div>
    </main>
    <footer>
        <p>&copy; 2025 GarbageHub. All rights reserved.</p>
    </footer>
</body>
</html>
