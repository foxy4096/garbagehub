<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    $_SESSION["error"] = "Log in first, you idiot.";
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"];

if (!isset($_GET["repo_id"])) {
    $_SESSION["error"] = "Which repo? Are you drunk?";
    header("Location: dashboard.php");
    exit;
}

$repo_id = intval($_GET["repo_id"]);

// Get repo details
$stmt = $db->prepare("SELECT repo_name FROM repositories WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $repo_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$repo = $result->fetch_assoc();
$stmt->close();

if (!$repo) {
    $_SESSION["error"] = "That repo doesn't exist, or it's not yours.";
    header("Location: dashboard.php");
    exit;
}

$repo_name = htmlspecialchars($repo["repo_name"]);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Files - <?= $username ?>/<?= $repo_name ?></title>
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
    input[type="file"] {
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }
    
        </style>
</head>
<body>
    <div class="container">

        <h1>Adding Files to <?= $username ?>/<?= $repo_name ?></h1>
        
        <?php if (isset($_SESSION["message"])): ?>
            <p style="color: green;"><?= $_SESSION["message"]; unset($_SESSION["message"]); ?></p>
            <?php endif; ?>
            
            <?php if (isset($_SESSION["error"])): ?>
                <p style="color: red;"><?= $_SESSION["error"]; unset($_SESSION["error"]); ?></p>
                <?php endif; ?>
                
                <form action="upload_files.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="repo_id" value="<?= $repo_id ?>">
                    
                    <label>Upload to folder:</label>
                    <input type="text" name="upload_path" id="uploadPath" value="<?=$_GET['upload_path'] ?? "/" ?>" placeholder="e.g., src/components/" required>
                    
                    <label>Select files:</label>
                    <input type="file" id="fileInput" name="files[]" multiple><br>
                    
                    <h3>Folder Structure Preview:</h3>
                    <div id="fileTree"></div>
                    
                    <button type="submit">Upload This Garbage</button>
                </form>
                
                <p><a href="repo.php?id=<?= $repo_id ?>">Back to Repo</a></p>
                
                <script>
                    document.getElementById("fileInput").addEventListener("change", updateTree);
                    document.getElementById("uploadPath").addEventListener("input", updateTree);
                    
                    let files = [];
                    let removedFiles = new Set();
                    
                    function updateTree() {
                        const fileTreeContainer = document.getElementById("fileTree");
                        fileTreeContainer.innerHTML = "";
                        
                        const uploadPath = document.getElementById("uploadPath").value.trim();
                        if (uploadPath === "") return;
                        
                        let tree = {};
                        files = Array.from(document.getElementById("fileInput").files);
                        removedFiles.clear();
                        
        // Build tree structure
        files.forEach(file => {
            let pathParts = (uploadPath + "/" + file.name).split("/").filter(Boolean);
            let currentLevel = tree;
            pathParts.forEach((part, index) => {
                if (!currentLevel[part]) {
                    currentLevel[part] = index === pathParts.length - 1 ? "file" : {};
                }
                currentLevel = currentLevel[part];
            });
        });
        
        function createTreeHTML(tree) {
            let ul = document.createElement("ul");
            for (let key in tree) {
                let li = document.createElement("li");
                li.textContent = key;
                
                if (tree[key] === "file") {
                    let removeButton = document.createElement("button");
                    removeButton.textContent = "Remove";
                    removeButton.style.marginLeft = "10px";
                    
                    removeButton.onclick = function() {
                        logic = confirm(`Are you sure you want to remove this file? `+ key);
                        if (!logic) return;
                        document.getElementById("fileInput").files = Array.from(document.getElementById("fileInput").files).filter(file => file.name !== key);
                        removedFiles.add(uploadPath + "/" + key);
                        
                        updateTree();
                        
                    };
                    
                    li.appendChild(removeButton);
                } else {
                    li.appendChild(createTreeHTML(tree[key]));
                }
                
                ul.appendChild(li);
            }
            return ul;
        }
        
        fileTreeContainer.appendChild(createTreeHTML(tree));
        
        // Modify file input to exclude removed files
        document.querySelector("form").addEventListener("submit", function(event) {
            let filteredFiles = files.filter(file => !removedFiles.has(uploadPath + "/" + file.name));
            
            if (filteredFiles.length === 0) {
                alert("You removed all files! Add at least one.");
                event.preventDefault();
                return;
            }
            
            let dataTransfer = new DataTransfer();
            filteredFiles.forEach(file => dataTransfer.items.add(file));
            document.getElementById("fileInput").files = dataTransfer.files;
        });
    }
    </script>

</div>
</body>
</html>
