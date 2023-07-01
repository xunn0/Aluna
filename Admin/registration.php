<?php
session_start();
include('connection.php');

// Validate and sanitize the email and password inputs
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$password = $_POST['password'];

// Hash the password using bcrypt
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert the email and hashed password into the admin table
$stmt = $con->prepare("INSERT INTO admin (email, password) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $hashedPassword);
$stmt->execute();

// Redirect to the login or desired page after successful registration
header("Location: index.php");
exit();
?>
