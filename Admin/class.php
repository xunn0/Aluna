<?php
include('connection.php');

if (mysqli_connect_errno()) {
  echo mysqli_connect_error();
  exit();
} else {
  $selectQuery = "SELECT * FROM class ORDER BY class_id ASC";
  $result = mysqli_query($con, $selectQuery);
  if (mysqli_num_rows($result) > 0) {
  } else {
    $msg = "No Records found";
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Class List</title>
  <link rel="stylesheet" type="text/css" href="../css/class.css">
  <link rel="stylesheet" type="text/css" href="../css/nav.css">
  <link rel="shortcut icon" type="image/x-icon" href="../img/favicon.ico" />
  <!-- MultiNav -->
  <script src="//code.jquery.com/jquery.min.js"></script>
</head>

<body>
  <!-- Nav -->
  <div id="nav-placeholder"></div>

  <div class="container">
    <!--<div class="header_wrap">
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
      </div> -->
    <!--<div class="tb_search">
        <input type="text" id="search_input_all" onkeyup="FilterkeyWord_all_table()" placeholder="Search.." class="form-control">
      </div>
    </div>-->
    <button class="add" id="btnOpenForm2">Add Class</button>
    <button class="assign-btn" onclick="openAssignForm()">Assign Student</button>
    <a href="report.php" id="print-report" target="_blank">Print Report</a>

    <div class="table-container">
      <table class="table table-striped table-class" id="table-id" style="width: 65%;">
        <thead>
          <tr>
            <th style="width: 90px;">Class ID</th>
            <th style="width: 250px;">Name</th>
            <th style="width: 90px;">Form</th>
            <th style="width: 90px;">Teacher ID</th>
            <th style="width: 90px;">Day</th>
            <th style="width: 90px;">Time Slot</th>
            <th style="width: 200px;">Students</th>
            <th style="width: 90px;">Actions</th>
          </tr>
        </thead>
        <tbody>

          <?php
          if (mysqli_num_rows($result) == 0) {
            echo '<tr><td colspan="4">No Rows Returned</td></tr>';
          } else {
            $i = 0;
            while ($row = mysqli_fetch_assoc($result)) {
              $classId = $row["class_id"];
              $className = $row["name"];
              $teacherId = $row["teacher_id"];
              $day = $row["day"];
              $timeSlot = $row["time_slot"];

              // Retrieve the list of students assigned to the class
              $studentQuery = mysqli_query($con, "SELECT student.student_id, student.name FROM class_student
               INNER JOIN student ON class_student.student_id = student.student_id
               WHERE class_student.class_id = '$classId'");
              $assignedStudents = mysqli_fetch_all($studentQuery, MYSQLI_ASSOC);
            ?>
            
              <tr>
                <td><?php echo $row["class_id"]; ?></td>
                <td><?php echo $row["name"]; ?></td>
                <td><?php echo $row["form"]; ?></td>
                <td><?php echo $row["teacher_id"]; ?></td>
                <td><?php echo $row["day"]; ?></td>
                <td><?php echo $row["time_slot"]; ?></td>
                <td><?php
                    echo '<form action="remove-student.php" method="POST">';
                    echo '<input type="hidden" name="classId" value="' . $classId . '">';
                    echo '<select class="student-select" name="studentId">';
                    foreach ($assignedStudents as $student) {
                      echo '<option value="' . $student['student_id'] . '">' . $student['name'] . ' | ID: ' . $student['student_id'] . '</option>';
                    }
                    echo '</select>';
                    echo '<button type="submit"><i class="fa-regular fa-trash-alt"></i></button>';
                    echo '</form>';
                    ?></td>

                <td>
                  <a onclick="openUpdateForm(<?php echo $row['class_id']; ?>, '<?php echo $row['name']; ?>', '<?php echo $row['teacher_id']; ?>')">
                    <i class="fa-regular fa-pen-to-square"></i>
                  </a>
                  <a href="delete-class.php?class_id=<?php echo $row['class_id']; ?>" onclick="return confirmDelete()">
                    <i class="fa-solid fa-trash"></i>
                  </a>
                </td>
              </tr>
          <?php
              $i++;
            }
          }
          ?>
        </tbody>
      </table>
    </div>

    <!-- Insert Form -->
    <div class="form-popup" id="classForm">
      <form action="insert-class.php" method="POST" class="form-container">
        <h2>Add Class</h2>

        <label for="insert_class_name">Class Name</label>
        <input type="text" placeholder="Enter class name" name="name" id="insert_class_name" required>

        <label for="insert_form">Form</label>
        <select name="form" id="insert_form" required>
          <option value="">Select a form</option>
          <?php
          $formQuery = "SELECT DISTINCT form FROM class";
          $formResult = mysqli_query($con, $formQuery);

          if (mysqli_num_rows($formResult) > 0) {
            while ($formRow = mysqli_fetch_assoc($formResult)) {
              echo '<option value="' . $formRow['form'] . '">' . $formRow['form'] . '</option>';
            }
          } else {
            echo '<option value="">No forms found</option>';
          }
          ?>
        </select>

        <label for="insert_day">Day</label>
        <select name="day" id="insert_day" required>
          <option value="">Select a day</option>
          <option value="Sunday">Sunday</option>
          <option value="Monday">Monday</option>
          <option value="Tuesday">Tuesday</option>
          <option value="Wednesday">Wednesday</option>
          <option value="Thursday">Thursday</option>
          <option value="Friday">Friday</option>
          <option value="Saturday">Saturday</option>
        </select>

          <label for="insert_time_slot">Time Slot</label>
          <select name="time_slot" id="insert_time_slot" required>
            <option value="">Select a time slot</option>
            <option value="08:30 AM - 10:30 AM">08:30 AM - 10:30 AM</option>
            <option value="10:30 AM - 12:30 PM">10:30 AM - 12:30 PM</option>
            <option value="02:30 PM - 04:30 PM">02:30 PM - 04:30 PM</option>
            <option value="04:30 PM - 06:30 PM">04:30 PM - 06:30 PM</option>
            <option value="07:30 PM - 09:30 PM">07:30 PM - 09:30 PM</option>
            <option value="09:30 PM - 11:30 PM">09:30 PM - 11:30 PM</option>
          </select>

          <label for="insert_teacher_id"><b>Teacher</b></label>
          <input type="text" id="insert_teacher_search" oninput="filterTeachers('insert')" placeholder="Search Teacher">

          <select name="teacher_id" id="insert_teacher_id" required>
            <option value="">Select a teacher</option>
            <?php
            $teacherQuery = "SELECT * FROM teacher";
            $teacherResult = mysqli_query($con, $teacherQuery);

            if (mysqli_num_rows($teacherResult) > 0) {
              while ($teacherRow = mysqli_fetch_assoc($teacherResult)) {
                echo '<option value="' . $teacherRow['teacher_id'] . '">' . $teacherRow['teacher_id'] . ' - ' . $teacherRow['name'] . '</option>';
              }
            } else {
              echo '<option value="">No teachers found</option>';
            }
            ?>
          </select>

          <button type="submit" class="submit-btn" onclick="submitForm()">Submit</button>
          <button type="button" class="cancel btn-shake" onclick="closeClassForm()">Close</button>
      </form>
    </div>

    <!-- Update Form -->
    <div class="form-popup" id="updateClassForm">
      <form action="update-class.php" method="POST" class="form-container">
        <h2>Update Class</h2>
        <input type="hidden" id="update_class_id" name="class_id">

        <label for="update_class_name">Class Name</label>
        <input type="text" placeholder="Enter class name" id="update_class_name" name="name" required>

        <label for="update_form">Form</label>
        <select name="form" id="update_form" required>
          <option value="">Select a form</option>
          <?php
          $formQuery = "SELECT DISTINCT form FROM class";
          $formResult = mysqli_query($con, $formQuery);

          if (mysqli_num_rows($formResult) > 0) {
            while ($formRow = mysqli_fetch_assoc($formResult)) {
              echo '<option value="' . $formRow['form'] . '">' . $formRow['form'] . '</option>';
            }
          } else {
            echo '<option value="">No forms found</option>';
          }
          ?>
        </select>

        <label for="update_day">Day</label>
        <select name="day" id="update_day" required>
          <option value="">Select a day</option>
          <option value="Sunday">Sunday</option>
          <option value="Monday">Monday</option>
          <option value="Tuesday">Tuesday</option>
          <option value="Wednesday">Wednesday</option>
          <option value="Thursday">Thursday</option>
          <option value="Friday">Friday</option>
          <option value="Saturday">Saturday</option>
        </select>

          <label for="update_time_slot">Time Slot</label>
          <select name="time_slot" id="update_time_slot" required>
            <option value="">Select a time slot</option>
            <option value="08:30 AM - 10:30 AM">08:30 AM - 10:30 AM</option>
            <option value="10:30 AM - 12:30 PM">10:30 AM - 12:30 PM</option>
            <option value="02:30 PM - 04:30 PM">02:30 PM - 04:30 PM</option>
            <option value="04:30 PM - 06:30 PM">04:30 PM - 06:30 PM</option>
            <option value="07:30 PM - 09:30 PM">07:30 PM - 09:30 PM</option>
            <option value="09:30 PM - 11:30 PM">09:30 PM - 11:30 PM</option>
          </select>

        <label for="update_teacher_id"><b>Teacher</b></label>
        <input type="text" id="update_teacher_search" oninput="filterTeachers('update')" placeholder="Search Teacher">

        <select name="teacher_id" id="update_teacher_id" required>
          <option value="">Select a teacher</option>
          <?php
          $teacherQuery = "SELECT * FROM teacher";
          $teacherResult = mysqli_query($con, $teacherQuery);

          if (mysqli_num_rows($teacherResult) > 0) {
            while ($teacherRow = mysqli_fetch_assoc($teacherResult)) {
              echo '<option value="' . $teacherRow['teacher_id'] . '">' . $teacherRow['teacher_id'] . ' - ' . $teacherRow['name'] . '</option>';
            }
          } else {
            echo '<option value="">No teachers found</option>';
          }
          ?>
        </select>

        <button type="submit" class="submit-btn" onclick="submitForm()">Update</button>
        <button type="button" class="cancel btn-shake" onclick="closeUpdateForm()">Close</button>
      </form>
    </div>

    <!-- Assign Form -->
    <div class="form-popup" id="assignForm">
      <form action="assign-stud.php" class="form-container" method="POST">
        <h2>Assign Student</h2>
        <!-- Form fields for assigning students to classes -->
        <label for="classSelect">Select Class:</label>
        <select id="classSelect" name="class_id">
          <!-- Add options for class selection -->
          <?php
          include('connection.php');

          // Retrieve the list of classes from the database
          $classQuery = mysqli_query($con, "SELECT * FROM class");
          while ($classRow = mysqli_fetch_assoc($classQuery)) {
            $classId = $classRow['class_id'];
            $className = $classRow['name'];
            $classForm = $classRow['form'];
            echo "<option value='$classId'>$className | Form $classForm</option>";
          }

          mysqli_close($con);
          ?>
        </select>

        <label for="studentSelect">Select Student:</label>
        <select id="studentSelect" name="student_id">
          <!-- Add options for student selection -->
          <?php
          include('connection.php');

          // Retrieve the list of students from the database
          $studentQuery = mysqli_query($con, "SELECT * FROM student");
          while ($studentRow = mysqli_fetch_assoc($studentQuery)) {
            $studentId = $studentRow['student_id'];
            $studentName = $studentRow['name'];
            echo "<option value='$studentId'>$studentName | ID: $studentId</option>";
          }

          mysqli_close($con);
          ?>
        </select>

        <button type="submit" class="submit-btn">Assign</button>
        <button type="button" class="cancel btn-shake" onclick="closeAssignForm()">Cancel</button>
      </form>
    </div>

    <div id="report" class="report">
      <!-- Class and student data goes here -->
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
    <script src="../JS/class.js"></script>
    <!-- dark-mode.js -->
    <script src="../JS/dark-mode.js"></script>
    <!-- Table.js -->
    <script src="../JS/Table.js"></script>
    <!-- nav.js -->
    <script src="../JS/nav.js"></script>
    <!-- shared.js file -->
    <script src="../JS/shared.js"></script>


</body>

</html>