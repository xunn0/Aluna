<?php
include('connection.php');

if (mysqli_connect_errno()) {
  echo mysqli_connect_error();
  exit();
} else {
  $selectQuery = "SELECT class.class_id, class.name AS class_name, class.form, class.teacher_id, teacher.name AS teacher_name, GROUP_CONCAT(student.name SEPARATOR ', ') AS student_names, class.day, class.time_slot
                  FROM class
                  INNER JOIN teacher ON class.teacher_id = teacher.teacher_id
                  LEFT JOIN class_student ON class.class_id = class_student.class_id
                  LEFT JOIN student ON class_student.student_id = student.student_id
                  GROUP BY class.class_id
                  ORDER BY class.class_id ASC";
  $result = mysqli_query($con, $selectQuery);
  if (!$result) {
    echo "Query Error: " . mysqli_error($con);
    exit();
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Class Report</title>
  <style>
    table {
      border-collapse: collapse;
      margin: 0 auto;
    }

    th,
    td {
      border: 1px solid black;
      padding: 8px;
      text-align: center;
    }

    th {
      background-color: #f2f2f2;
    }
  </style>
</head>
<body>
  <h1>Class Report</h1>
  <table>
    <tr>
      <th>Class ID</th>
      <th>Class Name</th>
      <th>Form</th>
      <th>Teacher ID</th>
      <th>Teacher Name</th>
      <th>Students</th>
      <th>Day</th>
      <th>Timeslot</th>
    </tr>
    <?php
    if (mysqli_num_rows($result) == 0) {
      echo '<tr><td colspan="8">No Rows Returned</td></tr>';
    } else {
      while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . $row['class_id'] . '</td>';
        echo '<td>' . $row['class_name'] . '</td>';
        echo '<td>' . $row['form'] . '</td>';
        echo '<td>' . $row['teacher_id'] . '</td>';
        echo '<td>' . $row['teacher_name'] . '</td>';
        echo '<td>' . $row['student_names'] . '</td>';
        echo '<td>' . $row['day'] . '</td>';
        echo '<td>' . $row['time_slot'] . '</td>';
        echo '</tr>';
      }
    }
    ?>
  </table>

  <script>
    window.onload = function() {
      window.print();
    };
  </script>
</body>
</html>
