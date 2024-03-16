<?php
session_start();
// Redirect to login page if user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.html");
    exit();
}

include '../db/db.php';

$name = '';
$department = '';
$specialization = '';
$error_message = '';

// Handle Add Doctor Form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $department = $_POST['department'];
    $specialization = $_POST['specialization'];

    // Validate inputs
    if (empty($name) || empty($department) || empty($specialization)) {
        $error_message = "All fields are required.";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        $error_message = "Only letters and white space allowed for Name.";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $department)) {
        $error_message = "Only letters and white space allowed for Department.";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $specialization)) {
        $error_message = "Only letters and white space allowed for Area of Specialization.";
    } else {
        // Check for duplicate entry
        $check_sql = "SELECT * FROM doctors WHERE name = ? AND department = ? AND specialization = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "sss", $name, $department, $specialization);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $error_message = "Doctor already exists.";
        } else {
            // Insert data into database
            $sql = "INSERT INTO doctors (name, department, specialization) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "sss", $name, $department, $specialization);

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                $success_message = "Doctor added successfully.";
            } else {
                $error_message = "Error: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_stmt_close($check_stmt);
    }
}

// Fetch data from database
$sql = "SELECT * FROM doctors";
$result = mysqli_query($conn, $sql);
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
    .error-message {
      color: red;
    }
    .success-message {
      color: green;
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
        <h2>Doctors Information</h2>
        <?php
        // Display error message if there's any
        if (!empty($error_message)) {
            echo "<div class='error-message'>$error_message</div>";
        }
        // Display success message if there's any
        if (!empty($success_message)) {
            echo "<div class='success-message'>$success_message</div>";
        }
        ?>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Department</th>
              <th>Area of Specialization</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Display fetched data in the table
            if (mysqli_num_rows($result) > 0) {
              while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['department'] . "</td>";
                echo "<td>" . $row['specialization'] . "</td>";
                echo "</tr>";
              }
            } else {
              echo "<tr><td colspan='4'>No doctors found</td></tr>";
            }
            ?>
          </tbody>
        </table>

        <!-- Add Doctor Button -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addDoctorModal">Add Doctor</button>
      </div>

      <!-- Add Doctor Modal -->
      <div class="modal fade" id="addDoctorModal" tabindex="-1" role="dialog" aria-labelledby="addDoctorModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addDoctorModalLabel">Add Doctor</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <!-- Doctor Form with PHP Validation -->
              <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                  <label for="doctorName">Name:</label>
                  <input type="text" class="form-control" id="doctorName" name="name" placeholder="Enter doctor's name" pattern="[A-Za-z ]+" title="Only letters and white space allowed for Name" required>
                </div>
                <div class="form-group">
                  <label for="doctorDepartment">Department:</label>
                  <input type="text" class="form-control" id="doctorDepartment" name="department" placeholder="Enter doctor's department" pattern="[A-Za-z ]+" title="Only letters and white space allowed for Department" required>
                </div>
                <div class="form-group">
                  <label for="doctorSpecialization">Area of Specialization:</label>
                  <input type="text" class="form-control" id="doctorSpecialization" name="specialization" placeholder="Enter area of specialization" pattern="[A-Za-z ]+" title="Only letters and white space allowed for Area of Specialization" required>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
              </form>
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

<?php
mysqli_close($conn);
?>
