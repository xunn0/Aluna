<?php
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Taking all values from the form data (input)
  $class_id = $_POST['class_id'];
  $student_id = $_POST['student_id'];

  // Perform necessary validations on the input data

  // Prepare the SQL statement with parameter placeholders
  $sql = "INSERT INTO class_student (class_id, student_id) VALUES (?, ?)";

  // Create a prepared statement
  $stmt = mysqli_prepare($con, $sql);

  // Bind the parameters with the corresponding values
  mysqli_stmt_bind_param($stmt, "ss", $class_id, $student_id);

  // Execute the prepared statement
  if (mysqli_stmt_execute($stmt)) {
    echo '<script language="javascript">
            alert("Student assigned successfully.");
            window.location.replace("class.php");
          </script>';
  } else {
    echo "ERROR: Failed to assign student. " . mysqli_error($con);
  }

  // Close the prepared statement
  mysqli_stmt_close($stmt);
}

mysqli_close($con);
?>
