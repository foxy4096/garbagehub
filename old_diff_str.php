<?php

$a = $_POST['original_code'] ?? '';
$b = $_POST['new_code'] ?? '';
$out = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $l1 = explode("\n", $a);
    $l2 = explode("\n", $b);
    $m = max(count($l1), count($l2));
    for ($i = 0; $i < $m; $i++) {
        $o = isset($l1[$i]) ? htmlspecialchars($l1[$i]) : '';
        $n = isset($l2[$i]) ? htmlspecialchars($l2[$i]) : '';
        if ($o !== $n) {
            $out .= "<span style='color:red;background:#ffcccc;'>- $o</span><br>";
            $out .= "<span style='color:green;background:#ccffcc;'>+ $n</span><br>";
        } else {
            $out .= "$o<br>";
        }
    }
} else {
    $out = 'gib me code plz 🥴';
}

?><!doctype html>
<html><head><title>garbagehub diff (unstable)</title></head>
<body style="font-family: Comic Sans MS, monospace; background:#f4f4f4; padding:10px;">
<marquee behavior="alternate" scrollamount="10">🚧 WELCOME TO GARBAGEHUB 🚧</marquee>
<h1>garbage diff viewer</h1>
<p>// pls don't use this in prod 💀</p>
<form method="POST" action="diff_str.php">
    <textarea name="original_code" rows="10" cols="80" style="background:#ffffe0;"><?php echo htmlspecialchars($a) ?></textarea><br><br>
    <textarea name="new_code" rows="10" cols="80" style="background:#e0ffff;"><?php echo htmlspecialchars($b) ?></textarea><br><br>
    <input type="submit" value="do da diff" style="font-weight:bold;background:red;color:white;border:none;padding:10px;">
</form>
<hr>
<h2>diffs (maybe):</h2>
<div style="white-space:pre;"><?php echo $out ?></div>
<footer style="margin-top: 50px; color:#555; font-size:10px;">
    <pre>
    ~~~ GarbageHub 2025 ~~~
    (ﾉಥ益ಥ）ﾉ ┻━┻ diff_str.php? more like cry.php
    </pre>
</footer>
</body>
</html>
