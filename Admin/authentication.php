<?php
session_start();
include('connection.php');

$email = $_POST['email'];
$password = $_POST['password'];

// Retrieve the stored hashed password from the database
$stmt = $con->prepare("SELECT password FROM admin WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $storedHashedPassword = $row['password'];

    // Verify the password
    if (password_verify($password, $storedHashedPassword)) {
        // Password is correct, proceed with login
        $_SESSION['email'] = $email; // Store the email in the session

        // JavaScript alert for successful login
        echo '<script>alert("Login successful.");</script>';

        // Redirect to the homepage after displaying the alert
        echo '<script>window.location.href = "homepage.php";</script>';
        exit();
    }
}

// Login failed
$_SESSION['Failed'] = 1;

// JavaScript alert for wrong email or password
echo '<script>alert("Wrong email or password.");</script>';

// Redirect back to the index.php after displaying the alert
echo '<script>window.location.href = "index.php";</script>';
exit();
?>
