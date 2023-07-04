<?php

include('connection.php');


$successMsg = $failureMsg = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $classId = $_POST['class_id'];
    $time = $_POST['attendance_time'];
    $date = $_POST['attendance_date'];
    $attendanceData = $_POST['attendance'];
    $absentData = $_POST['absent']; 


    $deleteQuery = "DELETE FROM attendance WHERE class_id = '$classId' AND time = '$time' AND date = '$date'";
    mysqli_query($con, $deleteQuery);


    $insertQuery = "INSERT INTO attendance (class_id, time, date, student_id, status) VALUES ";
    $values = array();
    foreach ($attendanceData as $studentId) {
        $values[] = "('$classId', '$time', '$date', '$studentId', 'present')";
    }
    $insertQuery .= implode(', ', $values);


    mysqli_query($con, $insertQuery);


    if (!empty($absentData)) {
        $insertAbsentQuery = "INSERT INTO attendance (class_id, time, date, student_id, status) VALUES ";
        $absentValues = array();
        foreach ($absentData as $studentId) {
            $absentValues[] = "('$classId', '$time', '$date', '$studentId', 'absent')";
        }
        $insertAbsentQuery .= implode(', ', $absentValues);


        mysqli_query($con, $insertAbsentQuery);
    }
}

// Fetch all students from the student table
$fetchStudentsQuery = "SELECT DISTINCT student.*, c.form 
                       FROM student 
                       LEFT JOIN class_student cs ON student.student_id = cs.student_id
                       LEFT JOIN class c ON cs.class_id = c.class_id";

$studentsResult = mysqli_query($con, $fetchStudentsQuery);

// Fetch all classes and their forms from the class_student table
$fetchClassesQuery = "SELECT DISTINCT c.class_id, c.name, c.form
                      FROM class_student cs
                      INNER JOIN class c ON cs.class_id = c.class_id";

$classesResult = mysqli_query($con, $fetchClassesQuery);

