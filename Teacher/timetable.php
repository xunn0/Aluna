<?php
// Include the database connection file
include('connection.php');

// Start the session
session_start();

// Retrieve the current teacher's ID from the session
$teacherId = $_SESSION['teacher_id'];

// Retrieve teacher information
$stmt = $con->prepare("SELECT * FROM teacher WHERE teacher_id = ?");
$stmt->bind_param("i", $teacherId); // Bind the parameter
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $teacherName = $row['name'];
} else {
    $error = "Teacher not found.";
}

// Retrieve the classes the current user is teaching
$classesStmt = $con->prepare("SELECT * FROM class WHERE teacher_id = ?");
$classesStmt->bind_param("i", $teacherId);
$classesStmt->execute();
$classesResult = $classesStmt->get_result();

// Create an array to store the class names
$classNames = array();

// Populate the class names array with the retrieved class data
while ($classRow = $classesResult->fetch_assoc()) {
    // Create a key for the class using the combination of day and time_slot
    $key = $classRow['day'] . '_' . $classRow['time_slot'];
    $classNames[$key] = $classRow['name'];
}

// Function to find the class name for the given day and time slot
function findClass($day, $timeSlot)
{
    global $classNames;

    // Create a key using the combination of day and time_slot
    $key = $day . '_' . $timeSlot;

    // Check if the key exists in the classNames array
    if (isset($classNames[$key])) {
        return $classNames[$key];
    }

    return "";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Aluna - Timetable</title>
    <link rel="stylesheet" type="text/css" href="../css/nav.css">
    <link rel="stylesheet" type="text/css" href="../css/timetable.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    <link rel="shortcut icon" type="image/x-icon" href="../img/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- MultiNav -->
    <script src="//code.jquery.com/jquery.min.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body>
    <!-- Nav -->
    <div id="nav-placeholder"></div>

    <h3 id="teach">Timetable for <?php echo $teacherName; ?></h3>

    <!-- Content -->
    <div class="container">
        <table class="timetable">
            <thead>
                <tr>
                    <th style="width: 180px;"></th>
                    <th style="width: 180px;">Sunday</th>
                    <th style="width: 180px;">Monday</th>
                    <th style="width: 180px;">Tuesday</th>
                    <th style="width: 180px;">Wednesday</th>
                    <th style="width: 180px;">Thursday</th>
                    <th style="width: 180px;">Friday</th>
                    <th style="width: 180px;">Saturday</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Define the time slots
                $timeSlots = array(
                    '08:30 AM - 10:30 AM',
                    '10:30 AM - 12:30 PM',
                    '02:30 PM - 04:30 PM',
                    '04:30 PM - 06:30 PM',
                    '07:30 PM - 09:30 PM',
                    '09:30 PM - 11:30 PM'
                );

                // Define the days of the week
                $daysOfWeek = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');

                // Loop through the time slots and generate rows
                foreach ($timeSlots as $timeSlot) {
                    echo "<tr>";
                    echo "<td>$timeSlot</td>";

                    // Loop through the days of the week
                    foreach ($daysOfWeek as $day) {
                        echo "<td>";
                        // Find the class name for the current day and time slot
                        $class = findClass($day, $timeSlot);
                        if ($class) {
                            echo $class;
                        }
                        echo "</td>";
                    }

                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

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

</body>

</html>