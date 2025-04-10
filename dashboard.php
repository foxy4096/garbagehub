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
</head>

<body>
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
    <p><a href="logout.php">🚪 Logout (Finally came to your senses?)</a></p>
</body>

</html>