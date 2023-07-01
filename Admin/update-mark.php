<?php
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Taking all values from the mark form data(input)
  $mark_id = $_POST['mark_id'];
  $test_name = $_POST['test_name'];
  $marks_obtained = $_POST['marks_obtained'];
  $student_id = $_POST['student_id'];
  $class_id = $_POST['class_id'];

  // Perform necessary validations on the input data

  // Get the teacher ID for the selected class
  $teacherQuery = "SELECT teacher_id FROM class WHERE class_id = ?";
  $teacherStmt = mysqli_prepare($con, $teacherQuery);
  mysqli_stmt_bind_param($teacherStmt, "s", $class_id);
  mysqli_stmt_execute($teacherStmt);
  $teacherResult = mysqli_stmt_get_result($teacherStmt);

  if (mysqli_num_rows($teacherResult) > 0) {
    $teacherRow = mysqli_fetch_assoc($teacherResult);
    $teacher_id = $teacherRow['teacher_id'];

    // Update the mark record in the database
    $sql = "UPDATE marks SET test_name=?, marks_obtained=?, student_id=?, class_id=?, teacher_id=? WHERE mark_id=?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "ssssss", $test_name, $marks_obtained, $student_id, $class_id, $teacher_id, $mark_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
      echo '<script language="javascript">
              alert("Data updated in the database successfully.");
              window.location.replace("marks.php");
            </script>';
    } else {
      echo "ERROR: Failed to update data in the database. " . mysqli_error($con);
    }
  } else {
    echo "ERROR: Failed to retrieve teacher information for the selected class.";
  }
}

mysqli_close($con);
?>
