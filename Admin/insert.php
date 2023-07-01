<?php
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Taking all values from the teacher form data(input)
  $name = $_POST['name'];
  $phone = $_POST['phone'];
  $email = $_POST['email'];
  $gender = $_POST['gender'];
  $dob = $_POST['dob'];
  $password = $_POST['password'];
  
  // Perform necessary validations on the input data
  
  // Generate the teacher ID
  $teacher_id = generateTeacherID(); // Implement your logic to generate a unique teacher ID
  
  // Hash the password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
  
  // Prepare the SQL statement with parameterized query
  $sql = "INSERT INTO teacher (teacher_id, name, phone, email, gender, dob, password) VALUES (?, ?, ?, ?, ?, ?, ?)";
  $stmt = mysqli_prepare($con, $sql);
  
  // Bind the parameters
  mysqli_stmt_bind_param($stmt, "sssssss", $teacher_id, $name, $phone, $email, $gender, $dob, $hashedPassword);
  
  if (mysqli_stmt_execute($stmt)) {
    echo '<script language="javascript">
            alert("Data stored in the database successfully.");
            window.location.replace("teachers.php");
          </script>';
  } else {
    echo "ERROR: Failed to store data in the database. " . mysqli_error($con);
  }
  
  mysqli_stmt_close($stmt);
}

// Function to generate a unique teacher ID
function generateTeacherID() {
  // Implement your logic to generate a unique teacher ID
  // Example: You can use a combination of a prefix and a random number
  $prefix = "T";
  $randomNumber = mt_rand(10000, 99999);
  $teacherID = $prefix . $randomNumber;
  return $teacherID;
}

// Repeat the similar logic for handling student form submission

mysqli_close($con);
?>
