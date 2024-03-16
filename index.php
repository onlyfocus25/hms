<?php
  require_once 'login.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .login-container {
      margin-top: 5%;
      position: relative; /* Position relative for absolute positioning inside */
    }
    .card {
      max-width: 400px;
      border-radius: 15px; /* Rounded corners */
    }
    .card-header {
      border-top-left-radius: 15px; /* Round the top left corner */
      border-top-right-radius: 15px; /* Round the top right corner */
    }
    .card-body {
      border-bottom-left-radius: 15px; /* Round the bottom left corner */
      border-bottom-right-radius: 15px; /* Round the bottom right corner */
    }
    .invalid-feedback {
      display: none;
    }
    .is-invalid {
      border-color: red !important;
    }
    .errormsg {
      color: red;
      text-align: center;
    }
    /* Additional CSS for auto-typing words */
#auto-typing-word {
  text-align: center; /* Center the text */
  /* margin-top: -50px; Remove this line or adjust the value */
  position: absolute; /* Position absolutely */
  top: 0; /* Place at the top */
  left: 50%; /* Center horizontally */
  transform: translateX(-50%); /* Adjust for centering */
  color: blue; /* Set color to blue */
}
  </style>
</head>
<body>

<div class="container">
  <div class="row justify-content-center login-container">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header text-center">
          <img src="images/logo-no-background.png" class="rounded-circle" alt="Image" id="logo" width="100">
          <h5>Login</h5>
        </div>
        <div class="card-body">
          <form action="/hospital/login.php" method="POST" id="loginForm">
            <div class="form-group">
              <label for="username">Username</label>
              <input type="text" class="form-control" name="username" id="username" placeholder="Enter username">
              <div class="invalid-feedback">Please enter a valid username.</div>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
              <div class="invalid-feedback">Please enter a valid password.</div>
            </div>
            <span class="errormsg"><?php echo $error_msg; ?></span>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Element for auto-typing words -->
<div id="auto-typing-word" class="h2"></div>

<!-- Custom JS -->
<script>
  // Client-side form validation
  document.getElementById("loginForm").addEventListener("submit", function(event) {
    var usernameInput = document.getElementById("username");
    var passwordInput = document.getElementById("password");
    var isValid = true;

    if (usernameInput.value.trim() === "") {
      usernameInput.classList.add("is-invalid");
      isValid = false;
    } else {
      usernameInput.classList.remove("is-invalid");
    }

    if (passwordInput.value.trim() === "") {
      passwordInput.classList.add("is-invalid");
      isValid = false;
    } else {
      passwordInput.classList.remove("is-invalid");
    }

    if (!isValid) {
      event.preventDefault(); // Prevent form submission if validation fails
    } else {
      // Rotate the logo 360 degrees
      var logo = document.getElementById("logo");
      logo.style.transition = "transform 2s";
      logo.style.transform = "rotate(360deg)";
    }
  });

  // Auto-typing words
  var words = ['Welcome', 'to', 'Our', 'Hospital', 'Referral', 'Management', 'System'];
  var index = 0;
  var wordElement = document.getElementById('auto-typing-word');

  function autoType() {
    if (index < words.length) {
      wordElement.textContent += words[index] + ' ';
      index++;
      setTimeout(autoType, 100); // Change the delay here (in milliseconds)
    }
  }

  autoType();
</script>

</body>
</html>
