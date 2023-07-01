<?php
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Taking all values from the class form data(input)
  $class_id = $_POST['class_id'];
  $name = $_POST['name'];
  $teacher_id = $_POST['teacher_id'];
  $day = $_POST["day"];
  $timeSlot = $_POST["time_slot"];

  // Perform necessary validations on the input data

  // Prepare the SQL statement with parameter placeholders
  $sql = "UPDATE class SET name=?, teacher_id=?, day=?, time_slot=? WHERE class_id=?";

  // Create a prepared statement
  $stmt = mysqli_prepare($con, $sql);

  // Bind the parameters with the corresponding values
  mysqli_stmt_bind_param($stmt, "ssssi", $name, $teacher_id, $day, $timeSlot, $class_id);

  // Execute the prepared statement
  if (mysqli_stmt_execute($stmt)) {
    echo '<script language="javascript">
            alert("Class updated successfully.");
            window.location.replace("class.php");
          </script>';
  } else {
    echo "ERROR: Failed to update class. " . mysqli_error($con);
  }

  // Close the prepared statement
  mysqli_stmt_close($stmt);
}

mysqli_close($con);
?>
