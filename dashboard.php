<?php
session_start();
include "db.php";

// Redirect to login if not authenticated
if (!isset($_SESSION["user_id"])) {
    $_SESSION["error"] = "You ain't logged in. GTFO.";
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"];
$avatar = $_SESSION["avatar"] ?? "default_avatar.png"; // In case avatar is missing

// Fetch user repositories
$stmt = $db->prepare("SELECT id, repo_name, description FROM repositories WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$repos = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$db->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard - GarbageHub</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Make hover effects sketchy */
        a:hover {
            transition: color 0.1s steps(5, end);
            color: #ff4500;
        }
        body {
            font-family: "Comic Sans MS", cursive, sans-serif;
            background-color: #000;
            color: #0f0;
            margin: 0;
            padding: 0;
        }

        h1, h2 {
            color: #ff0000;
            text-shadow: 2px 2px #000;
        }

        a {
            color: #00ffff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: blink;
            color: #ff00ff;
        }

        p {
            margin: 10px 0;
            font-style: italic;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin: 10px 0;
            padding: 10px;
            background-color: #222;
            border: 1px dashed #ff0;
            border-radius: 10px;
        }

        img {
            border-radius: 0;
            border: 2px solid #f00;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #111;
            border: 2px dotted #0f0;
            border-radius: 15px;
            box-shadow: 0 0 10px #0f0;
        }
</style>
</head>

<body>
    <div class="container">
        
        <p>Go to <a href="/">Home</a></p>
        <h1>Welcome to your dumpster fire, <?= htmlspecialchars($username) ?>.</h1>
    <img src="<?= htmlspecialchars($avatar) ?>" alt="your avatar" height="50" width="50">
    <br>
    <a href="edit_profile.php">Edit Profile</a>
    <?php if (isset($_SESSION["error"])): ?>
        <p style="color: red;"><?= $_SESSION["error"];
        unset($_SESSION["error"]); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION["message"])): ?>
        <p style="color: green;"><?= $_SESSION["message"];
        unset($_SESSION["message"]); ?></p>
    <?php endif; ?>

    <h2>Your Garbage Repositories</h2>
    
    <?php if (empty($repos)): ?>
        <p>You have no repositories. Are you even trying to fail?</p>
        <?php else: ?>
            <ul>
                <?php foreach ($repos as $repo): ?>
                    <li>
                        <a href="repo.php?id=<?= $repo['id'] ?>"><strong><?= htmlspecialchars($repo['repo_name']) ?></strong></a>
                        <p><?= htmlspecialchars($repo['description']) ?></p>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

                <p><a href="new_repo.php">🚀 Create New Repo (Because One Mess Isn't Enough)</a></p>
                <p><a href="diff_str.php">
        🛠️ Compare Code (Because You Can't Even)</a></p>
        <p>
            <a href="settings.php">
                ⚙️ Settings (If You Can Call Them That)</a>
            </a>
        </p>
        <p><a href="logout.php">🚪 Logout (Finally came to your senses?)</a></p>
    </div>
</body>

</html>