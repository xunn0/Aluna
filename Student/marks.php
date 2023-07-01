<?php
session_start();
include('connection.php');

// Check if the student ID is stored in the session
if (isset($_SESSION['student_id'])) {
  // Retrieve the student ID
  $currentStudentID = $_SESSION['student_id'];
  // Use the $currentStudentID variable in your code
} else {
  // Student ID is not available in the session, handle the situation accordingly
}

if (mysqli_connect_errno()) {
  echo mysqli_connect_error();
  exit();
} else {
  $selectQuery = "SELECT marks.*, class.name AS class_name, class.form, teacher.name FROM marks
                  INNER JOIN class ON marks.class_id = class.class_id
                  INNER JOIN teacher ON marks.teacher_id = teacher.teacher_id
                  WHERE marks.student_id = '$currentStudentID'
                  ORDER BY marks.mark_id ASC";
                  
  $result = mysqli_query($con, $selectQuery); // Execute the query and assign the result

  if (!$result) {
    echo mysqli_error($con); // Display any query errors
    exit();
  }

  if (mysqli_num_rows($result) > 0) {
    // Rest of your code...
  } else {
    $msg = "No Record found";
  }
}
// Function to calculate the grade based on marks obtained
function getGrade($marks)
{
  if ($marks >= 90) {
    return "A+";
  } elseif ($marks >= 80) {
    return "A";
  } elseif ($marks >= 70) {
    return "B+";
  } elseif ($marks >= 60) {
    return "B";
  } elseif ($marks >= 50) {
    return "C+";
  } elseif ($marks >= 40) {
    return "C";
  } else {
    return "F";
  }
}
?>

