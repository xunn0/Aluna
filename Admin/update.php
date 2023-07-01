<?php
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Taking all values from the teacher form data(input)
  $teacher_id = $_POST['teacher_id'];
  $name = $_POST['name'];
  $phone = $_POST['phone'];
  $email = $_POST['email'];
  $gender = $_POST['gender'];
  $dob = $_POST['dob'];
  $password = $_POST['password'];

  // Perform necessary validations on the input data

  // Hash the password
  $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

  // Update the teacher record in the database using parameterized statement
  $sql = "UPDATE teacher SET name=?, phone=?, email=?, gender=?, dob=?, password=? WHERE teacher_id=?";
  $stmt = mysqli_prepare($con, $sql);
  mysqli_stmt_bind_param($stmt, "ssssssi", $name, $phone, $email, $gender, $dob, $hashedPassword, $teacher_id);

  if (mysqli_stmt_execute($stmt)) {
    echo '<script language="javascript">
            alert("Data updated in the database successfully.");
            window.location.replace("teachers.php");
          </script>';
  } else {
    echo "ERROR: Failed to update data in the database. " . mysqli_error($con);
  }

  mysqli_stmt_close($stmt);
}

mysqli_close($con);
?>
