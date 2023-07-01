    <?php
    // Start a session
    @session_start();

    // Prevent caching of the login page
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    // Check if the user is already logged in as admin
    if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true && $_SESSION['role'] === 'admin') {
        // Redirect to the admin homepage
        header("Location: homepage.php");
        exit();
    }
    ?>

<html>

<head>
    <title>Aluna-Admin</title>
    <link rel="shortcut icon" type="image/x-icon" href="../img/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="../css/index.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <!--Font-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

</head>
<body id="signin">
    <div class="grid-container">
        <!--Logo-->
        <div class="item1">
            <img src="../img/Logo.png" alt="Logo" width="400px">
            <p>Hello, welcome to Aluna</p>
            <p>The student management system for tuition Uni Pintar</p>
        </div>
        <!--Quote-->
        <div class="item2">
            <div class="quote">
                <p>
                    <i class="fa-solid fa-phone"></i>
                    +94 0116 789 754
                </p>
                <div class="banner-container fade">
                    <div class="banner fade">
                        <div class="quote box">
                            <img src="../img/theskygrasstree.png" alt="">
                            <div class="quote_area">
                                <h4>“The expert in anything was once a beginner.” —Helen Hayes.</h4>
                            </div>
                        </div>
                    </div>
                    <div class="banner fade">
                        <div class="quote box">
                            <img src="../img/floatingislandwaterfall.png" alt="">
                            <div class="quote_area">
                                <h4>Take charge of your attitude. Don't let someone else choose it for you.</h4>
                            </div>
                        </div>
                    </div>
                    <div class="banner fade">
                        <div class="quote box">
                            <img src="../img/pinktreebeautifulgreenery.png" alt="">
                            <div class="quote_area">
                                <h4>Show respect for everyone who works for a living, regardless of how trivial their job.</h4>
                            </div>
                        </div>
                    </div>
                    <div class="banner fade">
                        <div class="quote box">
                            <img src="../img/rangeofmountainswaterfall.png" alt="">
                            <div class="quote_area">
                                <h4>Keep it simple.</h4>
                            </div>
                        </div>
                    </div>
                    <div class="banner fade">
                        <div class="quote box">
                            <img src="../img/peakwithtreesandwaterfall.png" alt="">
                            <div class="quote_area">
                                <h4>Be brave. Even if you're not, pretend to be. No one can tell the difference.</h4>
                            </div>
                        </div>
                    </div>
                    <div class="banner fade">
                        <div class="quote box">
                            <img src="../img/winterskytreescastle.png" alt="">
                            <div class="quote_area">
                                <h4>Make a habit to do nice things for people who will never find out.</h4>
                            </div>
                        </div>
                    </div>
                    <div class="banner fade">
                        <div class="quote box">
                            <img src="../img/sunrisingbetweenmountainslandscape.png" alt="">
                            <div class="quote_area">
                                <h4>Be modest. A lot was accomplished before you were born.</h4>
                            </div>
                        </div>
                    </div>
                    <div style="text-align:center">
                        <span class="dot"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                    </div>
                </div>
                <h2>Hello, Welcome to Aluna!</h2>
                <h3>A student management system for Uni Pintar.
                    <br>Here's a quote to make your day!
                </h3>
            </div>
        </div>
        <!--SignIn-->
        <div class="item3">
            <div id="frm">
                <h1>Sign in</h1>
                <form action="authentication.php" method="POST" name="myForm" onsubmit="return validateForm()" required>
                    <p>
                    <div class="form__group field">
                        <label class="signInDetails"> Email: </label>
                        <input id='email' class="form__field" name="email" placeholder="Enter your email address" type="email" required />
                    </div>
                    </p>
                    <p>
                    <div class="form__group field">
                        <label class="signInDetails"> Password: </label>
                        <input id="password" name="password" class="form__field" placeholder="Enter your password" type="password" required />
                        <i id="eye" class="fa-regular fa-eye-slash"></i>
                    </div>
                    </p>
                    <label class="container">
                        <input id="remember" class="checkmark" checked="checked" name="remember" type="checkbox" />
                        <span class="checkmark"></span>
                        <label class="rememberme" for="remember">Remember me</label>
                    </label>
                    <p>
                    <div class="ta-c padT150 padB150">
                        <button formnovalidate class="button signin-btn" type="submit" onclick="lsRememberMe()">Sign in</button>
                    </div>
                    <div class="loginlinks">
                    <a href="../Teacher/index.php">Teacher Login</a>
                    <a href="../Student/index.php">Student Login</a>
                    </div>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <!-- RememberMe.js -->
    <script src="../JS/RememberMe.js"></script>
    <!--Eye.js-->
    <script src="https://kit.fontawesome.com/9bcf1d89b4.js" crossorigin="anonymous"></script>
    <script src="../JS/Eye.js"></script>
    <!-- Quote.js -->
    <script src="../JS/Quote.js"></script>
    <!--validation.js-->
    <script src="../JS/validation.js"></script>
</body>

</html>