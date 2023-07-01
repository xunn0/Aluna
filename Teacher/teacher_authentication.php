<?php
session_start();
include('connection.php');

$email = $_POST['email'];
$password = $_POST['password'];

// Retrieve the stored hashed password and teacher ID from the database
$stmt = $con->prepare("SELECT teacher_id, password FROM teacher WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $storedHashedPassword = $row['password'];
    $teacherId = $row['teacher_id'];

    // Verify the password
    if (password_verify($password, $storedHashedPassword)) {
        // Password is correct, proceed with login
        $_SESSION['teacherLoggedIn'] = true;
        $_SESSION['email'] = $email; // Store the email in the session
        $_SESSION['teacher_id'] = $teacherId; // Store the teacher ID in the session

        // JavaScript alert for successful login
        echo '<script>alert("Login successful.");</script>';

        // Redirect to the homepage after displaying the alert
        echo '<script>window.location.href = "homepage.php";</script>';
        exit();
    }
}

// After successful login
$_SESSION['teacher_id'] = $teacherId; // $teacherId is the ID of the logged-in teacher

// Login failed
$_SESSION['Failed'] = 1;

// JavaScript alert for wrong email or password
echo '<script>alert("Wrong email or password.");</script>';

// Redirect back to the index.php after displaying the alert
echo '<script>window.location.href = "index.php";</script>';
exit();
?>
