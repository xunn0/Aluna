<?php
// Include the database connection file
include('connection.php');

// Retrieve the class ID and time values from the AJAX request
$classId = $_POST['class_id'];
$time = $_POST['attendance_time'];

// Fetch the record table data based on the selected class ID and time
$fetchRecordQuery = "SELECT attendance.*, CONCAT(student.student_id, ' | ', student.name) AS student, CONCAT(class.class_id, ' | ', class.name) AS class_info, class.form
                     FROM attendance
                     INNER JOIN student ON attendance.student_id = student.student_id
                     INNER JOIN class ON attendance.class_id = class.class_id
                     WHERE attendance.class_id = '$classId' AND attendance.time = '$time'";
$recordResult = mysqli_query($con, $fetchRecordQuery);

// Generate the HTML markup for the record table
$html = '';
$counter = 1;
while ($record = mysqli_fetch_assoc($recordResult)) {
    // Build the table row HTML based on the record data
    $html .= "<tr>";
    $html .= "<td class='record-table-cell'>$counter</td>";
    $html .= "<td class='record-table-cell'>{$record['student']}</td>";
    $html .= "<td class='record-table-cell'>{$record['class_info']}</td>";
    $html .= "<td class='record-table-cell'>{$record['form']}</td>";
    $html .= "<td class='record-table-cell'>{$record['time']}</td>";
    $html .= "<td class='record-table-cell'>{$record['date']}</td>";
    $html .= "<td class='record-table-cell'>{$record['status']}</td>";
    $html .= "</tr>";

    $counter++;
}

// Return the generated HTML markup
echo "<table class='record-table'>
        <thead>
            <tr>
                <th class='record-table-header' style='width: 50px;'>No.</th>
                <th class='record-table-header' style='width: 350px;'>Student</th>
                <th class='record-table-header' style='width: 230px;'>Class</th>
                <th class='record-table-header' style='width: 40px;'>Form</th>
                <th class='record-table-header' style='width: 180px;'>Time</th>
                <th class='record-table-header' style='width: 90px;'>Date</th>
                <th class='record-table-header' style='width: 90px;'>Status</th>
            </tr>
        </thead>
        <tbody>
            $html
        </tbody>
    </table>";
?>
