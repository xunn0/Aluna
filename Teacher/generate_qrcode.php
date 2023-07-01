<?php
include('connection.php'); // Include the database connection file
include('phpqrcode/qrlib.php');

// Get the class ID, time, and date from the AJAX request
$classId = $_POST['class_id'];
$time = $_POST['attendance_time'];
$date = $_POST['attendance_date'];

// Generate a unique filename for the QR code image
$filename = uniqid('qr_', true) . '.png';

// Path to store the generated QR code image
$filepath = 'qrcodes/' . $filename;

// Generate the QR code content (e.g., class ID, time, date)
$content = $classId . '|' . $time . '|' . $date;

try {
    // Generate the QR code and save it as an image file
    QRcode::png($content, $filepath, 'L', 8, 2);

    // Send the filepath back as the response
    echo $filepath;
} catch (Exception $e) {
    // Log the error
    error_log('QR code generation failed: ' . $e->getMessage());

    // Return an error response
    http_response_code(500);
    echo 'QR code generation failed. Please try again.';
}
?>
