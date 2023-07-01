<?php
@session_start();

if (isset($_SESSION['Failed'])) {
    echo '<script language="javascript">
    alert("Wrong email or password!")
    </script>';
    unset($_SESSION['Failed']);
}
?>

<html>
<head>
    <title>Aluna - Admin Sign In</title>
    <link rel="shortcut icon" type="image/x-icon" href="../img/favicon.ico" />
    <link rel="stylesheet" type="text/css" href="../css/index.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>
<body id="signin">
    <div class="grid-container">
        <!-- Logo -->
        <div class="item1">
            <img src="../img/Logo.png" alt="Logo" width="400px">
            <p>Hello, welcome to Aluna</p>
            <p>The student management system for tuition Uni Pintar</p>
        </div>
        <!-- Quote -->
        <div class="item2">
            <div class="quote">
                <p>
                    <i class="fa-solid fa-phone"></i>
                    +94 0116 789 754
                </p>
                <div class="banner-container fade">
                    <!-- Add your quote banners here -->
                    <!-- Example banner -->
                    <div class="banner fade">
                        <div class="quote box">
                            <img src="../img/theskygrasstree.png" alt="">
                            <div class="quote_area">
                                <h4>“The expert in anything was once a beginner.” —Helen Hayes.</h4>
                            </div>
                        </div>
                    </div>
                    <!-- End of example banner -->
                </div>
                <h2>Hello, Welcome to Aluna!</h2>
                <h3>A student management system for Uni Pintar.
                    <br>Here's a quote to make your day!
                </h3>
            </div>
        </div>
        <!-- Sign In -->
        <div class="item3">
            <div id="frm">
                <h1>Sign in</h1>
                <form action="registration.php" method="POST" name="myForm" onsubmit="return validateForm()" required>
                    <p>
                        <div class="form__group field">
                            <label class="signInDetails">Email:</label>
                            <input id="email" class="form__field" name="email" placeholder="Enter your email address" type="email" required />
                        </div>
                    </p>
                    <p>
                        <div class="form__group field">
                            <label class="signInDetails">Password:</label>
                            <input id="pass" name="password" class="form__field" placeholder="Enter your password" type="password" required />
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
                            <button formnovalidate class="button signin-btn" type="submit" onclick="lsRememberMe()" onclick="addAnimation2()">Sign in</button>
                        </div>
                    </p>
                </form>
            </div>
        </div>
    </div>

    <!-- RememberMe.js -->
    <script src="../JS/RememberMe.js"></script>
    <!-- Eye.js -->
    <script src="https://kit.fontawesome.com/9bcf1d89b4.js" crossorigin="anonymous"></script>
    <script src="../JS/Eye.js"></script>
    <!-- Quote.js -->
    <script src="../JS/Quote.js"></script>
    <!-- Validation.js -->
    <script src="../JS/validation.js"></script>
</body>
</html>

