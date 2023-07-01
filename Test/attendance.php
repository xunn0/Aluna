<?php
// Include the database connection file
include('connection.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the selected class, attendance time, and attendance date from the form
  if (isset($_POST['class_id']) && isset($_POST['attendance_time']) && isset($_POST['attendance_date'])) {
    $classId = $_POST['class_id'];
    $attendanceTime = $_POST['attendance_time'];
    $attendanceDate = $_POST['attendance_date'];

    // Get the student IDs and attendance status from the form
    if (isset($_POST['student_ids']) && isset($_POST['attendance_status'])) {
      $studentIds = $_POST['student_ids'];
      $attendanceStatus = $_POST['attendance_status'];

      // Loop through the student IDs and attendance status arrays
      foreach ($studentIds as $index => $studentId) {
        // Get the attendance status for the current student
        if (isset($attendanceStatus[$studentId])) {
          $status = $attendanceStatus[$studentId];

          // Insert the attendance record into the database
          $insertQuery = "INSERT INTO attendance (class_id, time, date, student_id, status) VALUES ('$classId', '$attendanceTime', '$attendanceDate', '$studentId', '$status')";

          // Log the SQL query for debugging purposes
          error_log("SQL Query: " . $insertQuery);

          // Execute the query
          $result = mysqli_query($con, $insertQuery);

          // Check if the query execution was successful
          if (!$result) {
            // Log the database error
            error_log("Database Error: " . mysqli_error($con));
          }
        }
      }

      // Redirect back to the attendance page or display a success message
      header('Location: attendance.php');
      exit();
    }
  }
}
?>
<html>

<head>
  <title>Aluna-Attendance</title>
  <link rel="stylesheet" type="text/css" href="../css/attendance.css">
  <link rel="stylesheet" type="text/css" href="../css/nav.css">
  <link rel="shortcut icon" type="image/x-icon" href="../img/favicon.ico" />
  <!-- MultiNav -->
  <script src="//code.jquery.com/jquery.min.js"></script>
</head>

