<?php
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Taking all values from the student form data(input)
  $name = $_POST['name'];
  $phone = $_POST['phone'];
  $email = $_POST['email']; 
  $gender = $_POST['gender'];
  $dob = $_POST['dob'];
  $password = $_POST['password'];
  
  // Perform necessary validations on the input data
  
  // Generate the student ID
  $id = generateStudentID(); // Implement your logic to generate a unique student ID
  
  // Hash the password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
  
  // Prepare a parameterized statement for inserting the student record into the database
  $stmt = $con->prepare("INSERT INTO student (student_id, name, phone, email, gender, dob, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
  
  // Bind parameters to the statement
  $stmt->bind_param("sssssss", $id, $name, $phone, $email, $gender, $dob, $hashedPassword);
  
  // Execute the statement
  if ($stmt->execute()) {
    echo '<script language="javascript">
            alert("Data stored in the database successfully.");
            window.location.replace("students.php");
          </script>';
  } else {
    echo "ERROR: Failed to store data in the database. " . $stmt->error;
  }
  
  // Close the statement
  $stmt->close();
}

// Function to generate a unique student ID
function generateStudentID() {
  // Implement your logic to generate a unique student ID
  // Example: You can use a combination of a prefix and a random number
  $prefix = "S";
  $randomNumber = mt_rand(10000, 99999);
  $studentID = $prefix . $randomNumber;
  return $studentID;
}

// Repeat the similar logic for handling teacher form submission

mysqli_close($con);
?>
