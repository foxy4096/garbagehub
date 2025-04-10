<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    $_SESSION["error"] = "You ain't logged in. What are you even doing?";
    header("Location: login.php");
    exit;
}

if (!isset($_GET["id"])) {
    $_SESSION["error"] = "Repo ID missing. Are you serious?";
    header("Location: dashboard.php");
    exit;
}

$repo_id = intval($_GET["id"]);
$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"];

// Fetch repo details with the owner's username
$stmt = $db->prepare("
    SELECT r.repo_name, r.description, r.is_public, r.user_id, u.username 
    FROM repositories r 
    JOIN users u ON r.user_id = u.id 
    WHERE r.id = ?
");
$stmt->bind_param("i", $repo_id);
$stmt->execute();
$result = $stmt->get_result();
$repo = $result->fetch_assoc();
$stmt->close();

// If the repo doesn't exist or isn't accessible, deny access
if (!$repo || ($repo["user_id"] !== $user_id && !$repo["is_public"])) {
    $_SESSION["error"] = "Either this repo doesn't exist, or it's none of your damn business.";
    header("Location: dashboard.php");
    exit;
}


// Prepare the full repo name like "username/repo_name"
$full_repo_name = htmlspecialchars($repo["username"]) . "/" . htmlspecialchars($repo["repo_name"]);

// Fetch commits for this repo
$stmt = $db->prepare("SELECT id, message, created_at, commit_hash FROM commits WHERE repo_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $repo_id);
$stmt->execute();
$commits_result = $stmt->get_result();
$commits = $commits_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();


// Function to get file tree
function getFileTree($dir)
{
    $result = [];
    $files = scandir($dir);

    foreach ($files as $file) {
        if ($file === "." || $file === "..")
            continue;

        $filePath = $dir . "/" . $file;
        if (is_dir($filePath)) {
            $result[$file] = getFileTree($filePath);
        } else {
            $result[] = $file;
        }
    }
    return $result;
}

$repo_path = "uploads/$full_repo_name/staged/"; // Staging area for commits
$staged_files = file_exists($repo_path) ? getFileTree($repo_path) : [];

?>

<!DOCTYPE html>
<html>

<head>
    <title><?= $full_repo_name ?> - GarbageHub</title>
</head>

<body>
    <?php if (isset($_SESSION["error"])): ?>
        <p style="color: red;"><?= $_SESSION["error"];
        unset($_SESSION["error"]); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION["message"])): ?>
        <p style="color: green;"><?= $_SESSION["message"];
        unset($_SESSION["message"]); ?></p>
    <?php endif; ?>

    <h1><?= $full_repo_name ?></h1>
    <p><?= !empty($repo["description"]) ? htmlspecialchars($repo["description"]) : "No description? Just vibes?" ?></p>
    <p><strong>Visibility:</strong>
        <?= $repo["is_public"] ? "Public (Enjoy the shame)" : "Private (Hiding your crimes?)" ?></p>
    <p><a href="add_files.php?repo_id=<?= $repo_id ?>">Upload Files</a></p>
    <h2>Commits</h2>
    <?php if (empty($commits)): ?>
        <p>No commits yet? Wow, creating a repo just to let it rot?</p>
    <?php else: ?>
        <ul>
            <?php foreach ($commits as $commit): ?>
                <li>
                    <strong>Commit Message:</strong> <?= htmlspecialchars($commit["message"]) ?><br>
                    <strong>Commit Date:</strong> <?= date("F j, Y, g:i a", strtotime($commit["created_at"])) ?><br>
                    <strong>Commit Hash:</strong> <?= htmlspecialchars($commit["commit_hash"]) ?><br>
                    <a href="view_commit.php?commit_hash=<?= $commit["commit_hash"] ?>">View Changes</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <h2>Staged Files</h2>
    <?php if (empty($staged_files)): ?>
        <p>No files staged yet.</p>
    <?php else: ?>
        <ul>
            <?php function renderTree($tree)
            {
                foreach ($tree as $key => $value) {
                    if (is_array($value)) {
                        echo "<li><strong>$key/</strong><ul>";
                        renderTree($value);
                        echo "</ul></li>";
                    } else {
                        echo "<li>$value</li>";
                    }
                }
            }
            renderTree($staged_files);
            ?>
        </ul>
        <h2>Commit Changes</h2>
        <form action="commit.php" method="POST">
            <input type="hidden" name="repo_id" value="<?= $repo_id ?>">
            <label>Commit Message:</label>
            <input type="text" name="message" required>
            <button type="submit">Commit This Trash</button>
        </form>
    <?php endif; ?>

    <p><a href="dashboard.php">Back to Dashboard (Before you ruin more things)</a></p>
</body>

</html>