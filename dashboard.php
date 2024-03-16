<?php
session_start();

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

// Display dashboard content
echo "Welcome, " . $_SESSION['username'] . "! This is your dashboard.";
?>
