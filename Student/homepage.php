<?php
include('connection.php');
session_start();

// Check if the user is logged in as a student
if (!isset($_SESSION['studentLoggedIn']) || $_SESSION['studentLoggedIn'] !== true) {
    // Redirect to the student login page
    header("Location: index.php");
    exit();
}

// Get the student ID
$studentId = $_SESSION['student_id'];

// Retrieve the count of classes for the current student
$classCountQuery = "SELECT COUNT(*) AS classCount FROM class_student WHERE student_id = $studentId";
$classCountResult = mysqli_query($con, $classCountQuery);
$classCountRow = mysqli_fetch_assoc($classCountResult);
$classCount = $classCountRow['classCount'];

// Close the database connection
mysqli_close($con);
?>

<html>

<head>
    <title>Aluna-Home</title>
    <link rel="stylesheet" type="text/css" href="../css/homepage.css">
    <link rel="stylesheet" type="text/css" href="../css/nav.css">
    <link rel="shortcut icon" type="image/x-icon" href="../img/favicon.ico" />
    <!-- MultiNav -->
    <script src="https://code.jquery.com/jquery.min.js"></script>
    <!-- calendar -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.js"></script>
    <script>
        // Disable browser back and forward buttons
        history.pushState(null, null, location.href);
        window.onpopstate = function () {
            history.go(1);
        };
    </script>

    <style>
        #container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: flex-start;
            gap: 20px;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <!-- Nav -->
    <div id="nav-placeholder"></div>

    <!-- Content -->
    <div id="container">
        <div style="text-align: center; width: 100%;">
            <div class="counter classes">
                <small>Classes</small>
                <span id="classCounter"><?php echo $classCount; ?></span>
            </div>
        </div>

        <!--Quote Bar-->
        <?php include 'quote_bar.php'; ?>

        <!-- Calendar -->
        <div id='calendar'></div>

        <!-- To-do List -->
        <div id="todo-list">
            <h2>Todo List</h2>
            <input type="text" id="todo-input" placeholder="Add a new todo">
            <button onclick="addTodo()">Add</button>
            <ul id="todo-items">
                <!-- Existing todo items will be added dynamically here -->
            </ul>
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
    <!-- calendar-config.js -->
    <script src="../JS/calendar-config.js"></script>
    <!-- dark-mode.js -->
    <script src="../JS/dark-mode.js"></script>

    <script>
        // Update the counters with real values
        document.getElementById('classCounter').innerText = '<?php echo $classCount; ?>';

        // Add todo function
        function addTodo() {
            var todoInput = document.getElementById('todo-input');
            var todoItems = document.getElementById('todo-items');

            var todoText = todoInput.value;
            if (todoText.trim() === '') {
                alert('Please enter a valid todo item.');
                return;
            }

            var todoItem = document.createElement('li');
            todoItem.innerText = todoText;

            todoItems.appendChild(todoItem);
            todoInput.value = '';
        }
    </script>
</body>

</html>
