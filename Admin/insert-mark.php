<?php
include('connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Taking all values from the mark form data(input)
  $test_name = $_POST['test_name'];
  $marks_obtained = $_POST['marks_obtained'];
  $student_id = $_POST['student_id'];
  $class_id = $_POST['class_id'];

  // Perform necessary validations on the input data

  // Get the teacher ID for the selected class
  $teacherQuery = "SELECT teacher_id FROM class WHERE class_id = ?";
  $teacherStatement = mysqli_prepare($con, $teacherQuery);
  mysqli_stmt_bind_param($teacherStatement, "s", $class_id);
  mysqli_stmt_execute($teacherStatement);
  $teacherResult = mysqli_stmt_get_result($teacherStatement);

  if (mysqli_num_rows($teacherResult) > 0) {
    $teacherRow = mysqli_fetch_assoc($teacherResult);
    $teacher_id = $teacherRow['teacher_id'];

    // Insert the mark record into the database
    $sql = "INSERT INTO marks (test_name, marks_obtained, student_id, class_id, teacher_id) VALUES (?, ?, ?, ?, ?)";
    $statement = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($statement, "sssss", $test_name, $marks_obtained, $student_id, $class_id, $teacher_id);

    if (mysqli_stmt_execute($statement)) {
      echo '<script language="javascript">
              alert("Data stored in the database successfully.");
              window.location.replace("marks.php");
            </script>';
    } else {
      echo "ERROR: Failed to store data in the database. " . mysqli_error($con);
    }
  } else {
    echo "ERROR: Failed to retrieve teacher information for the selected class.";
  }
}

mysqli_close($con);
?>