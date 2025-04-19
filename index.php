<?php
session_start();
$insults = [
    'Why are you here? This place is a dump.',
    'Welcome to GarbageHub, where bad code gets worse.',
    "GitHub wouldn't take your code? We won't either, but you can still put it here.",
    'Leave all hope behind, you who enter here.',
    'The only repo here is regret.',
    '404: Code quality not found.',
    'You are one commit away from total disaster.',
];

$random_insult = $insults[ array_rand( $insults ) ];
?>

<!DOCTYPE html>
<html lang = 'en'>
<head>
<meta charset = 'UTF-8'>
<meta name = 'viewport' content = 'width=device-width, initial-scale=1.0'>
<title>GarbageHub - Home of Bad Code</title>
<style>
body {
    font-family: "Comic Sans MS", "Comic Sans", cursive;
    background-color: #000;
    color: #0f0;
    margin: 0;
    padding: 0;
}

nav {
    background-color: #333;
    padding: 10px;
    width: 100%;
    border-bottom: 3px solid #666;
}

nav a {
    /* rainbow */
    background: linear-gradient(90deg, #ff0000, #ff7f00, #ffff00, #00ff00, #0000ff, #4b0082, #9400d3);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    
    text-decoration: none;
    margin-right: 15px;
    font-size: 16px;
}

nav a:hover {
    color: #ff00ff;
    text-shadow: 0 0 5px #ff00ff;
}

/* h1 {
    text-align: center;
    color: #ff4500;
    font-size: 32px;
    margin-top: 20px;
    text-shadow: 2px 2px 5px #000;
} */

main{
    margin: 20px;
}
a:visited{
    color:rgb(0, 183, 255);
}
footer{
    text-align: center;
    margin-top: 20px;
    font-size: 12px;
    color: #ff4500;
    text-shadow: 1px 1px 3px #000;
}
.title{
    /* rainbow */
    background: linear-gradient(90deg, #ff0000, #ff7f00, #ffff00, #00ff00, #0000ff, #4b0082, #9400d3);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-size: 50px;
    text-align: center;

}
</style>
</head>
<body>
<main>

<h1><span class="title">GarbageHub</span>💀</h1>
<p><?= $random_insult ?></p>
<marquee behavior="" direction="">

    <nav>
        <a href = 'index.php'>Home</a>
<a href = 'about.php'>About</a>
<a href = 'contact.php'>Contact</a>
<a href = 'settings.php'>Settings</a>
<a href = 'logout.php'>Logout</a>
<a href = 'dashboard.php'>Dashboard</a>
<a href = 'signup.php'>Sign Up</a>
<a href = 'login.php'>Log In</a>
<a href = 'report.php'>Report</a>
<a href = 'feedback.php'>Feedback</a>
<a href = 'donate.php'>Donate</a>
<a href = 'terms.php'>Terms of Service</a>
<a href = 'privacy.php'>Privacy Policy</a>
<a href = 'faq.php'>FAQ</a>
<a href = 'help.php'>Help</a>
<a href = 'support.php'>Support</a>
<a href = 'blog.php'>Blog</a>
<a href = 'news.php'>News</a>
<a href = 'updates.php'>Updates</a>
<a href = 'changelog.php'>Changelog</a>
<a href = 'status.php'>Status</a>
<a href = 'api.php'>API</a>
<a href = 'docs.php'>Docs</a>
<a href = 'tutorials.php'>Tutorials</a>
<a href = 'guides.php'>Guides</a>
<a href = 'resources.php'>Resources</a>
<a href = 'tools.php'>Tools</a>
<a href = 'plugins.php'>Plugins</a>
</nav>
</marquee>
<h2>What is this?</h2>
<p>GarbageHub is a home for your cursed, unmaintainable, and barely functional code.
You write crimes against programming, we store them.</p>

<h2>Features ( That Probably Don't Work )</h2>
<ul>
<li>✅ Host your spaghetti code</li>
<li>✅ Version control that barely functions</li>
<li>✅ Commit messages nobody will read</li>
<li>✅ Guaranteed regret</li>
</ul>

<h2>Get Started</h2>
<?php if ( isset( $_SESSION[ 'user_id' ] ) ): ?>
<p>Welcome back, <?=htmlspecialchars( $_SESSION[ 'username' ] ) ?>!
<a href = 'dashboard.php'>Go to your dumpster fire.</a></p>
<?php else: ?>
<p><a href = 'signup.php'>Sign Up</a> or <a href = 'login.php'>Log In</a> ( Why? I have no idea. )</p>
<?php endif;
?>
<label>
    <input type="checkbox" onclick="document.body.style.transform='rotate(180deg)';">
    Invert Despair
</label>
<br>
<br>
<a href="tools.php">
    <button style="
        border: none;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        transition: background 0.3s ease;
    ">
        Tools
    </button>
</a>
<p><strong>⚠ Server Room Temperature:</strong> <?= rand(70, 120) ?>°C – Dangerously unstable.</p>


</main>
<footer>
<p><strong>GarbageHub:</strong> 'It is garbage, it is for the garbage, it is of the garbage.'</p>
</footer>
</body>
</html>
