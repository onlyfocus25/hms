<?php
session_start();
// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hospital Management System - Admin Dashboard</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="styles.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha384-RLQ/aWMVz3FcpoA5ItZG4sCtHPBDHpGJvhRGn2xgkvhCcMsRn7skwA/q4dqi3FZ2" crossorigin="anonymous">
  <style>
    /* Custom styles */
    body {
      font-family: Arial, Helvetica, sans-serif;
      margin: 0;
      padding: 0;
    }
    .header {
      background-color: #007bff;
      color: #fff;
      padding: 10px 20px;
      position: fixed;
      width: 100%;
      z-index: 1000;
    }
    .sidebar {
      background-color: #007bff;
      color: #fff;
      width: 250px;
      position: fixed;
      top: 50px;
      bottom: 0;
      left: 0;
      overflow-y: auto;
      padding-top: 20px; /* Adjust padding */
    }
    .sidebar h2 {
      margin-bottom: 30px;
    }
    .sidebar ul {
      list-style-type: none;
      padding: 0;
    }
    .sidebar ul li {
      padding: 10px;
    }
    .sidebar ul li a {
      color: #fff;
      text-decoration: none;
    }
    .sidebar ul li a:hover {
      text-decoration: underline;
    }
    .content {
      margin-left: 250px;
      padding: 20px;
      padding-top: 70px; /* Adjust padding */
    }
  </style>
</head>

<body>
  <!-- Header -->
  <?php
    require_once '../assets/header.php';
  ?>

<!-- Sidebar -->
<div class="sidebar">
  <h4>Hospital Management</h4>
  <ul class="nav flex-column">
    <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
    <li class="nav-item"><a class="nav-link" href="patients.php"><i class="fas fa-user-injured"></i> Patients list</a></li>
    <li class="nav-item"><a class="nav-link" href="refferal_form.php"><i class="fas fa-file-medical"></i> Apply for referral</a></li>
    <li class="nav-item"><a class="nav-link" href="doctors.php"><i class="fas fa-user-md"></i> Doctors</a></li>
    <li class="nav-item"><a class="nav-link" href="notifications.php.php"><i class="fas fa-bell"></i> Notifications</a></li>
    <li class="nav-item"><a class="nav-link" href="setting.php"><i class="fas fa-cog"></i> Settings</a></li>
    <li class="nav-item"><a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
  </ul>
</div>


  <!-- Page Content -->
  <div class="content">
