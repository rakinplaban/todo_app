<?php
    session_start(); // Start the session

    // Check if the user is logged in (adjust the condition as needed)
    if (isset($_SESSION['user_id'])) {  
        // Unset all session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();

        // Redirect the user to the login page or any other page as needed
        header("Location: login.php"); // Replace 'login.php' with your login page
        exit();
    } else {
        // Redirect to the login page in case the user is not logged in
        header("Location: login.php"); // Replace 'login.php' with your login page
        exit();
    }
?>