// Fetch all rows from the classesResult and store them in an array
$classes = [];
while ($class = mysqli_fetch_assoc($classesResult)) {
    $classes[] = $class;
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
    <script src="https://code.jquery.com/jquery.min.js"></script>
    <script>
        function filterStudents() {
            var selectedClass = document.getElementById('class_id').value;

            // Filter the attendance table
            var attendanceTable = document.getElementById('attendance_table');
            var attendanceRows = attendanceTable.getElementsByTagName('tr');

            for (var i = 1; i < attendanceRows.length; i++) {
                var row = attendanceRows[i];
                var studentId = row.cells[0].innerText;
                var studentClasses = row.getAttribute('data-classes').split(',');

                if (selectedClass === '' || studentClasses.includes(selectedClass)) {
                    row.style.display = ''; // Display the student
                    row.querySelector('input[name="attendance"]').disabled = false; // Enable the checkbox
                } else {
                    var selectedClassLength = selectedClass.length;

                    var found = false;

                    for (var j = 0; j < studentClasses.length; j++) {
                        var studentClass = studentClasses[j];
                        var studentClassLength = studentClass.length;

                        if (studentClassLength === selectedClassLength && studentClass.charAt(0) === selectedClass.charAt(0)) {
                            found = true;
                            break;
                        }
                    }

                    if (found) {
                        row.style.display = ''; // Display the student
                        row.querySelector('input[name="attendance"]').disabled = false; // Enable the checkbox
                    } else {
                        row.style.display = 'none'; // Hide the student
                        row.querySelector('input[name="attendance"]').disabled = true; // Disable the checkbox
                        row.querySelector('input[name="attendance"]').checked = false; // Uncheck the checkbox
                    }
                }
            }

            // Disable all checkboxes if no class is selected
            var checkboxes = document.querySelectorAll('input[name="attendance"]');
            if (selectedClass === '') {
                checkboxes.forEach(function(checkbox) {
                    checkbox.disabled = true;
                });
            }

            // Filter the record table
            var recordTable = document.getElementById('record_table');
            var recordRows = recordTable.getElementsByTagName('tr');

            for (var i = 1; i < recordRows.length; i++) {
                var row = recordRows[i];
                var classIdWithName = row.cells[2].innerText;
                var classId = classIdWithName.split(' | ')[0];

                if (selectedClass === '-- Select Class --' || classId === selectedClass || selectedClass === '') {
                    row.style.display = ''; // Display the record
                } else {
                    row.style.display = 'none'; // Hide the record
                }
            }

        }

        function selectChangeHandler() {
            var selectElement = document.getElementById("class_id");
            var checkboxes = document.querySelectorAll(".checkbox-class");

            // Get the selected value
            var selectedValue = selectElement.value;

            // Disable or enable checkboxes based on the selected value
            checkboxes.forEach(function(checkbox) {
                checkbox.disabled = selectedValue === "-- Select Class --";
            });

            // Call the reloadRecordTable() function
            reloadRecordTable();
        }

        function updateCheckboxTooltip() {
            var checkboxes = document.querySelectorAll('input[name="attendance"]');
            checkboxes.forEach(function(checkbox) {
                var selectedClass = document.getElementById('class_id').value;
                if (selectedClass === '') {
                    checkbox.disabled = true;
                    checkbox.title = "Select a class first";
                } else {
                    checkbox.disabled = false;
                    checkbox.title = "";
                }
            });
        }

        // Call the function initially to set the tooltip messages
        updateCheckboxTooltip();

        // Update the tooltip messages whenever the class select changes
        var selectElement = document.getElementById("class_id");
        selectElement.addEventListener('change', updateCheckboxTooltip);

        // Update the tooltip messages whenever the class select changes
        var selectElement = document.getElementById("class_id");
        selectElement.addEventListener('change', updateCheckboxTooltip);

        function submitAttendance() {
            var selectedClass = document.getElementById('class_id').value;
            var time = document.getElementById('attendance_time').value;
            var date = document.getElementById('attendance_date').value;
            var checkboxes = document.querySelectorAll('input[name="attendance"]');
            var attendanceData = [];
            var absentData = []; // Array to store IDs of absent students

            // Get the selected and absent students
            checkboxes.forEach(function(checkbox) {
                var row = checkbox.parentNode.parentNode;
                var studentId = row.cells[0].innerText;
                var studentClasses = row.getAttribute('data-classes').split(',');

                if (checkbox.checked) {
                    attendanceData.push(checkbox.value);
                } else {
                    var found = false;

                    if (selectedClass === '') {
                        // If no class is selected, consider the student absent
                        found = true;
                    } else {
                        for (var j = 0; j < studentClasses.length; j++) {
                            var studentClass = studentClasses[j];
                            var studentClassLength = studentClass.length;

                            if (studentClassLength === selectedClass.length && studentClass.charAt(0) === selectedClass.charAt(0)) {
                                found = true;
                                break;
                            }
                        }
                    }

                    if (found) {
                        absentData.push(checkbox.value);
                    }
                }
            });

            // Check if class is selected
            if (selectedClass === "") {
                alert("Please select a class.");
                return;
            }

            // Check if time is selected
            if (time === "") {
                alert("Please select a time.");
                return;
            }

            // Check if date is selected
            if (date === "") {
                alert("Please select a date.");
                return;
            }

            // Check if at least one student is selected
            if (attendanceData.length === 0) {
                alert("Please select at least one student.");
                return;
            }

            // Perform AJAX request to submit the attendance
            $.ajax({
                url: '<?php echo $_SERVER["PHP_SELF"]; ?>',
                method: 'POST',
                data: {
                    class_id: selectedClass,
                    attendance_time: time,
                    attendance_date: date,
                    attendance: attendanceData,
                    absent: absentData // Pass the absent student IDs
                },
                success: function(response) {
                    // Display success message
                    var successMsg = document.getElementById('success-msg');
                    successMsg.innerText = "Attendance Recorded!";
                    successMsg.style.opacity = '1'; // Show the success message

                    var failureMsg = document.getElementById('failure-msg');
                    failureMsg.style.opacity = '0'; // Hide the failure message

                    // Hide the success message smoothly after 2 seconds
                    setTimeout(function() {
                        successMsg.style.opacity = '0';
                    }, 2000);

                    // Uncheck all checkboxes
                    var checkboxes = document.getElementsByName('attendance');
                    for (var i = 0; i < checkboxes.length; i++) {
                        checkboxes[i].checked = false;
                    }
                    reloadRecordTable();
                },
                error: function(xhr, status, error) {
                    // Display error message
                    var failureMsg = document.getElementById('failure-msg');
                    failureMsg.innerText = "Error Submitting Attendance!";
                    failureMsg.style.opacity = '1'; // Show the failure message

                    var successMsg = document.getElementById('success-msg');
                    successMsg.style.opacity = '0'; // Hide the success message

                    // Hide the failure message smoothly after 2 seconds
                    setTimeout(function() {
                        failureMsg.style.opacity = '0';
                    }, 2000);
                }
            });
        }

        function toggleTable() {
            var attendanceTable = document.getElementById('attendance_table');
            var recordTable = document.getElementById('record_table');
            var toggleBtn = document.querySelector('.toggle-button');

            if (attendanceTable.style.display === 'none') {
                attendanceTable.style.display = '';
                recordTable.style.display = 'none';
                toggleBtn.innerText = 'Toggle to Record';
            } else {
                attendanceTable.style.display = 'none';
                recordTable.style.display = '';
                toggleBtn.innerText = 'Toggle to Attendance';
            }
        }

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

        function searchTable() {
            var searchInput = document.getElementById('searchBar').value.toLowerCase();
            var attendanceTableRows = document.querySelectorAll('#attendance_table tbody tr');
            var recordTableRows = document.querySelectorAll('#record_table tbody tr');

            for (var i = 0; i < attendanceTableRows.length; i++) {
                var studentName = attendanceTableRows[i].cells[2].textContent.toLowerCase();
                var studentId = attendanceTableRows[i].cells[1].textContent.toLowerCase();

                if (studentName.includes(searchInput) || studentId.includes(searchInput)) {
                    attendanceTableRows[i].style.display = '';
                } else {
                    attendanceTableRows[i].style.display = 'none';
                }
            }

            for (var j = 0; j < recordTableRows.length; j++) {
                var recordStudentIdWithName = recordTableRows[j].cells[1].textContent.toLowerCase();
                var recordStudentId = recordStudentIdWithName.split(' | ')[0];
                var recordStudentName = recordStudentIdWithName.split(' | ')[1];

                if (recordStudentId.includes(searchInput) || recordStudentName.includes(searchInput)) {
                    recordTableRows[j].style.display = '';
                } else {
                    recordTableRows[j].style.display = 'none';
                }
            }
        }

        window.addEventListener('DOMContentLoaded', function() {
            var classSelect = document.getElementById('class_id');
            var attendanceCheckbox = document.getElementById('attendance_checkbox');

            classSelect.addEventListener('change', function() {
                var selectedClass = classSelect.value;
                if (selectedClass === '') {
                    attendanceCheckbox.disabled = true;
                    attendanceCheckbox.checked = false;
                } else {
                    attendanceCheckbox.disabled = false;
                    attendanceCheckbox.checked = false; // Uncheck the checkbox
                }

                filterStudents();
            });
        });

        function reloadRecordTable() {
            // Fetch the class ID and time values
            var selectedClass = document.getElementById('class_id').value;
            var selectedTime = document.getElementById('attendance_time').value;

            // Check if both class ID and time are selected
            if (selectedClass === '' || selectedTime === '') {
                return; // Exit the function if not selected
            }

            // Perform an AJAX request to fetch the updated record table HTML
            $.ajax({
                url: 'fetch_record_table.php', // Replace with the actual URL or file path to fetch the updated record table
                method: 'POST',
                data: {
                    class_id: selectedClass,
                    attendance_time: selectedTime
                },
                success: function(response) {
                    // Update the record table with the fetched HTML
                    var recordTable = document.getElementById('record_table');
                    recordTable.innerHTML = response;
                },
                error: function(xhr, status, error) {
                    console.error(error); // Log any errors to the console
                }
            });

        }

        // Call the reloadRecordTable() function initially to load the record table based on the selected class and time
        reloadRecordTable();
    </script>
</head>

<body>
    <!-- Nav -->
    <div id="nav-placeholder"></div>

    <div class="tb_search">
        <input type="text" id="searchBar" oninput="searchTable()" placeholder="Search by student id/name...">
    </div>
    <button class="toggle-btn" onclick="toggleTable()" title="Toggle table between Attendance and Record">Toggle</button>
    <button id="print-report" class="print1" onclick="printTable()">Print Report</button>

    <div class="card">
        <div class="tb-class">
            <label for="class_id">Class:</label>
            <select id="class_id" onchange="filterStudents(); selectChangeHandler(this)">
                <option value="">-- Select Class --</option>
                <?php
                // Loop through the classes and generate options
                foreach ($classes as $class) {
                    echo "<option value='{$class['class_id']}'>ID: {$class['class_id']} | {$class['name']} | F{$class['form']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="tb-time">
            <label for="attendance_time">Time:</label>
            <select id="attendance_time">
                <option value="">-- Select Time --</option>
                <option value="08:30 AM - 10:30 AM">08:30 AM - 10:30 AM</option>
                <option value="10:30 AM - 12:30 PM">10:30 AM - 12:30 PM</option>
                <option value="02:30 PM - 04:30 PM">02:30 PM - 04:30 PM</option>
                <option value="04:30 PM - 06:30 PM">04:30 PM - 06:30 PM</option>
                <option value="07:30 PM - 09:30 PM">07:30 PM - 09:30 PM</option>
                <option value="09:30 PM - 11:30 PM">09:30 PM - 11:30 PM</option>
            </select>
        </div>

        <div class="tb-date">
            <label for="attendance_date">Date:</label>
            <input type="date" id="attendance_date" />
        </div>
    </div>

    <div class="table-container">
        <table id="attendance_table">
            <thead>
                <tr>
                    <th style="width: 90px;">Student ID</th>
                    <th style="width: 350px;">Student Name</th>
                    <th style="width: 90px;">Action</th>
                    <th style="width: 90px;">Form</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Define and set the $checkboxDisabled variable based on your condition
                $checkboxDisabled = true; // or false, depending on your logic

                while ($student = mysqli_fetch_assoc($studentsResult)) {
                    echo "<tr data-classes='";

                    // Get the classes attended by the student
                    $studentId = $student['student_id'];
                    $fetchStudentClassesQuery = "SELECT c.class_id, c.form FROM class_student cs
                     INNER JOIN class c ON cs.class_id = c.class_id
                     WHERE cs.student_id = '$studentId'";
                    $studentClassesResult = mysqli_query($con, $fetchStudentClassesQuery);

                    $studentClasses = [];
                    while ($class = mysqli_fetch_assoc($studentClassesResult)) {
                        $studentClasses[] = $class['class_id'];
                    }

                    echo implode(",", $studentClasses);
                    echo "'>";
                    echo "<td>{$student['student_id']}</td>";
                    echo "<td>{$student['name']}</td>";
                    echo "<td>
                        <input type='checkbox' id='attendance' name='attendance' value='" . (isset($student['student_id']) ? $student['student_id'] : '') . "'
                        onclick='updateCheckboxTooltip()' " . ($checkboxDisabled ? 'disabled' : '') . "
                        title='" . ($checkboxDisabled ? 'Select a class first' : '') . "' />
                        </td>";
                    echo "<td data-classes='" . implode(",", $studentClasses) . "'>{$student['form']}</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <div id="record-table-container">
            <table id="record_table" style="display: none;">
                <thead>
                    <tr>
                        <th style="width: 350px;">Student</th>
                        <th style="width: 230px;">Class</th>
                        <th style="width: 40px;">Form</th>
                        <th style="width: 180px;">Time</th>
                        <th style="width: 90px;">Date</th>
                        <th style="width: 90px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch all attendance records from the database
                    $fetchAttendanceQuery = "SELECT a.attendance_id, CONCAT(c.class_id, ' | ', c.name) AS class_info, c.form, CONCAT(s.student_id, ' | ', s.name) AS student_info, a.time, a.date, a.status 
                            FROM attendance a
                            INNER JOIN class c ON a.class_id = c.class_id
                            INNER JOIN student s ON a.student_id = s.student_id";
                    $attendanceResult = mysqli_query($con, $fetchAttendanceQuery);

                    $rowNumber = 1;
                    while ($attendance = mysqli_fetch_assoc($attendanceResult)) {
                        echo "<tr>";
                        echo "<td>{$attendance['student_info']}</td>";
                        echo "<td>{$attendance['class_info']}</td>";
                        echo "<td data-classes='" . implode(",", $studentClasses) . "'>{$attendance['form']}</td>";
                        echo "<td>{$attendance['time']}</td>";
                        echo "<td>{$attendance['date']}</td>";
                        echo "<td>{$attendance['status']}</td>";
                        echo "</tr>";

                        $rowNumber++;
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>
    <button class="submit-btn" onclick="submitAttendance()">Submit</button>
    <div id="success-msg" class="success-msg"></i></div>
    <div id="failure-msg" class="failure-msg"></i></div>

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
</body>

</html>