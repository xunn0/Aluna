<?php
    include('connection.php')
?>
<!DOCTYPE html>
<html>
<head>
  <title>Record Table</title>
  <style>
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
  </style>
</head>
<body>
  <h2>Record Table</h2>
  <table id="record_table">
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
      $fetchAttendanceQuery = "SELECT a.attendance_id, CONCAT(c.class_id, ' | ', c.name) AS class_info, c.form, CONCAT(s.student_id, ' | ', s.name) AS student_info, a.time, a.date, a.status 
                              FROM attendance a
                              INNER JOIN class c ON a.class_id = c.class_id
                              INNER JOIN student s ON a.student_id = s.student_id";
      $attendanceResult = mysqli_query($con, $fetchAttendanceQuery);

      while ($attendance = mysqli_fetch_assoc($attendanceResult)) {
        echo "<tr>";
        echo "<td>{$attendance['student_info']}</td>";
        echo "<td>{$attendance['class_info']}</td>";
        echo "<td>{$attendance['form']}</td>";
        echo "<td>{$attendance['time']}</td>";
        echo "<td>{$attendance['date']}</td>";
        echo "<td>{$attendance['status']}</td>";
        echo "</tr>";
      }
      ?>
    </tbody>
  </table>
</body>
</html>
