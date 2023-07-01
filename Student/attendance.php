<?php
session_start();
// Include the database connection file
include('connection.php');

// Initialize variables
$studentId = $_SESSION['student_id'];
$recordMsg = '';

// Fetch the attendance record for the current logged in student
$fetchAttendanceQuery = "SELECT class.name,
                        COUNT(CASE WHEN attendance.status = 'Present' THEN 1 END) AS classes_attended,
                        COUNT(attendance.status) AS total_classes
                        FROM attendance
                        INNER JOIN class ON attendance.class_id = class.class_id
                        WHERE attendance.student_id = '$studentId'
                        GROUP BY attendance.class_id
                        ORDER BY attendance.date DESC";

$attendanceResult = mysqli_query($con, $fetchAttendanceQuery);

// Check if the attendance record is empty
if (mysqli_num_rows($attendanceResult) > 0) {
    // Generate the HTML table for the attendance record
    $recordMsg .= '<table id="record_table">';
    $recordMsg .= '<tr><th>Class Name</th><th>Attendance Percentage</th></tr>';

    while ($row = mysqli_fetch_assoc($attendanceResult)) {
        $className = $row['name'];
        $classesAttended = $row['classes_attended'];
        $totalClasses = $row['total_classes'];
        $attendancePercentage = round(($classesAttended / $totalClasses) * 100, 2);

        $recordMsg .= "<tr><td>$className</td><td>$attendancePercentage%</td></tr>";
    }

    $recordMsg .= '</table>';
} else {
    $recordMsg = 'No attendance record found.';
}

// Check if the request contains the scanned QR code content
if (isset($_POST['qr_content'])) {
    $qrContent = $_POST['qr_content'];

    // Extract the class ID, time, and date from the QR code content
    list($classId, $time, $date) = explode('|', $qrContent);

    // Check if the student has already attended the class
    $checkAttendanceQuery = "SELECT * FROM attendance WHERE student_id = '$studentId' AND class_id = '$classId' AND `date` = '$date'";

    $attendanceResult = mysqli_query($con, $checkAttendanceQuery);

    if (mysqli_num_rows($attendanceResult) > 0) {
        // The student has already attended this class
        $updateAttendanceQuery = "UPDATE attendance SET `time` = '$time', `status` = 'Present' WHERE student_id = '$studentId' AND class_id = '$classId' AND `date` = '$date'";

        if (mysqli_query($con, $updateAttendanceQuery)) {
            // Attendance updated successfully
            echo 'Attendance updated.';
        } else {
            // Error updating attendance
            echo 'Failed to update attendance.';
        }
    } else {
        // Record the attendance in the database
        $insertAttendanceQuery = "INSERT INTO attendance (student_id, class_id, `time`, `date`, `status`) VALUES ('$studentId', '$classId', '$time', '$date', 'present')";

        if (mysqli_query($con, $insertAttendanceQuery)) {
            // Attendance recorded successfully
            echo 'Attendance recorded.';
        } else {
            // Error recording attendance
            echo 'Failed to record attendance.';
        }
    }
} else {
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Aluna-Attendance</title>
    <link rel="stylesheet" type="text/css" href="../css/attendance.css">
    <link rel="stylesheet" type="text/css" href="../css/nav.css">
    <link rel="shortcut icon" type="image/x-icon" href="../img/favicon.ico" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="https://rawgit.com/schmich/instascan-builds/master/css/instascan.min.css">
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
</head>

<body>
    <!-- Nav -->
    <div id="nav-placeholder"></div>
    <h2 style="margin-left: 30px;">My Attendance Record</h2>
    <h3 class="qr-msg">QR attendance scanning is only available on mobile</h3>
    <button id="print-report" class="print3" onclick="printTable()">Print Report</button>

    <video id="scanner-video"></video>
    <div id="scanned-content"></div>

    <div class="table-container table3" style="margin-top: 20%; margin-left: 10%;">
        <?php echo $recordMsg; ?>
    </div>

    <!-- darkmode -->
    <main>
        <header>
            <div>
                <a id="theme_switch">
                    <i onclick="toggleDarkMode()" class='fa-solid fa-sun'></i>
                </a>
            </div>
        </header>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- nav.js -->
    <script src="../JS/nav.js"></script>
    <!-- dark-mode.js -->
    <script src="../JS/dark-mode.js"></script>
    <!-- Table.js -->
    <script src="../JS/Table.js"></script>
    <script>
        function printTable() {
            var tableHtml = document.getElementById('record_table').innerHTML;

            var newWindow = window.open('', '_blank');
            newWindow.document.write('<html><head><title>Record Table</title>');
            newWindow.document.write('<style>');
            newWindow.document.write('table { border-collapse: collapse; width: 100%; }');
            newWindow.document.write('th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }');
            newWindow.document.write('</style>');
            newWindow.document.write('</head><body>');
            newWindow.document.write('<h2>Record Table</h2>');
            newWindow.document.write('<table>' + tableHtml + '</table>');
            newWindow.document.write('</body></html>');
            newWindow.document.close();

            newWindow.print();
        }


        // Check if the device is a phone based on screen width
        function isPhoneLayout() {
            return window.innerWidth <= 360; // Adjust the breakpoint as needed
        }

        // Initialize the QR code scanner
        function initializeScanner() {
            var scanner = new Instascan.Scanner({
                video: document.getElementById('scanner-video')
            });

            scanner.addListener('scan', function(content) {
                // Send an HTTP POST request to record the attendance
                fetch('attendance.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'student_id=' + encodeURIComponent(<?php echo json_encode($studentId); ?>) + '&qr_content=' + encodeURIComponent(content),
                    })
                    .then(response => {
                        if (response.ok) {
                            console.log('Attendance recorded successfully.');
                        } else {
                            console.error('Failed to record attendance.');
                        }
                    })
                    .catch(error => {
                        console.error('Failed to record attendance.', error);
                    });


                // For example, display the scanned content on the page
                document.getElementById('scanned-content').textContent = content;
            });

            Instascan.Camera.getCameras().then(function(cameras) {
                if (cameras.length > 0 && isPhoneLayout()) {
                    scanner.start(cameras[0]); // Use the first available camera
                } else {
                    console.log('No cameras found or not in phone layout.');
                }
            }).catch(function(error) {
                console.error(error);
            });
        }

        window.onload = function() {
            initializeScanner();
        };

        window.addEventListener('resize', function() {
            if (scanner) {
                scanner.stop();
            }
            initializeScanner();
        });
    </script>
</body>

</html>
