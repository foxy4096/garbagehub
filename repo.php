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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        
    body {
        font-family: Arial, sans-serif;
        background-color: #c1dff0;
        color: #333;
        margin: 0;
        padding: 0;
    }

    h1, h2 {
        font-family: Georgia, serif;
        text-shadow: 1px 1px #6fa3c1;
    }

    a {
        font-weight: bold;
        color: #0078a8;
        text-decoration: underline;
    }

    a:hover {
        color: #005f85;
        text-decoration: none;
    }

    ul {
        list-style-type: none;
        padding: 10px;
        border: 2px solid #6fa3c1;
        background-color: #e3f2fa;
    }

    li {
        margin-bottom: 10px;
        font-style: normal;
    }

    button {
        background-color: #6fa3c1;
        color: white;
        border: 2px solid #0078a8;
        padding: 10px 15px;
        cursor: pointer;
        border-radius: 5px;
        font-size: 1em;
        box-shadow: inset 0 0 5px #0078a8, 2px 2px #6fa3c1;
        background: linear-gradient(to bottom, #6fa3c1, #0078a8);
    }

    button:hover {
        background-color: #0078a8;
        box-shadow: inset 0 0 7px #6fa3c1, 3px 3px #0078a8;
        background: linear-gradient(to bottom, #0078a8, #6fa3c1);
    }

    .container {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: #e3f2fa;
        border: 2px solid #6fa3c1;
        border-radius: 10px;
        box-shadow: 0 0 10px #6fa3c1;
    }
    input[type="text"],
    textarea {
        width: 40%;
        padding: 11px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }
    input[type="checkbox"] {
        margin-right: 10px;
    }
    label {
        font-weight: bold;
        margin-right: 10px;
    }
        </style>
</head>

<body>
    <div class="container">

        <?php if (isset($_SESSION["error"])): ?>
            <p style="color: red;"><?= $_SESSION["error"];
        unset($_SESSION["error"]); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION["message"])): ?>
        <p style="color: green;"><?= $_SESSION["message"];
        unset($_SESSION["message"]); ?></p>
    <?php endif; ?>
    
    <h1><?= $full_repo_name ?></h1>

    <p class="desc_block">
        <span id="desc_text">
            <?= !empty($repo["description"]) ? htmlspecialchars($repo["description"]) : "No description? Just vibes?" ?>
        </span>
        <textarea name="description" id="repo_desc" hidden><?= htmlspecialchars($repo["description"]) ?></textarea>
        <br>
        <button id="desc_edit">Edit</button>
</p>
    <script>
        document.getElementById("desc_edit").addEventListener("click", function() {
            const descText = document.getElementById("desc_text");
            const descTextarea = document.getElementById("repo_desc");
            const isEditing = descTextarea.hasAttribute("hidden");

            if (isEditing) {
                descText.hidden = true;
                descTextarea.removeAttribute("hidden");
                this.textContent = "Save";
            } else {
                descText.textContent = descTextarea.value;
                descTextarea.setAttribute("hidden", true);
                this.textContent = "Edit";
                // Save the new description via AJAX or form submission
                fetch('update_description.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ repo_id: <?= $repo_id ?>, description: descTextarea.value })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Description updated successfully!");
                    } else {
                        alert("Failed to update description. Please try again.");
                        descText.textContent = descTextarea.value;
                    }
                    descTextarea.setAttribute("hidden", true);
                })
                .catch(error => console.error('Error:', error));
            }
        });
</script>
    <p><strong>Owner:</strong> <?= htmlspecialchars($repo["username"]) ?></p>
    <p><strong>Repo ID:</strong> <?= htmlspecialchars($repo_id) ?></p>

    <p><strong>Visibility:</strong>
    <input type="checkbox" onchange="updateRepoVisiblity()" <?= $repo["is_public"] ? "checked" : "" ?>>
    <label for="visibility">Make it public</label><br>
    <?= $repo["is_public"] ? "Public (Enjoy the shame)" : "Private (Hiding your crimes?)" ?></p>
    <p><a href="add_files.php?repo_id=<?= $repo_id ?>">
        <button>
            Upload Files
        </button>    
    </a></p>
    <h2>Commits</h2>
    <?php if (empty($commits)): ?>
        <p style="color: gray; font-style: italic;">This repo has no commits. It's basically a digital graveyard.</p>
        <img src="https://http.cat/204" alt="No Content" width="300">
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
    </div>
    <script>
        function updateRepoVisiblity() {
            const checkbox = document.querySelector('input[type="checkbox"]');
            const repoId = <?= $repo_id ?>;
            const isPublic = checkbox.checked ? 1 : 0;

            fetch('set_visiblity.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ repo_id: repoId, is_public: isPublic })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Visibility updated successfully!");
                } else {
                    alert("Failed to update visibility. Please try again.");
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
    </body>
    
    </html>