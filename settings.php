<!-- This page looks like a typical government website 😭 -->

<?php

session_start();

include "db.php";

// Redirect to login if not authenticated

if (!isset($_SESSION["user_id"])) {
    $_SESSION["error"] = "You ain't logged in. GTFO.";
    header("Location: login.php");
    exit;
}

// Fetch user data

$user_id = $_SESSION["user_id"];
$username = $_SESSION["username"];
$avatar = $_SESSION["avatar"] ?? "default_avatar.png"; // In case avatar is missing

// Does the user have a token?
$stmt = $db->prepare("SELECT token FROM tokens WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$token = $result->fetch_assoc();
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        GarbageHub - Settings
    </title>
</head>
<style>
    body {
        font-family: "Times New Roman", Times, serif;
        background-color: #f8f9fa;
        color: #212529;
        margin: 0;
        padding: 0;
    }

    nav {
        background-color: #007bff;
        padding: 5px;
        border-bottom: 2px solid #0056b3;
    }

    nav a {
        color: #ffffff;
        text-decoration: underline;
        margin-right: 5px;
        font-size: 12px;
    }

    nav a:hover {
        color: #ffc107;
        text-shadow: none;
    }

    h1 {
        text-align: left;
        color: #343a40;
        font-size: 18px;
        margin: 10px;
        text-shadow: none;
    }

    p {
        font-size: 12px;
        line-height: 1.2;
        margin: 10px;
        text-shadow: none;
    }

    button {
        display: inline-block;
        margin: 10px;
        padding: 5px 10px;
        background-color: #6c757d;
        color: #ffffff;
        border: 1px solid #343a40;
        border-radius: 0;
        cursor: pointer;
        font-family: "Times New Roman", Times, serif;
    }

    button:hover {
        background-color: #5a6268;
        border-color: #343a40;
        box-shadow: none;
    }
    
    body {
        cursor:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg'  width='40' height='48' viewport='0 0 100 100' style='fill:black;font-size:24px;'><text y='50%'>💀</text></svg>") 16 0,auto; /*!emojicursor.app*/
}


</style>
<body>
    <div style="width: 100%; background: grey; margin: 0px 0;">
    <div style="width: 99%; height: 10px; background: lime; animation: loading 10s infinite;"></div>
</div>
    
    <nav>
        <a href="index.php">Home</a>
        <a href="dashboard.php">Dashboard</a>
    </nav>

<style>
@keyframes loading {
    0% { width: 0%; }
    100% { width: 99%; }
}
</style>

    <h1>Settings</h1>
    
    <img src="<?= htmlspecialchars($avatar) ?>" alt="Avatar" style="width: 80px; height: 80px; border-radius: 50%; animation: spin 5s linear infinite; margin: 10px;">
    <p>Manage your account settings.</p>
    <?php if($token): ?>
        <p>Your current token:
        <strong class="token" id="slow-token"></strong></p>
        <p>WARNING: THIS TOKEN CANNOT BE REVOKED</p>

    </p>
    <?php else: ?>
        <p>You don't have a token yet. Generate one below.</p>
    <button 
        onclick="window.location.href='gen_token.php'"
        style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; cursor: pointer;">
        Generate Token
    </button>
    <?php endif; ?>
        <!-- what else can we add? -->
        <button onclick='window.open("https:\//www.youtube.com/watch?v=dQw4w9WgXcQ")'
        style="background: black; color: lime; font-family: monospace;">
    Panic
</button>
<style>
    @keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

</style>

    <p><a href="index.php">Home</a></p>
    <p><a href="edit_profile.php">Edit Profile</a></p>
    <p><a href="logout.php">Logout</a></p>
    <script>
const token = "<?= htmlspecialchars($token['token'] ?? '') ?>";
let revealed = "";
let i = 0;

function revealToken() {
    if (i < token.length) {
        revealed += token[i];
        document.getElementById("slow-token").textContent = revealed + "_";
        i++;
        setTimeout(revealToken, 200);
    }
}
window.onload = revealToken;

</script>
</body>
</html>