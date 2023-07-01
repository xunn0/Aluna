<?php
// Establish database connection
$con = new mysqli("localhost", "root", "", "aluna");

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Enable prepared statements
$stmt = $con->prepare("SET SESSION sql_mode='NO_BACKSLASH_ESCAPES'");
$stmt->execute();
$stmt->close();
?>