<head>
  <title>Aluna-Marks</title>
  <link rel="stylesheet" type="text/css" href="../css/mark.css">
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
      <button id="print-report" class="print3" onclick="printTable()">Print Report</button>

    </div>
    <div class="table-container" style="width: 100%;">
      <table class="table table-striped table-class" id="table-id" style="width: 80%;">
        <thead>
          <tr>
            <th style="width: 90px;">Mark ID</th>
            <th style="width: 300px;">Test Name</th>
            <th>Marks Obtained</th>
            <th>Student ID</th>
            <th>Class ID</th>
            <th>Teacher ID</th>
            <th>Form</th>
            <th>Grade</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (mysqli_num_rows($result) == 0) {
            echo '<tr><td colspan="9">No Rows Returned</td></tr>';
          } else {
            $i = 0;
            while ($row = mysqli_fetch_assoc($result)) {
          ?>
              <tr>
                <td><?php echo $row["mark_id"]; ?></td>
                <td><?php echo $row["test_name"]; ?></td>
                <td><?php echo intval($row['marks_obtained']) . '%'; ?></td>
                <td><?php echo $row['student_id']; ?></td>
                <td><?php echo $row['class_id']; ?></td>
                <td><?php echo $row['teacher_id']; ?></td>
                <td><?php echo $row['form']; ?></td>
                <td><?php echo getGrade($row['marks_obtained']); ?></td> <!-- Display calculated grade -->
              </tr>
          <?php
              $i++;
            }
          }
          ?>
        </tbody>
      </table>
    </div>
    <div class='pagination-container'>
      <nav>
        <ul class="pagination">
          <!-- Here the JS Function Will Add the Rows -->
        </ul>
      </nav>
    </div>
    <div class="rows_count">Showing 11 to 20 of 91 entries</div>
  </div>

  <!-- Insert Form HTML -->
  <div class="form-popup" id="marksForm">
    <form action="insert-mark.php" method="POST" class="form-container">
      <h2>Add Marks</h2>
      <label for="insert_test_name"><b>Test Name</b></label>
      <input type="text" placeholder="Enter test name" name="test_name" id="insert_test_name" required>

      <label for="insert_marks_obtained"><b>Marks Obtained</b></label>
      <input type="number" placeholder="Enter marks obtained" name="marks_obtained" id="insert_marks_obtained" required oninput="calculateAndUpdateGrade('insert')">

      <label for="insert_class_id"><b>Class</b></label>
      <select name="class_id" id="insert_class_id" required onchange="updateTestName('insert'); updateTeacherID('insert')" data-selected-class-id="">
        <option value="">Select a class</option>
        <?php
        $classQuery = "SELECT * FROM class";
        $classResult = mysqli_query($con, $classQuery);

        if (mysqli_num_rows($classResult) > 0) {
          while ($classRow = mysqli_fetch_assoc($classResult)) {
            $formIndicator = ($classRow['form'] != '') ? ' | Form ' . $classRow['form'] : '';
            echo '<option value="' . $classRow['class_id'] . '">' . $classRow['name'] . $formIndicator . '</option>';
          }
        } else {
          echo '<option value="">No classes found</option>';
        }
        ?>
      </select>

      <label for="insert_teacher_id"><b>Teacher</b></label>
      <select name="teacher_id" id="insert_teacher_id" required>
        <option value="">Select a teacher</option>
        <?php
        $teacherQuery = "SELECT * FROM teacher";
        $teacherResult = mysqli_query($con, $teacherQuery);

        if (mysqli_num_rows($teacherResult) > 0) {
          while ($teacherRow = mysqli_fetch_assoc($teacherResult)) {
            echo '<option value="' . $teacherRow['teacher_id'] . '">' . $teacherRow['name'] . ' | ID: ' . $teacherRow['teacher_id'] . '</option>';
          }
        } else {
          echo '<option value="">No teachers found</option>';
        }
        ?>
      </select>

      <label for="insert_student_id"><b>Student</b></label>
      <select name="student_id" id="insert_student_id" required>
        <option value="">Select a student</option>
        <?php
        $studentQuery = "SELECT * FROM student";
        $studentResult = mysqli_query($con, $studentQuery);

        if (mysqli_num_rows($studentResult) > 0) {
          while ($studentRow = mysqli_fetch_assoc($studentResult)) {
            echo '<option value="' . $studentRow['student_id'] . '">' . $studentRow['name'] . ' | ID: ' . $studentRow['student_id'] . '</option>';
          }
        } else {
          echo '<option value="">No students found</option>';
        }
        ?>
      </select>

      <label for="insert_grade"><b>Grade</b></label>
      <input type="text" placeholder="Grade" name="grade" id="insert_grade" readonly>

      <button type="submit" class="submit-btn" onclick="submitForm()">Submit</button>
      <button type="button" class="cancel btn-shake" onclick="closeMarksForm()">Close</button>
    </form>
  </div>

  <!-- Update Form HTML -->
  <div class="form-popup" id="updateMarksForm">
    <form action="update-mark.php" method="POST" class="form-container">
      <h2>Update Marks</h2>
      <input type="hidden" name="mark_id" id="update_mark_id">

      <label for="update_test_name"><b>Test Name</b></label>
      <input type="text" placeholder="Enter test name" name="test_name" id="update_test_name" required>

      <label for="update_marks_obtained"><b>Marks Obtained</b></label>
      <input type="number" placeholder="Enter marks obtained" name="marks_obtained" id="update_marks_obtained" required oninput="calculateAndUpdateGrade('update')">

      <label for="update_class_id"><b>Class</b></label>
      <select name="class_id" id="update_class_id" required onchange="updateTestName('update'); updateTeacherID('update')" data-selected-class-id="">
        <option value="">Select a class</option>
        <?php
        $classQuery = "SELECT * FROM class";
        $classResult = mysqli_query($con, $classQuery);

        if (mysqli_num_rows($classResult) > 0) {
          while ($classRow = mysqli_fetch_assoc($classResult)) {
            $formIndicator = ($classRow['form'] != '') ? ' | Form ' . $classRow['form'] : '';
            echo '<option value="' . $classRow['class_id'] . '">' . $classRow['name'] . $formIndicator . '</option>';
          }
        } else {
          echo '<option value="">No classes found</option>';
        }
        ?>
      </select>

      <label for="update_teacher_id"><b>Teacher</b></label>
      <select name="teacher_id" id="update_teacher_id" required>
        <option value="">Select a teacher</option>
        <?php
        $teacherQuery = "SELECT * FROM teacher";
        $teacherResult = mysqli_query($con, $teacherQuery);

        if (mysqli_num_rows($teacherResult) > 0) {
          while ($teacherRow = mysqli_fetch_assoc($teacherResult)) {
            echo '<option value="' . $teacherRow['teacher_id'] . '">' . $teacherRow['name'] . ' | ID: ' . $teacherRow['teacher_id'] . '</option>';
          }
        } else {
          echo '<option value="">No teachers found</option>';
        }
        ?>
      </select>

      <label for="update_student_id"><b>Student</b></label>
      <select name="student_id" id="update_student_id" required>
        <option value="">Select a student</option>
        <?php
        $studentQuery = "SELECT * FROM student";
        $studentResult = mysqli_query($con, $studentQuery);

        if (mysqli_num_rows($studentResult) > 0) {
          while ($studentRow = mysqli_fetch_assoc($studentResult)) {
            echo '<option value="' . $studentRow['student_id'] . '">' . $studentRow['name'] . ' | ID: ' . $studentRow['student_id'] . '</option>';
          }
        } else {
          echo '<option value="">No students found</option>';
        }
        ?>
      </select>

      <label for="update_grade"><b>Grade</b></label>
      <input type="text" placeholder="Grade" name="grade" id="update_grade" readonly>

      <button type="submit" class="submit-btn">Update</button>
      <button type="button" class="cancel btn-shake" onclick="closeUpdateForm()">Close</button>
    </form>
  </div>

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

  <!-- mark.js -->
  <script src="../js/mark.js"></script>
  <!-- dark-mode.js -->
  <script src="../JS/dark-mode.js"></script>
  <!-- Table.js -->
  <script src="../JS/Table.js"></script>
  <!-- nav.js -->
  <script src="../JS/nav.js"></script>
  <!-- grade.js -->
  <script src="../JS/grade.js"></script>
  <!-- shared.js file -->
  <script src="../JS/shared.js"></script>
  <!-- report-mark.js file -->
  <script src="../JS/report-mark.js"></script>

</body>

</html>