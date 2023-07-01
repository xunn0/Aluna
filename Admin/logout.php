<?php
// Start a session
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Ensure that any existing session cookie is deleted
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Redirect the admin user to the admin login page
header("Location: index.php");
exit;
?>
