<?php
// Include the database connection file
include('connection.php');

// Get the student ID from the query parameter
$studentId = $_GET['id'];

// Fetch the classes attended by the student
$fetchClassesQuery = "SELECT class_id FROM class_student WHERE student_id = '$studentId'";
$classesResult = mysqli_query($con, $fetchClassesQuery);

$studentClasses = [];
while ($class = mysqli_fetch_assoc($classesResult)) {
    $studentClasses[] = $class['class_id'];
}

// Return the student's classes as JSON
header('Content-Type: application/json');
echo json_encode($studentClasses);
?>
