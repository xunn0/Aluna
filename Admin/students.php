<?php
include('connection.php');

if (mysqli_connect_errno()) {
  echo mysqli_connect_error();
  exit();
} else {
  $selectQuery = "SELECT s.student_id, s.name, s.email, s.phone, s.gender, s.dob, IFNULL(c.form, 'None') AS form
  FROM student s
  LEFT JOIN class_student cs ON s.student_id = cs.student_id
  LEFT JOIN class c ON cs.class_id = c.class_id
  GROUP BY s.student_id
  ORDER BY s.student_id ASC";


  $result = mysqli_query($con, $selectQuery);
  if (mysqli_num_rows($result) > 0) {
  } else {
    $msg = "No Record found";
  }
}
?>

<head>
  <title>Aluna-Students</title>
  <link rel="stylesheet" type="text/css" href="../css/student.css">
  <link rel="stylesheet" type="text/css" href="../css/nav.css">
  <link rel="shortcut icon" type="image/x-icon" href="../img/favicon.ico" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
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
      <button class="add" id="btnOpenForm2">Add student</button>
      <button id="print-report" onclick="printTable()">Print Report</button>

    </div>
    <div class="table-container" style="width: 100%;">
      <table class="table table-striped table-class" id="table-id" style="width: 95%;">
        <thead>
          <tr>
            <th style="width: 90px;">Student ID</th>
            <th style="width: 90px;">Form</th>
            <th style="width: 450px;">Name</th>
            <th>Email</th>
            <th style="width: 150px;">Phone</th>
            <th style="width: 100px;">Gender</th>
            <th style="width: 100px;">Date of Birth</th>
            <th style="width: 90px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (mysqli_num_rows($result) == 0) {
            echo '<tr><td colspan="6">No Rows Returned</td></tr>';
          } else {
            $i = 0;
            while ($row = mysqli_fetch_assoc($result)) {
          ?>
              <tr>
                <td><?php echo $row["student_id"]; ?></td>
                <td><?php echo $row['form']; ?></td>
                <td><?php echo $row["name"]; ?></td>
                <td><?php echo $row["email"]; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['gender']; ?></td>
                <td><?php echo $row['dob']; ?></td>
                <td>
                  <a onclick="openUpdateForm(<?php echo $row['student_id']; ?>, '<?php echo $row['name']; ?>', '<?php echo $row['email']; ?>', '<?php echo $row['phone']; ?>', '<?php echo $row['gender']; ?>', '<?php echo $row['dob']; ?>')">
                    <i class="fa-regular fa-pen-to-square"></i>
                  </a>

                  <a href="delete-stud.php?id=<?php echo $row['student_id']; ?>" onclick="return confirmDelete()">
                    <i class="fa-solid fa-trash"></i>
                  </a>
                    <a href="#" class="report-icon" data-student-id="<?php echo $student['student_id']; ?>">
                        <i class="fas fa-chart-line"></i>
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
  <div class="form-popup" id="studentForm">
    <form action="insert-stud.php" method="POST" class="form-container">
      <h2>Add Student</h2>
      <label for="name"><b>Name</b></label>
      <input type="text" placeholder="Enter name" name="name" id="name" required>

      <label for="email"><b>Email</b></label>
      <input type="email" placeholder="Enter email" name="email" id="email" required>

      <label for="phone"><b>Phone</b></label>
      <input type="text" placeholder="Enter phone" name="phone" id="phone" required>

      <label for="gender"><b>Gender</b></label>
      <select name="gender" id="gender" required>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
      </select>

      <label for="dob"><b>Date of Birth</b></label>
      <input type="date" name="dob" id="dob" required>

      <div class="password-container">
        <label for="password"><b>Password</b></label>
        <div class="password-input-container">
          <input type="password" placeholder="Enter password" name="password" id="password" required>
          <span class="password-toggle" onclick="togglePasswordVisibility('password')">
            <i id="password-toggle-icon" class="fas fa-eye-slash"></i>
          </span>
        </div>
      </div>

      <button type="submit" class="submit-btn" onclick="submitForm()">Submit</button>
      <button type="button" class="cancel btn-shake" onclick="closeStudentForm()">Close</button>
    </form>
  </div>

  <!-- Update Form HTML -->
  <div class="form-popup" id="updateStudentForm">
    <form action="update-stud.php" method="POST" class="form-container">
      <h2>Update Student</h2>
      <input type="hidden" name="student_id" id="update_student_id">
      <label for="name"><b>Name</b></label>
      <input type="text" placeholder="Enter name" name="name" id="update_name" required>

      <label for="email"><b>Email</b></label>
      <input type="email" placeholder="Enter email" name="email" id="update_email" required>

      <label for="phone"><b>Phone</b></label>
      <input type="text" placeholder="Enter phone" name="phone" id="update_phone" required>

      <label for="gender"><b>Gender</b></label>
      <select name="gender" id="update_gender" required>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
      </select>

      <label for="dob"><b>Date of Birth</b></label>
      <input type="date" name="dob" id="update_dob" required>

      <div class="password-container">
        <label for="password"><b>Password</b></label>
        <div class="password-input-container">
          <input type="password" placeholder="Enter password" name="password" id="update_password" required>
          <span class="password-toggle" onclick="togglePasswordVisibility('update_password')">
            <i id="update_password-toggle-icon" class="fas fa-eye-slash"></i>
          </span>
        </div>
      </div>

      <button type="submit" class="submit-btn" onclick="updateForm()">Update</button>
      <button type="button" class="cancel btn-shake" onclick="closeUpdateStudentForm()">Close</button>
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

  <!-- Pagination script -->
  <script src="../JS/pagination.js"></script>
  <!-- student.js -->
  <script src="../JS/student.js"></script>
  <!-- dark-mode.js -->
  <script src="../JS/dark-mode.js"></script>
  <!-- Table.js -->
  <script src="../JS/Table.js"></script>
  <!-- nav.js -->
  <script src="../JS/nav.js"></script>
  <!-- shared.js file -->
  <script src="../JS/shared.js"></script>
  <!-- report-stud.js file -->
  <script src="../JS/report-stud.js"></script>

</body>