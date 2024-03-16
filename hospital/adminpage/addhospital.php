<?php
session_start();
// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: ../login/login.html");
    exit();
}

// Include database connection
include '../db/db.php';

$error_message = '';
$success_message = '';

// Handle Add Hospital Form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hospitalName = $_POST['hospitalName'];
    $cityLocated = $_POST['cityLocated'];

    // Validate inputs
    if (empty($hospitalName) || empty($cityLocated)) {
        $error_message = "All fields are required.";
    } else {
        // Check for duplicate entry
        $check_sql = "SELECT * FROM hospitals WHERE name = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "s", $hospitalName);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);
        if (mysqli_stmt_num_rows($check_stmt) > 0) {
            $error_message = "Hospital already exists.";
        } else {
            // Insert data into database using prepared statement
            $sql = "INSERT INTO hospitals (name, city) VALUES (?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $hospitalName, $cityLocated);

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                $success_message = "Hospital added successfully.";
            } else {
                $error_message = "Error adding hospital: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_stmt_close($check_stmt);
    }
}

// Fetch data from database
$sql = "SELECT * FROM hospitals";
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
        <h2>Hospitals Information</h2>
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
              <th>Hospital Name</th>
              <th>City Located</th>
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
                echo "<td>" . $row['city'] . "</td>";
                echo "</tr>";
              }
            } else {
              echo "<tr><td colspan='3'>No hospitals found</td></tr>";
            }
            ?>
          </tbody>
        </table>

        <!-- Add Hospital Button -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addHospitalModal">Add Hospital</button>
      </div>

      <!-- Add Hospital Modal -->
      <div class="modal fade" id="addHospitalModal" tabindex="-1" role="dialog" aria-labelledby="addHospitalModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addHospitalModalLabel">Add Hospital</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <!-- Hospital Form with PHP Validation -->
              <form method="post" action="<?php echo htmlspecialchars($_SERVER["SCRIPT_NAME"]); ?>">
                <div class="form-group">
                  <label for="hospitalName">Hospital Name:</label>
                  <input type="text" class="form-control" id="hospitalName" name="hospitalName" placeholder="Enter hospital's name" required>
                </div>
                <div class="form-group">
                  <label for="cityLocated">City Located:</label>
                  <input type="text" class="form-control" id="cityLocated" name="cityLocated" placeholder="Enter city where hospital is located" required>
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
// Close database connection
mysqli_close($conn);
?>
