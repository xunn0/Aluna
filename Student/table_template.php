<?php
session_start();
include('connection.php');

$studentId = $_SESSION['student_id'];
// Retrieve student information
$stmt = $con->prepare("SELECT * FROM student WHERE student_id = ?");
$stmt->bind_param("i", $studentId); // Bind the parameter
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $studentName = $row['name'];
} else {
    $error = "Student not found.";
}

$fetchAttendanceQuery = "SELECT class.name,
                        COUNT(CASE WHEN attendance.status = 'Present' THEN 1 END) AS classes_attended,
                        COUNT(attendance.status) AS total_classes
                        FROM attendance
                        INNER JOIN class ON attendance.class_id = class.class_id
                        WHERE attendance.student_id = '$studentId'
                        GROUP BY attendance.class_id
                        ORDER BY attendance.date DESC";

$attendanceResult = mysqli_query($con, $fetchAttendanceQuery);

if (mysqli_num_rows($attendanceResult) > 0) {
    $recordMsg = '<html>
                    <head>
                        <title>Attendance Record</title>
                        <style>
                            body {
                                text-align: center;
                            }
                            table {
                                border-collapse: collapse;
                                width: 60%;
                                margin: 0 auto;
                            }
                            th, td {
                                border: 1px solid #ddd;
                                padding: 8px;
                                text-align: left;
                            }
                        </style>
                    </head>
                    <body>
                        <h2>Attendance Record</h2>
                        <p>Student ID: ' . $studentId . '</p>
                        <p>Student Name: ' . $studentName . '</p>
                        <table id="record_table">
                            <tr>
                                <th>Class Name</th>
                                <th>Attendance Percentage</th>
                            </tr>';

    while ($row = mysqli_fetch_assoc($attendanceResult)) {
        $className = $row['name'];
        $classesAttended = $row['classes_attended'];
        $totalClasses = $row['total_classes'];
        $attendancePercentage = round(($classesAttended / $totalClasses) * 100, 2);

        $recordMsg .= "<tr><td>$className</td><td>$attendancePercentage%</td></tr>";
    }

    $recordMsg .= '</table>
                </body>
            </html>';
} else {
    $recordMsg = 'No attendance record found.';
}

echo $recordMsg;
?>
