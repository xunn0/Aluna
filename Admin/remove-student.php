<?php
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $classId = $_POST['classId'];
  $studentId = $_POST['studentId'];

  // Remove the student from the class
  $removeQuery = "DELETE FROM class_student WHERE class_id = '$classId' AND student_id = '$studentId'";
  $removeResult = mysqli_query($con, $removeQuery);

  if ($removeResult) {
    // Student successfully removed from the class
    header("Location: class.php");
    exit();
  } else {
    // Error occurred while removing the student
    echo "Error: " . mysqli_error($con);
  }
}
?>
