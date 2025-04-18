<!-- This webpage will have 2 textareas -->
<!-- One for the user to input their code and another to display the diff -->
<?php

$original_code = $_POST['original_code'] ?? '';
$new_code = $_POST['new_code'] ?? '';

$diff_output = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Without any libraries, we can use a simple approach to show the diff
    $original_lines = explode("\n", $original_code);
    $new_lines = explode("\n", $new_code);

    $diff_output = '';
    $max_lines = max(count($original_lines), count($new_lines));

    for ($i = 0; $i < $max_lines; $i++) {
        $original_line = isset($original_lines[$i]) ? htmlspecialchars($original_lines[$i]) : '';
        $new_line = isset($new_lines[$i]) ? htmlspecialchars($new_lines[$i]) : '';

        if ($original_line !== $new_line) {
            $diff_output .= "<span style='color: red;'>- $original_line</span><br>";
            $diff_output .= "<span style='color: green;'>+ $new_line</span><br>";
        } else {
            $diff_output .= "$original_line<br>";
        }
    }
} else {
    $diff_output = 'Please enter the original and new code to see the diff.';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        GarbageHub - Diff Viewer
    </title>
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
    </nav>
    <h1>Diff Viewer</h1>
    <p>Compare your code with the original.</p>
    <form method="POST" action="diff_str.php">
        <label for="original_code">Original Code:</label><br>
        <textarea id="original_code" name="original_code" rows="10" cols="50"></textarea><br><br>
        
        <label for="new_code">New Code:</label><br>
        <textarea id="new_code" name="new_code" rows="10" cols="50"></textarea><br><br>
        
        <input type="submit" value="Compare">
    </form>
    <h2>Diff Output:</h2>

    <pre><?php echo $diff_output ?></pre>
    <footer>
        <p><strong>GarbageHub:</strong> "It is garbage, it is for the garbage, it is of the garbage."</p>
    </footer>
</body>
</html>