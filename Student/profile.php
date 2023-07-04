<?php
include('connection.php');

// Start a session
session_start();

// Retrieve the student ID from the session
$studentId = $_SESSION['student_id'];

// Retrieve student information
$stmt = $con->prepare("SELECT * FROM student WHERE student_id = ?");
$stmt->bind_param("i", $studentId); // Bind the parameter
$stmt->execute();
$result = $stmt->get_result();

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
} else {
    $error = "Student not found.";
}

if (mysqli_connect_errno()) {
    echo mysqli_connect_error();
    exit();
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['update_info'])) {
            // Update student information
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $gender = $_POST['gender'];
            $dob = $_POST['dob'];
            $picture = $_POST['picture'];

            // Use prepared statements to update the student's information
            $stmt = $con->prepare("UPDATE student SET name = ?, phone = ?, email = ?, gender = ?, dob = ?, picture = ? WHERE student_id = ?");
            $stmt->bind_param("ssssssi", $name, $phone, $email, $gender, $dob, $picture, $studentId); // Update variable names here
            $result = $stmt->execute();

            if ($result) {
                $msg = "Student information updated successfully.";
            } else {
                $error = "Error updating student information.";
            }
        } elseif (isset($_POST['update_password'])) {
            // Update student password
            $password = $_POST['password'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];

            // Retrieve the current student information
            $stmt = $con->prepare("SELECT * FROM student WHERE student_id = ?");
            $stmt->bind_param("i", $studentId); // Update variable name here
            $stmt->execute();
            $result = $stmt->get_result();

            if (mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $storedPassword = $row['password'];

                // Verify if the current password matches the stored password
                if (password_verify($password, $storedPassword)) {
                    // Verify if the new password and confirmation match
                    if ($newPassword === $confirmPassword) {
                        // Hash the new password
                        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                        // Use prepared statements to update the student's password
                        $stmt = $con->prepare("UPDATE student SET password = ? WHERE student_id = ?");
                        $stmt->bind_param("si", $hashedPassword, $studentId); // Update variable names here
                        $result = $stmt->execute();

                        if ($result) {
                            $msg = "Student password updated successfully.";
                        } else {
                            $error = "Error updating student password.";
                        }
                    } else {
                        $error = "New password and confirmation password do not match.";
                    }
                } else {
                    $error = "Current password is incorrect.";
                }
            } else {
                $error = "Student not found.";
            }
        }
    }

    // Retrieve student information
    $stmt = $con->prepare("SELECT * FROM student WHERE student_id = ?");
    $stmt->bind_param("i", $studentId); // Update variable name here
    $stmt->execute();
    $result = $stmt->get_result();

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        $error = "Student not found.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Aluna - Profile</title>
    <link rel="stylesheet" type="text/css" href="../css/nav.css">
    <link rel="stylesheet" type="text/css" href="../css/profile.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    <link rel="shortcut icon" type="image/x-icon" href="../img/favicon.ico" />
    <!-- MultiNav -->
    <script src="//code.jquery.com/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body>
    <!-- Nav -->
    <div id="nav-placeholder"></div>

    <div class="container">
        <div class="header_wrap"></div>

        <div class="card">
            <h2>Student Information</h2>
            <p><b>Name:</b> <?php echo $row['name']; ?></p>
            <p><b>Phone:</b> <?php echo $row['phone']; ?></p>
            <p><b>Email:</b> <?php echo $row['email']; ?></p>
            <p><b>Gender:</b> <?php echo $row['gender']; ?></p>
            <p><b>Date of Birth:</b> <?php echo $row['dob']; ?></p>
            <p><b>Picture:</b> <?php echo $row['picture']; ?></p>
            <?php if (!empty($row['picture'])) : ?>
                <img src="../img/<?php echo $row['picture']; ?>" alt="Student Picture">
            <?php else : ?>
                <p>No picture available.</p>
            <?php endif; ?>
        </div>

    <div class="card">
        <form action="" method="POST" class="form-container">
            <label for="name"><b>Name</b></label>
            <input type="text" placeholder="Enter your name" name="name" id="name" required value="<?php echo isset($row['name']) ? $row['name'] : ''; ?>">

            <label for="phone"><b>Phone</b></label>
            <input type="text" placeholder="Enter your phone number" name="phone" id="phone" required value="<?php echo isset($row['phone']) ? $row['phone'] : ''; ?>">

            <label for="email"><b>Email</b></label>
            <input type="email" placeholder="Enter your email" name="email" id="email" required value="<?php echo isset($row['email']) ? $row['email'] : ''; ?>">

            <label for="gender"><b>Gender</b></label>
            <select name="gender" id="gender" required>
                <option value="Male" <?php if (isset($row['gender']) && $row['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                <option value="Female" <?php if (isset($row['gender']) && $row['gender'] == 'Female') echo 'selected'; ?>>Female</option>
            </select>

            <label for="dob"><b>Date of Birth</b></label>
            <input type="date" placeholder="Enter your date of birth" name="dob" id="dob" required value="<?php echo isset($row['dob']) ? $row['dob'] : ''; ?>">

            <label for="picture"><b>Picture</b></label>
            <input type="file" name="picture" id="picture" required>

            <button type="submit" class="submit-btn" name="update_info">Update</button>
        </form>
    </div>

    <div class="card">
        <form action="" method="POST" class="form-container">

            <label for="email"><b>Email</b></label>
            <input type="email" placeholder="Enter your email" name="email" id="email" required value="<?php echo isset($row['email']) ? $row['email'] : ''; ?>">

            <label for="password"><b>Current Password</b></label>
            <input type="password" placeholder="Enter your current password" name="password" id="password" required>

            <label for="new_password"><b>New Password</b></label>
            <input type="password" placeholder="Enter your new password" name="new_password" id="new_password" required>

            <label for="confirm_password"><b>Confirm Password</b></label>
            <input type="password" placeholder="Confirm your new password" name="confirm_password" id="confirm_password" required>

            <button type="submit" class="submit-btn btm" name="update_password">Change Password</button>
        </form>
    </div>
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

    <!-- nav.js -->
    <script src="../JS/nav.js"></script>
    <!-- dark-mode.js -->
    <script src="../JS/dark-mode.js"></script>
    <!-- Eye.js -->
    <script src="../JS/Eye.js"></script>

    <script>
        <?php if (isset($msg)) { ?>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '<?php echo $msg; ?>',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'custom-swal-popup'
                }
            });
        <?php } ?>
        <?php if (isset($error)) { ?>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?php echo $error; ?>',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'custom-swal-popup'
                }
            });
        <?php } ?>
    </script>
</body>

</html>