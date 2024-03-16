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
    .sidebar h4 {
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
    <li class="nav-item"><a class="nav-link" href="addhospital.php"><i class="fas fa-user-injured"></i>Add Hospital</a></li>
    <li class="nav-item"><a class="nav-link" href="doctors.php"><i class="fas fa-user-md"></i> Doctors</a></li>
    <li class="nav-item"><a class="nav-link" href="setting.php"><i class="fas fa-cog"></i> Settings</a></li>
    <li class="nav-item"><a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
  </ul>
</div>


  <!-- Page Content -->
  <div class="content">
    <header>
      <h2>Dashboard</h2>
    </header>
    <main>
      <p>Welcome to the hospital management system admin dashboard. You can manage patients, doctors, appointments, and settings from here.</p>

      <div class="container mt-5">
        <div class="row">
          <div class="col-md-3">
            <!-- Sidebar -->
            <div class="list-group">
              <a href="#" class="list-group-item list-group-item-action active">General</a>
              <a href="#" class="list-group-item list-group-item-action">Privacy</a>
              <a href="#" class="list-group-item list-group-item-action">Security</a>
              <a href="#" class="list-group-item list-group-item-action">Notifications</a>
            </div>
          </div>
          <div class="col-md-9">
            <!-- Settings Content -->
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">General Settings</h5>
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#settingsModal">
                  Open Settings
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="settingsModal" tabindex="-1" role="dialog" aria-labelledby="settingsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="settingsModalLabel">General Settings</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form>
                <div class="form-group">
                  <label for="fullName">Full Name</label>
                  <input type="text" class="form-control" id="fullName">
                </div>
                <div class="form-group">
                  <label for="email">Email Address</label>
                  <input type="email" class="form-control" id="email">
                </div>
                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" class="form-control" id="password">
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Save Changes</button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
