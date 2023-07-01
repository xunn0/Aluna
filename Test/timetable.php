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
    $classNames[] = $classRow['name'];
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
    <label for="class-select">Select Class:</label>
        <select id="class-select">
            <option value="">All Classes</option>
            <?php
            // Iterate through the class names array and create an option for each class
            foreach ($classNames as $className) {
                echo "<option value='$className'>$className</option>";
            }
            ?>
        </select>
        <table class="timetable">
            <thead>
                <tr>
                    <th></th>
                    <th>08:30 AM - 09:30 AM</th>
                    <th>09:30 AM - 10:30 AM</th>
                    <th>10:30 AM - 11:30 AM</th>
                    <th>11:30 AM - 12:30 PM</th>
                    <th>12:30 PM - 01:30 PM</th>
                    <th>01:30 PM - 02:30 PM</th>
                    <th>02:30 PM - 03:30 PM</th>
                    <th>03:30 PM - 04:30 PM</th>
                    <th>04:30 PM - 05:30 PM</th>
                    <th>05:30 PM - 06:30 PM</th>
                    <th>06:30 PM - 07:30 PM</th>
                    <th>07:30 PM - 08:30 PM</th>
                    <th>08:30 PM - 09:30 PM</th>
                    <th>09:30 PM - 10:30 PM</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Sunday</td>
                    <td>Class A</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Monday</td>
                    <td></td>
                    <td>Class D</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Tuesday</td>
                    <td>Class A</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Wednesday</td>
                    <td>Class A</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Thursday</td>
                    <td>Class A</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Friday</td>
                    <td>Class A</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td>Saturday</td>
                    <td>Class A</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
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
    <!-- draggable.js -->
    <script>
        $(function() {
            $("#class-box").draggable({
                containment: ".container",
                grid: [60, 60],
                snap: ".timetable td",
                snapMode: "inner",
                snapTolerance: 10
            });
        });
    </script>
</body>

</html>
