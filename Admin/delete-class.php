<?php
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['class_id'])) {
  $classId = $_GET['class_id'];

  // Delete associated records in the 'marks' table
  $deleteMarksQuery = "DELETE FROM marks WHERE class_id = '$classId'";
  if (mysqli_query($con, $deleteMarksQuery)) {
    // Delete associated records from the class_student table
    $deleteStudentsQuery = "DELETE FROM class_student WHERE class_id = '$classId'";
    if (mysqli_query($con, $deleteStudentsQuery)) {
      // Delete the class
      $deleteClassQuery = "DELETE FROM class WHERE class_id = '$classId'";
      if (mysqli_query($con, $deleteClassQuery)) {
        echo '<script language="javascript">
               alert("Class deleted successfully.");
               window.location.replace("class.php");
              </script>';
      } else {
        echo "ERROR: Failed to delete class. " . mysqli_error($con);
      }
    } else {
      echo "ERROR: Failed to delete associated students. " . mysqli_error($con);
    }
  } else {
    echo "ERROR: Failed to delete associated marks. " . mysqli_error($con);
  }
}

mysqli_close($con);
?>
