<?php
session_start();
require_once '../db/db.php';

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: ../login.html");
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
      margin-bottom: 20px;
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
    .table th,
    .table td {
      text-align: center;
    }
  </style>
</head>

<body>
  <!-- Header -->
  <?php require_once '../assets/header.php'; ?>

  <!-- Sidebar -->
  <div class="sidebar">
    <h4>Hospital Management</h4>
    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
      <li class="nav-item"><a class="nav-link" href="patients.php"><i class="fas fa-user-injured"></i> Patients list</a></li>
      <li class="nav-item"><a class="nav-link" href="refferal_form.php"><i class="fas fa-file-medical"></i> Apply for referral</a></li>
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
        <!-- Search Bar -->
        <input type="text" id="searchInput" class="form-control mb-3" placeholder="Search...">

        <!-- Internal Patients Table -->
        <h2>Internal Patients</h2>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Doctor Name</th>
                <th>Reason</th>
                <th>Date</th>
                <th>Clinical History</th>
                <th>Physical Examination</th>
                <th>Management Given</th>
              </tr>
            </thead>
            <tbody id="internalPatientsTable">
              <?php
              // Fetch data from internalpatient table
              $internal_patients_sql = "SELECT * FROM internalpatient";
              $internal_patients_result = mysqli_query($conn, $internal_patients_sql);
              if(mysqli_num_rows($internal_patients_result) > 0) {
                while ($row = mysqli_fetch_assoc($internal_patients_result)) {
                  echo "<tr>";
                  echo "<td>" . $row['id'] . "</td>";
                  echo "<td>" . $row['name'] . "</td>";
                  echo "<td>" . $row['doctorName'] . "</td>";
                  echo "<td>" . $row['reason'] . "</td>";
                  echo "<td>" . $row['date'] . "</td>";
                  echo "<td>" . $row['clinicalHistory'] . "</td>";
                  echo "<td>" . $row['physicalExam'] . "</td>";
                  echo "<td>" . $row['managementGiven'] . "</td>";
                  echo "</tr>";
                }
              } else {
                echo "<tr><td colspan='8'>No data available</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>

        <!-- External Patients Table -->
        <h2>External Patients</h2>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Doctor Name</th>
                <th>Reason</th>
                <th>Date</th>
                <th>Clinical History</th>
                <th>Physical Examination</th>
                <th>Management Given</th>
              </tr>
            </thead>
            <tbody id="externalPatientsTable">
              <?php
              // Fetch data from externalpatient table
              $external_patients_sql = "SELECT * FROM externalpatient";
              $external_patients_result = mysqli_query($conn, $external_patients_sql);
              if(mysqli_num_rows($external_patients_result) > 0) {
                while ($row = mysqli_fetch_assoc($external_patients_result)) {
                  echo "<tr>";
                  echo "<td>" . $row['id'] . "</td>";
                  echo "<td>" . $row['name'] . "</td>";
                  echo "<td>" . $row['doctorName'] . "</td>";
                  echo "<td>" . $row['reason'] . "</td>";
                  echo "<td>" . $row['date'] . "</td>";
                  echo "<td>" . $row['clinicalHistory'] . "</td>";
                  echo "<td>" . $row['physicalExam'] . "</td>";
                  echo "<td>" . $row['managementGiven'] . "</td>";
                  echo "</tr>";
                }
              } else {
                echo "<tr><td colspan='8'>No data available</td></tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <!-- JavaScript for Search Functionality -->
  <script>
    $(document).ready(function(){
      $('#searchInput').on('keyup', function(){
        var value = $(this).val().toLowerCase();
        $('#internalPatientsTable tr, #externalPatientsTable tr').filter(function(){
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
    });
  </script>
</body>
</html>
