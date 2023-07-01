<?php
include('connection.php');

if (isset($_GET['class_id'])) {
  $classID = $_GET['class_id'];

  $teacherQuery = "SELECT teacher_id FROM class WHERE class_id = '$classID'";
  $teacherResult = mysqli_query($con, $teacherQuery);

  if (mysqli_num_rows($teacherResult) > 0) {
    $teacherRow = mysqli_fetch_assoc($teacherResult);
    $teacherID = $teacherRow['teacher_id'];
    echo $teacherID;
  } else {
    echo "Teacher ID not found";
  }
}

mysqli_close($con);
?>
