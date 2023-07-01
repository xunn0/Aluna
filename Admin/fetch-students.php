<?php
include('connection.php');
// Get the class ID from the request
$classID = $_GET['class_id'];

// Prepare the SQL query to fetch students for the selected class
$sql = "SELECT * FROM student WHERE student_id IN (
  SELECT student_id FROM class_student WHERE class_id = $classID
)";

// Execute the query
$result = $con->query($sql);

// Fetch the student records and store them in an array
$students = array();

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $students[] = $row;
  }
}

// Return the students as JSON
echo json_encode($students);
?>
