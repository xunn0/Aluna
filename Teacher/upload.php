<?php
include('connection.php');
error_reporting(0);
 
$msg = "";
 
// If upload button is clicked for teacher picture...
if (isset($_POST['upload_teacher_picture'])) {
 
    $filename = $_FILES["teacher_picture"]["name"];
    $tempname = $_FILES["teacher_picture"]["tmp_name"];
    $folder = "./image/" . $filename;
 
    // Get all the submitted data from the form
    $sql = "UPDATE teacher SET picture = '$filename' WHERE teacher_id = ?"; // Replace ? with the appropriate teacher ID
 
    // Use prepared statements to update the teacher's picture
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $teacherId); // Update variable name here
    $result = $stmt->execute();
 
    // Now let's move the uploaded image into the folder: image
    if ($result && move_uploaded_file($tempname, $folder)) {
        echo "<h3>Image uploaded successfully!</h3>";
    } else {
        echo "<h3>Failed to upload image!</h3>";
    }
}
?>
