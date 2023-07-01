<!-- teacher.php -->
<?php include('connection.php');
if (mysqli_connect_errno()) {
  echo mysqli_connect_error();
  exit();
} else {
  $selectQuery = "SELECT * FROM teacher ORDER BY teacher_id ASC";
  $result = mysqli_query($con, $selectQuery);
  if (mysqli_num_rows($result) > 0) {
  } else {
    $msg = "No Record found";
  }
}
?>
<html>

<head>
  <title>Aluna-Teachers</title>
  <link rel="stylesheet" type="text/css" href="../css/teacher.css">
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
        <div class="form-group"> <!--		Show Numbers Of Rows 		-->
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
      <button class="add" id="btnOpenForm2">Add teacher</button>
      <button id="print-report" onclick="printTable()">Print Report</button>

    </div>
    <div class="table-container" style="width: 100%;">
      <table class="table table-striped table-class" id="table-id" style="width: 95%;">

        <thead>
          <tr>
            <th style="width: 90px;">Teacher ID</th>
            <th style="width: 500px;">Name</th>
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
            echo '<tr><td colspan="7">No Rows Returned</td></tr>';
          } else {
            $i = 0;
            while ($row = mysqli_fetch_assoc($result)) { ?>
              <tr>
                <td><?php echo $row["teacher_id"]; ?></td>
                <td><?php echo $row["name"]; ?></td>
                <td><?php echo $row["email"]; ?></td>
                <td><?php echo $row['phone']; ?></td>
                <td><?php echo $row['gender']; ?></td>
                <td><?php echo $row['dob']; ?></td>
                <td>
                  <a onclick="openUpdateForm(<?php echo $row['teacher_id']; ?>, '<?php echo $row['name']; ?>', '<?php echo $row['email']; ?>', '<?php echo $row['phone']; ?>', '<?php echo $row['gender']; ?>', '<?php echo $row['dob']; ?>')">
                    <i class="fa-regular fa-pen-to-square"></i>
                  </a>
                  <a href="delete.php?teacher_id=<?php echo $row['teacher_id']; ?>" onclick="return confirmDelete()"><i class='fa-solid fa-trash'></i></a>
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
    <!--		Start Pagination -->
    <div class='pagination-container'>
      <nav>
        <ul class="pagination">
          <!--	Here the JS Function Will Add the Rows -->
        </ul>
      </nav>
    </div>
    <div class="rows_count">Showing 11 to 20 of 91 entries</div>
  </div>

  <!-- Insert Form HTML -->
  <div class="form-popup" id="teacherForm">
    <form action="insert.php" method="POST" class="form-container">
      <h2>Add Teacher</h2>

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

      <button type="submit" class="submit-btn">Submit</button>
      <button type="button" class="cancel btn-shake" onclick="closeTeacherForm()">Close</button>
    </form>
  </div>

  <!-- Update Form HTML -->
  <div class="form-popup" id="updateTeacherForm">
    <form action="update.php" method="POST" class="form-container">
      <h2>Update Teacher</h2>

      <input type="hidden" name="teacher_id" id="update_teacher_id">

      <label for="update_name"><b>Name</b></label>
      <input type="text" placeholder="Enter name" name="name" id="update_name" required>

      <label for="update_email"><b>Email</b></label>
      <input type="email" placeholder="Enter email" name="email" id="update_email" required>

      <label for="update_phone"><b>Phone</b></label>
      <input type="text" placeholder="Enter phone" name="phone" id="update_phone" required>

      <label for="update_gender"><b>Gender</b></label>
      <select name="gender" id="update_gender" required>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
      </select>

      <label for="update_dob"><b>Date of Birth</b></label>
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

      <button type="submit" class="submit-btn" onclick="submitForm()">Update</button>
      <button type="button" class="cancel btn-shake" onclick="closeUpdateTeacherForm()">Close</button>
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

  <!-- dark-mode.js -->
  <script src="../JS/dark-mode.js"></script>
  <!-- Table.js -->
  <script src="../JS/Table.js"></script>
  <!-- nav.js -->
  <script src="../JS/nav.js"></script>
  <!-- teacher.js -->
  <script src="../JS/teacher.js"></script>
  <!-- shared.js file -->
  <script src="../JS/shared.js"></script>
  <!-- report.js file -->
  <script src="../JS/report.js"></script>

</body>

</html>