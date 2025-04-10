<?php
session_start();
session_unset();
session_destroy();

$logout_insults = [
    "Finally leaving? Good riddance.",
    "Hope you don't come back.",
    "You lasted longer than I expected.",
    "Logout successful. Now go touch some grass.",
    "GarbageHub is now 1% cleaner without you."
];

session_start();
$_SESSION["message"] = $logout_insults[array_rand($logout_insults)];

header("Location: login.php");
exit;
?>
