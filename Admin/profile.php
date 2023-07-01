<?php
include('connection.php');

if (mysqli_connect_errno()) {
    echo mysqli_connect_error();
    exit();
} else {
    $admin_id = 3; // Assuming the admin's ID is 3, you can modify this as per your requirements

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Update admin information
        $email = $_POST['email'];
        $password = $_POST['password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        // Retrieve the current admin information
        $stmt = $con->prepare("SELECT * FROM admin WHERE admin_id = ?");
        $stmt->bind_param("i", $admin_id);
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

                    // Use prepared statements to update the admin's password
                    $stmt = $con->prepare("UPDATE admin SET email = ?, password = ? WHERE admin_id = ?");
                    $stmt->bind_param("ssi", $email, $hashedPassword, $admin_id);
                    $result = $stmt->execute();

                    if ($result) {
                        $msg = "Admin information updated successfully.";
                    } else {
                        $error = "Error updating admin information.";
                    }
                } else {
                    $error = "New password and confirmation password do not match.";
                }
            } else {
                $error = "Current password is incorrect.";
            }
        } else {
            $error = "Admin not found.";
        }
    }

    // Retrieve admin information
    $stmt = $con->prepare("SELECT * FROM admin WHERE admin_id = ?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        $error = "Admin not found.";
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
            <form action="" method="POST" class="form-container">
                <label for="email"><b>Email</b></label>
                <input type="email" placeholder="Enter your email" name="email" id="email" required value="<?php echo isset($row['email']) ? $row['email'] : ''; ?>">

                <label for="password"><b>Current Password</b></label>
                <input type="password" placeholder="Enter your current password" name="password" id="password" required>

                <label for="new_password"><b>New Password</b></label>
                <input type="password" placeholder="Enter your new password" name="new_password" id="new_password" required>

                <label for="confirm_password"><b>Confirm Password</b></label>
                <input type="password" placeholder="Confirm your new password" name="confirm_password" id="confirm_password" required>

                <button type="submit" class="submit-btn">Update</button>
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
