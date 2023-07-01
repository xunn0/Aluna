<?php
include('connection.php');

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $student_id = $_GET['id'];

    // Performing delete query execution
    $sql = "DELETE FROM student WHERE student_id = '$student_id'";

    if (mysqli_query($con, $sql)) {
        echo '<script language="javascript">
                alert("Data has been deleted successfully.")
                window.location.replace("students.php");
              </script>';
    } else {
        echo "ERROR: Failed to delete the data. " . mysqli_error($con);
    }
} else {
    echo "ERROR: Invalid student ID parameter.";
}

// Close connection
mysqli_close($con);
?>