<body>
  <!-- Nav -->
  <div id="nav-placeholder"></div>

  <div class="container">
    <div class="header_wrap">
      <div class="num_rows">
        <div class="form-group">
          <select class="form-control" name="state" id="maxRows">
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
            <option value="50">50</option>
            <option value="70">70</option>
            <option value="100">100</option>
            <option value="5000">Show All</option>
          </select>
        </div>
      </div>
      <div class="tb_search">
        <input type="text" id="search_input_all" onkeyup="FilterkeyWord_all_table()" placeholder="Search.." class="form-control">
      </div>

      <button id="print-report" onclick="printTable()">Print Report</button>
      <form id="attendance-form" action="process-attendance.php" method="POST">
    </div>
    <div class="table-container" style="width: 100%;">
      <table class="table table-striped table-class" id="table-id" style="width: 50%;">
        <thead>
          <tr>
            <th style="width: 90px;">ID</th>
            <th style="width: 300px;">Student</th>
            <th style="width: 100px;">Attendance</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Fetch the selected class ID from the form
          $selectedClassId = $_POST['class_id'] ?? '';

          // Fetch the list of students from the database
          include('connection.php');
          $selectQuery = "SELECT * FROM student";
          $result = mysqli_query($con, $selectQuery);
          if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              echo '<tr>';
              echo '<td class="student_id">' . $row['student_id'] . '</td>';
              echo '<td>' . $row['name'] . '</td>';
              echo '<td>';

              // Retrieve the class information for the student from class_student table
              $classQuery = "SELECT class_id FROM class_student WHERE student_id = " . $row['student_id'];
              $classResult = mysqli_query($con, $classQuery);
              if (mysqli_num_rows($classResult) > 0) {
                $classRow = mysqli_fetch_assoc($classResult);
                $studentClassId = $classRow['class_id'];

                // Check if the student's class matches the selected class
                if ($selectedClassId === "" || $studentClassId === $selectedClassId) {
                  echo '<input type="hidden" name="student_ids[]" value="' . $row['student_id'] . '">';
                  echo '<input type="checkbox" name="attendance_status[' . $row['student_id'] . ']" value="' . $row['student_id'] . '"> present';
                } else {
                  echo 'No class found';
                }
              } else {
                echo 'No class found';
              }

              echo '</td>';
              echo '</tr>';
            }
          } else {
            echo '<tr><td colspan="2">No students found</td></tr>';
          }
          ?>

        </tbody>
      </table>
      </form>
    </div>
    <div class="card">
      <div class="tb-class">
        <label for="class_id">Class:</label>
        <select class="form-control" name="class_id" id="class_id" onchange="filterStudentsByClass()">
          <option value="">All Classes</option>
          <?php
          include('connection.php');

          $selectQuery = "SELECT * FROM class";
          $result = mysqli_query($con, $selectQuery);
          if (!$result) {
            echo '<option value="">Error retrieving classes: ' . mysqli_error($con) . '</option>';
          } else {
            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                echo '<option value="' . $row['class_id'] . '">' . $row['name'] . ' | Form: ' . $row['form'] . '</option>';
              }
            } else {
              echo '<option value="">No classes found</option>';
            }
          }
          ?>
        </select>
      </div>

      <div class="tb-time">
        <label for="attendance_time">Time:</label>
        <select class="form-control" id="attendance_time" name="attendance_time">
          <option value="8:30AM">8:30AM</option>
          <option value="10:30AM">10:30AM</option>
          <option value="2:30PM">2:30PM</option>
          <option value="5:30PM">5:30PM</option>
          <option value="7:30PM">7:30PM</option>
          <option value="9:30PM">9:30PM</option>
        </select>
      </div>

      <div class="tb-date">
        <label for="attendance_date">Date:</label>
        <input type="date" id="attendance_date" name="attendance_date">
      </div>
    </div>

    <button type="button" class="submit-btn" onclick="submitAttendance()">Submit Attendance</button>
    <!-- darkmode -->
    <main>
      <header>
        <div>
          <a id="theme_switch">
            <i onclick="toggledarkmode()" class='fa-solid fa-sun'></i>
          </a>
        </div>
      </header>
    </main>

    <!-- nav.js -->
    <script src="../JS/nav.js"></script>
    <!-- dark-mode.js -->
    <script src="../JS/dark-mode.js"></script>
    <!-- Table.js -->
    <script src="../JS/Table.js"></script>

    <script>
      function submitAttendance() {
        var form = document.getElementById('attendance-form');
        var formData = new FormData(form);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);
        xhr.onload = function() {
          if (xhr.status === 200) {
            alert('Attendance submitted!');
          } else {
            alert('Error submitting attendance. Please try again.');
          }
        };
        xhr.onerror = function() {
          alert('Error submitting attendance. Please try again.');
        };
        xhr.send(formData);
      }

      // Function to filter students by class
      function filterStudentsByClass() {
        var selectedClassId = document.getElementById("class_id").value;
        var tableRows = document.querySelectorAll("#table-id tbody tr");

        if (selectedClassId === '') {
          // No class is selected, so show all students
          for (var i = 0; i < tableRows.length; i++) {
            tableRows[i].style.display = "";
          }
        } else {
          // A class is selected, so show only students in that class
          for (var i = 0; i < tableRows.length; i++) {
            var classIdCell = tableRows[i].querySelector(".class_id");
            var selectedClassIdFromSelect = document.getElementById("class_id").getAttribute("value");

            if (classIdCell.getAttribute("class_id") === selectedClassIdFromSelect) {
              tableRows[i].style.display = "";
            } else {
              tableRows[i].style.display = "none";
            }
          }
        }
      }

      // Function to check if a student is in the selected class
      function isStudentInSelectedClass(studentId, selectedClassId) {
        var classRows = document.querySelectorAll("#table-id tbody tr[data-class]");

        for (var i = 0; i < classRows.length; i++) {
          var classRow = classRows[i];
          var classId = classRow.getAttribute("data-class");

          if (classId === selectedClassId && classRow.querySelector(".student_id").textContent === studentId) {
            return true;
          }
        }

        return false;
      }

      // Fetch student-class data on page load
      fetchStudentClassData();

      // Function to fetch student-class data asynchronously
      function fetchStudentClassData() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'fetch_student_class.php', true);
        xhr.onreadystatechange = function() {
          if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
            var studentClassData = JSON.parse(xhr.responseText);
            applyStudentClassData(studentClassData);
          }
        };
        xhr.send();
      }

      // Function to apply student-class data to the table rows
      function applyStudentClassData(studentClassData) {
        var tableRows = document.querySelectorAll("#table-id tbody tr");

        for (var i = 0; i < tableRows.length; i++) {
          var studentIdCell = tableRows[i].querySelector(".student_id");
          var studentRow = tableRows[i];
          var studentId = studentIdCell.textContent;

          if (studentClassData.hasOwnProperty(studentId)) {
            var classId = studentClassData[studentId];
            studentRow.setAttribute("data-class", classId);
          } else {
            studentRow.removeAttribute("data-class");
          }
        }

        filterStudentsByClass();
      }

      // Event listener for class selection change
      document.getElementById("class_id").addEventListener("change", filterStudentsByClass);

      // Function to print the attendance table
      function printTable() {
        var printContent = document.getElementById("table-id").outerHTML;
        var originalContent = document.body.innerHTML;
        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;
      }
    </script>

</body>

</html>