<?php
session_start();

// Check if user is logged in, if not redirect to login page
if (!isset($_SESSION['username'])) {
    header("Location: ../login.html");
    exit();
}

require_once '../db/db.php';

// Function to validate input (only alphanumeric characters and spaces)
function validateInput($input) {
    return preg_match('/^[a-zA-Z0-9\s]+$/', $input);
}

// Function to check if the referral already exists
function checkExistingReferral($patientName, $doctorName, $date, $conn) {
    $sql = "SELECT * FROM internalpatient WHERE name = ? AND doctorName = ? AND date = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $patientName, $doctorName, $date);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $resultCount = mysqli_stmt_num_rows($stmt);
    mysqli_stmt_close($stmt);
    return $resultCount > 0;
}

$internalSuccessMessage = $internalErrorMessage = $externalSuccessMessage = $externalErrorMessage = '';

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['internal_submit'])) {
        handleReferralSubmission('internal');
    } elseif (isset($_POST['external_submit'])) {
        handleReferralSubmission('external');
    }
}

// Function to handle referral form submission
function handleReferralSubmission($type) {
    global $internalSuccessMessage, $internalErrorMessage, $externalSuccessMessage, $externalErrorMessage, $conn;

    // Validate input data
    $hospitalName = $_POST[$type === 'internal' ? 'hospitalName' : 'hospitalNameExt'];
    $clinicalHistory = $_POST[$type === 'internal' ? 'clinicalHistory' : 'clinicalHistoryExt'];
    $patientName = $_POST[$type === 'internal' ? 'patientName' : 'patientNameExt'];
    $managementGiven = $_POST[$type === 'internal' ? 'managementGiven' : 'managementGivenExt'];
    $doctorName = $_POST[$type === 'internal' ? 'doctorName' : 'doctorNameExt'];
    $reason = $_POST[$type === 'internal' ? 'reason' : 'reasonExt'];
    $date = $_POST[$type === 'internal' ? 'date' : 'dateExt'];

    // Check if the referral already exists in the database
    $existingReferral = checkExistingReferral($patientName, $doctorName, $date, $conn);
    if ($existingReferral) {
        ${$type . 'ErrorMessage'} = "Referral already exists.";
        return; // Exit function if referral already exists
    }

    if (empty($patientName) || empty($doctorName) || empty($reason) || empty($date) || empty($clinicalHistory)) {
        ${$type . 'ErrorMessage'} = "All fields are required.";    
    } elseif (!validateInput($patientName) || !validateInput($doctorName) || !validateInput($reason)) {
        ${$type . 'ErrorMessage'} = "Invalid input. Please use only alphanumeric characters and spaces.";
    } else {
        // Insert data into the appropriate referral table
        $table = $type === 'internal' ? 'internalpatient' : 'externalpatient';
        $sql = "INSERT INTO $table (name, doctorName, reason, date, hospitalName, clinicalHistory, managementGiven ) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssss", $patientName, $doctorName, $reason, $date, $hospitalName, $clinicalHistory, $managementGiven);

        if (mysqli_stmt_execute($stmt)) {
            ${$type . 'SuccessMessage'} = ucfirst($type) . " referral submitted successfully.";
        } else {
            ${$type . 'ErrorMessage'} = "Error: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    }
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
          integrity="sha384-RLQ/aWMVz3FcpoA5ItZG4sCtHPBDHpGJvhRGn2xgkvhCcMsRn7skwA/q4dqi3FZ2"
          crossorigin="anonymous">
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

        .form-control.error {
            border: 1px solid red;
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
        <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        </li>
        <li class="nav-item"><a class="nav-link" href="patients.php"><i class="fas fa-user-injured"></i> Patients
                list</a></li>
        <li class="nav-item"><a class="nav-link" href="refferal_form.php"><i class="fas fa-file-medical"></i> Apply
                for referral</a></li>
        <li class="nav-item"><a class="nav-link" href="doctors.php"><i class="fas fa-user-md"></i> Doctors</a></li>
        <li class="nav-item"><a class="nav-link" href="setting.php"><i class="fas fa-cog"></i> Settings</a></li>
        <li class="nav-item"><a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </li>
    </ul>
</div>

<!-- Page Content -->
<div class="content">
    <header>
        <h2>Dashboard</h2>
    </header>
    <main>
        <p>Welcome to the hospital management system admin dashboard. You can manage patients, doctors,
            appointments, and settings from here.</p>

        <!-- Tabbed Content -->
        <div class="container mt-5">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="internal-tab" data-toggle="tab" href="#internal" role="tab"
                       aria-controls="internal" aria-selected="true" style="color: #007bff;">Internal Referral</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="external-tab" data-toggle="tab" href="#external" role="tab"
                       aria-controls="external" aria-selected="false" style="color: #007bff;">External Referral</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="internal" role="tabpanel" aria-labelledby="internal-tab">
                    <!-- Internal Referral Form -->
                    <div class="container mt-5">
                        <h2>Internal Referral Form</h2>
                        <!-- Display internal referral success or error message -->
                        <?php if (!empty($internalSuccessMessage) || !empty($internalErrorMessage)) : ?>
                            <div class="alert <?php echo !empty($internalSuccessMessage) ? 'alert-success' : 'alert-danger'; ?>"
                                 role="alert">
                                <?php echo !empty($internalSuccessMessage) ? $internalSuccessMessage : $internalErrorMessage; ?>
                            </div>
                        <?php endif; ?>
                        <form id="internalForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group">
                                <label for="hospitalName">Referred To:</label>
                                <input type="text" class="form-control" id="hospitalName" name="hospitalName"
                                       placeholder="Enter Hospital Name">
                            </div>
                            <div class="form-group">
                                <label for="patientName">Patient Name:</label>
                                <input type="text" class="form-control" id="patientName" name="patientName"
                                       placeholder="Enter patient's name">
                            </div>
                            <div class="form-group">
                                <label for="age">Age:</label>
                                <input type="text" class="form-control" id="age" name="age" placeholder="Enter patient's age">
                            </div>
                            <div class="form-group">
                                <label for="clinicalHistory">Clinical History:</label>
                                <textarea class="form-control" id="clinicalHistory" name="clinicalHistory" rows="3"
                                          placeholder="Enter clinical history"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="physicalExam">Physical Examination and Investigations Done:</label>
                                <textarea class="form-control" id="physicalExam" name="physicalExam" rows="3"
                                          placeholder="Enter physical examination and investigations done"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="doctorName">Referring Doctor:</label>
                                <input type="text" class="form-control" id="doctorName" name="doctorName"
                                       placeholder="Enter referring doctor's name">
                            </div>
                            <div class="form-group">
                                <label for="reason">Reason for Referral:</label>
                                <textarea class="form-control" id="reason" name="reason" rows="3"
                                          placeholder="Enter reason for referral"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="managementGiven">Management Given:</label>
                                <textarea class="form-control" id="managementGiven" name="managementGiven" rows="3"
                                          placeholder="Enter management given"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="date">Date of Referral:</label>
                                <input type="date" class="form-control" id="date" name="date">
                            </div>
                            <button type="submit" name="internal_submit" class="btn btn-primary"
                                    style="background-color: #007bff;">Submit
                            </button>
                        </form>
                    </div>
                </div>
                <div class="tab-pane fade" id="external" role="tabpanel" aria-labelledby="external-tab">
                    <!-- External Referral Form -->
                    <div class="container mt-5">
                        <h2>External Referral Form</h2>
                        <!-- Display external referral success or error message -->
                        <?php if (!empty($externalSuccessMessage) || !empty($externalErrorMessage)) : ?>
                            <div class="alert <?php echo !empty($externalSuccessMessage) ? 'alert-success' : 'alert-danger'; ?>"
                                 role="alert">
                                <?php echo !empty($externalSuccessMessage) ? $externalSuccessMessage : $externalErrorMessage; ?>
                            </div>
                        <?php endif; ?>
                        <form id="externalForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group">
                                <label for="hospitalNameExt">Referred To:</label>
                                <input type="text" class="form-control" id="hospitalNameExt" name="hospitalNameExt"
                                       placeholder="Enter Hospital Name">
                            </div>
                            <div class="form-group">
                                <label for="patientNameExt">Patient Name:</label>
                                <input type="text" class="form-control" id="patientNameExt" name="patientNameExt"
                                       placeholder="Enter patient's name">
                            </div>
                            <div class="form-group">
                                <label for="ageExt">Age:</label>
                                <input type="text" class="form-control" id="ageExt" name="ageExt" placeholder="Enter patient's age">
                            </div>
                            <div class="form-group">
                                <label for="clinicalHistoryExt">Clinical History:</label>
                                <textarea class="form-control" id="clinicalHistoryExt" name="clinicalHistoryExt" rows="3"
                                          placeholder="Enter clinical history"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="physicalExamExt">Physical Examination and Investigations Done:</label>
                                <textarea class="form-control" id="physicalExamExt" name="physicalExamExt" rows="3"
                                          placeholder="Enter physical examination and investigations done"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="doctorNameExt">Referring Doctor:</label>
                                <input type="text" class="form-control" id="doctorNameExt" name="doctorNameExt"
                                       placeholder="Enter referring doctor's name">
                            </div>
                            <div class="form-group">
                                <label for="reasonExt">Reason for Referral:</label>
                                <textarea class="form-control" id="reasonExt" name="reasonExt" rows="3"
                                          placeholder="Enter reason for referral"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="managementGivenExt">Management Given:</label>
                                <textarea class="form-control" id="managementGivenExt" name="managementGivenExt" rows="3"
                                          placeholder="Enter management given"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="dateExt">Date of Referral:</label>
                                <input type="date" class="form-control" id="dateExt" name="dateExt">
                            </div>
                            <button type="submit" name="external_submit" class="btn btn-primary"
                                    style="background-color: #007bff;">Submit
                            </button>
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
