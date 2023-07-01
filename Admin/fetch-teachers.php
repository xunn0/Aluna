<?php
include('connection.php');

if (isset($_GET['class_id'])) {
  $classID = $_GET['class_id'];

  if ($classID == '') {
    // Return an empty response if no class is selected
    echo json_encode(array());
    exit();
  }

  $teacherQuery = "SELECT teacher.* FROM teacher INNER JOIN class ON teacher.teacher_id = class.teacher_id WHERE class.class_id = '$classID'";
  $teacherResult = mysqli_query($con, $teacherQuery);

  if (mysqli_num_rows($teacherResult) > 0) {
    $teachers = array();

    while ($teacherRow = mysqli_fetch_assoc($teacherResult)) {
      $teachers[] = $teacherRow;
    }

    // Return the teacher details as JSON response
    echo json_encode($teachers);
  } else {
    // Return an empty response if no teachers found
    echo json_encode(array());
  }
}
?>
