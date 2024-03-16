<?php
session_start();

// Change these values to your MySQL database configuration
$host = 'localhost';
$dbname = 'hms';
$username = 'root';
$password = '';

$error_msg= '';

// Establish a connection to the database
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Error: Could not connect. " . mysqli_connect_error());
}

// Process login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Server-side validation
    if (empty($username) || empty($password)) {
        $error_msg = "Please enter both username and password.";
    } else {
        // Query to check if the user exists and the password is correct
        $query = "SELECT * FROM logindata WHERE username = '$username' AND password = '$password'";
        $result = mysqli_query($conn, $query);

        // If user exists and password is correct
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['username'] = $username;

            // Check the role of the user
            $role = $row['role'];
            if ($role == 'inchargeadmin') {
                header("Location: /hospital/doctor_incharge/index.php");
            } elseif ($role == 'admin') {
                header("Location: /hospital/adminpage/index.php");
            } elseif ($role == 'adminhospital') {
                header("Location: /hospital/admin_hospital/index.php");
            } 
            else {
                $error_msg = "Invalid role. Please contact administrator.";
                header("Location: index.php");
            }
            exit();
        } else {
            $error_msg = "Invalid username or password.";
            header("Location: index.php");
            exit();
        }
    }
}

mysqli_close($conn);
?>