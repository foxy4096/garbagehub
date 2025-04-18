<?php
// report.php
header("HTTP/1.1 503 Service Unavailable");
header("Retry-After: 31536000"); // Suggest retry after 1 year
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Under Maintenance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
            background-color: #f4f4f4;
            color: #333;
        }
        h1 {
            font-size: 2.5em;
            color: #e74c3c;
        }
        p {
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <h1>Under Maintenance</h1>
    <p>Sorry, this page is currently under maintenance and will not be available for the foreseeable future.</p>
</body>
</html>