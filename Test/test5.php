<div class="container">
    <button class="add" id="btnOpenForm2">Add Class</button>
    <button class="assign-btn" onclick="openAssignForm()">Assign Student</button>
    <div class="table-container">
      <table class="table table-striped table-class" id="table-id" style="width: 70%;">
        <thead>
          <tr>
            <th style="width: 90px;">Class ID</th>
            <th>Class Name</th>
            <th style="width: 90px;">Teacher ID</th>
            <th style="width: 200px;">Students</th>
            <th style="width: 90px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (mysqli_num_rows($result) == 0) {
            echo '<tr><td colspan="5">No Rows Returned</td></tr>';
          } else {
            $i = 0;
            while ($row = mysqli_fetch_assoc($result)) {
              $classId = $row["class_id"];
              $className = $row["name"];
              $teacherId = $row["teacher_id"];

              // Retrieve the list of students assigned to the class
              $studentQuery = mysqli_query($con, "SELECT student.student_id, student.name FROM class_student
              INNER JOIN student ON class_student.student_id = student.student_id
              WHERE class_student.class_id = '$classId'");
              $assignedStudents = mysqli_fetch_all($studentQuery, MYSQLI_ASSOC);

              echo '<tr>';
              echo '<td>' . $classId . '</td>';
              echo '<td>' . $className . '</td>';
              echo '<td>' . $teacherId . '</td>';
              echo '<td>';
              echo '<form action="remove-student.php" method="POST">';
              echo '<input type="hidden" name="classId" value="' . $classId . '">';
              echo '<select name="studentId">';
              foreach ($assignedStudents as $student) {
                echo '<option value="' . $student['student_id'] . '">' . $student['name'] . ' | ID: ' . $student['student_id'] . '</option>';
              }
              echo '</select>';
              echo '<button type="submit"><i class="fa-regular fa-trash-alt"></i></button>';
              echo '</form>';
              echo '</td>';
              echo '<td>';
              echo '<a onclick="openUpdateForm(' . $classId . ', \'' . $className . '\', \'' . $teacherId . '\')"><i class="fa-regular fa-pen-to-square"></i></a>';
              echo '<a href="delete-class.php?class_id=' . $classId . '"><i class="fa-regular fa-trash-alt"></i></a>';
              echo '</td>';
              echo '</tr>';

              $i++;
            }
          }
          ?>
        </tbody>
      </table>
    </div>