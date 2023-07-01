<?php
include('connection.php');
// Taking all 7 values from the form data(input)
         
        // Performing insert query execution
        $sql = "DELETE FROM teacher WHERE teacher_id='" . $_GET["teacher_id"] . "'";
         
        if(mysqli_query($con, $sql)){
            echo '<script language="javascript">
                    alert("Data has been deleted successfully.")
                    window.location.replace("teachers.php");
                  </script>';
            
        } else{
            echo "ERROR: Hush! Sorry $sql. "
                . mysqli_error($con);
        }

        // Close connection
        mysqli_close($con);