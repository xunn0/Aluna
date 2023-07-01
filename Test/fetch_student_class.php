<?php
// Include the database connection file
include('connection.php');

// Fetch the student-class data from the database
$selectQuery = "SELECT student_id, class_id FROM class_student";
$result = mysqli_query($con, $selectQuery);

$studentClassData = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $studentId = $row['student_id'];
        $classId = $row['class_id'];

        $studentClassData[$studentId] = $classId;
    }
}

// Return the student-class data as JSON
echo json_encode($studentClassData);
?>
