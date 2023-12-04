<?php
function isLoggedIn() {

    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

if (!isLoggedIn()) {

    exit("You need to log in or register to view this page. <a href='login.php'>Log in</a> or <a href='register.php'>Register</a>.");
}
