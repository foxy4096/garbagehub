<?php
// garbage_theme.php
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
$random_insult = $insults[ array_rand($insults) ];
?>
