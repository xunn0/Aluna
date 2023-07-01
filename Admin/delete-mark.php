<?php
include('connection.php');

// Performing delete query execution
$sql = "DELETE FROM marks WHERE mark_id='" . $_GET["mark_id"] . "'";

if(mysqli_query($con, $sql)){
    echo '<script language="javascript">
            alert("Data has been deleted successfully.")
            window.location.replace("marks.php");
          </script>';
} else{
    echo "ERROR: Failed to delete data. " . mysqli_error($con);
}

// Close connection
mysqli_close($con);
?>
