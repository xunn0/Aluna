<?php
session_start();

include('connection.php');

$studentId = $_SESSION['student_id'];
$recordMsg = '';

$fetchAttendanceQuery = "SELECT class.name,
                        COUNT(CASE WHEN attendance.status = 'present' THEN 1 END) AS classes_attended,
                        COUNT(attendance.status) AS total_classes
                        FROM attendance
                        INNER JOIN class ON attendance.class_id = class.class_id
                        WHERE attendance.student_id = '$studentId'
                        GROUP BY attendance.class_id
                        ORDER BY attendance.date DESC";

$attendanceResult = mysqli_query($con, $fetchAttendanceQuery);

if (mysqli_num_rows($attendanceResult) > 0) {
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

if (isset($_POST['qr_content'])) {
    $qrContent = $_POST['qr_content'];

    list($classId, $time, $date) = explode('|', $qrContent);

    // Check if the student is enrolled in the class
    $checkEnrollmentQuery = "SELECT * FROM class_student WHERE student_id = '$studentId' AND class_id = '$classId'";
    $enrollmentResult = mysqli_query($con, $checkEnrollmentQuery);

    if (mysqli_num_rows($enrollmentResult) > 0) {
        $checkAttendanceQuery = "SELECT * FROM attendance WHERE student_id = '$studentId' AND class_id = '$classId' AND `date` = '$date'";
        $attendanceResult = mysqli_query($con, $checkAttendanceQuery);

        if (mysqli_num_rows($attendanceResult) > 0) {
            $attendanceData = mysqli_fetch_assoc($attendanceResult);
            $existingTime = $attendanceData['time'];
            $existingStatus = $attendanceData['status'];

            if ($existingTime == $time) {
                if ($existingStatus == 'absent') {
                    $updateAttendanceQuery = "UPDATE attendance SET `status` = 'present' WHERE student_id = '$studentId' AND class_id = '$classId' AND `date` = '$date'";

                    if (mysqli_query($con, $updateAttendanceQuery)) {
                        echo 'Attendance Updated.';
                    } else {
                        echo 'Failed to update attendance.';
                    }
                } else {
                    echo 'Attendance Updated.';
                }
            } else {
                $updateAttendanceQuery = "UPDATE attendance SET `time` = '$time', `status` = 'present' WHERE student_id = '$studentId' AND class_id = '$classId' AND `date` = '$date'";

                if (mysqli_query($con, $updateAttendanceQuery)) {
                    echo 'Attendance Updated.';
                } else {
                    echo 'Failed to update attendance.';
                }
            }
        } else {
            $insertAttendanceQuery = "INSERT INTO attendance (student_id, class_id, `time`, `date`, `status`) VALUES ('$studentId', '$classId', '$time', '$date', 'present')";

            if (mysqli_query($con, $insertAttendanceQuery)) {
                echo 'Attendance Recorded.';
            } else {
                echo 'Failed to record attendance.';
            }
        }
    } else {
        echo 'not_enrolled';
    }
    exit; // Stop further execution to prevent rendering the HTML content
}
?>


<!DOCTYPE html>
<html>

<head>
  <title>Aluna-Attendance</title>
  <link rel="stylesheet" type="text/css" href="../css/attendance.css">
  <link rel="stylesheet" type="text/css" href="../css/nav.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
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

  <script>
    function printTable() {
      var xhr = new XMLHttpRequest();
      xhr.open('GET', 'table_template.php', true);
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          var templateContent = xhr.responseText;
          var tableHtml = document.getElementById('record_table').innerHTML;
          var combinedHtml = templateContent.replace('<table id="record_table"></table>', tableHtml);

          // Create a new window to display the PDF preview
          var newWindow = window.open('', '_blank');
          newWindow.document.write(combinedHtml);
          newWindow.document.close();

          // Call the print function on the new window
          newWindow.print();
        }
      };
      xhr.send();
    }

    function initializeScanner() {
  var scannerVideo = document.getElementById('scanner-video');

  var scanner = new Instascan.Scanner({ video: scannerVideo });
  scanner.addListener('scan', function (content) {
    var qrData = content.split('|');
    var classId = qrData[0];
    var time = qrData[1];
    var date = qrData[2];

    fetch('attendance.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body:
        'student_id=' +
        encodeURIComponent(<?php echo json_encode($studentId); ?>) +
        '&qr_content=' +
        encodeURIComponent(content)
    })
      .then(function (response) {
        if (response.ok) {
          response.text().then(function (data) {
            if (data === 'not_enrolled') {
              swal("You are not enrolled in this class.", "", "error");
            } else {
              swal("Attendance recorded.", "", "success");
            }
          });
        } else {
          swal("Failed to record attendance.", "", "error");
        }
      })
      .catch(function (error) {
        console.error('Failed to record attendance.', error);
        swal("Failed to record attendance.", "", "error");
      });
  });

      var selectedCamera = null;

      Instascan.Camera.getCameras()
        .then(function (cameras) {
          if (cameras.length > 0) {
            // Find the back camera
            var backCamera = cameras.find(function (camera) {
              return camera.name.toLowerCase().includes('back');
            });

            // Use the back camera if found, otherwise use the first available camera
            selectedCamera = backCamera || cameras[0];

            scanner.start(selectedCamera);
          } else {
            console.error('No cameras found.');
          }
        })
        .catch(function (error) {
          console.error('Error accessing cameras:', error);
        });

      // Remove mirroring effect from video feed
      scannerVideo.style.transform = 'scaleX(1)';
    }

    document.addEventListener('DOMContentLoaded', function () {
      initializeScanner();
    });

  </script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <!-- nav.js -->
  <script src="../JS/nav.js"></script>
  <!-- dark-mode.js -->
  <script src="../JS/dark-mode.js"></script>
  <!-- Table.js -->
  <script src="../JS/Table.js"></script>
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

</body>

</html>

