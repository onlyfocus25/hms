<!-- Header -->
<div class="header bg-dark shadow">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6">
        <span>Welcome, <?php echo $_SESSION['username']; ?>!</span>
      </div>
      <div class="col-md-6 text-right">
        <span id="currentDateTime"></span>
        <img src="../images/face.jpg" alt="Profile Image" class="rounded-circle" style="width: 40px; height: 40px;">
      </div>
    </div>
  </div>
</div>

<script>
  // Update date and time every second
  setInterval(function() {
    var dateTime = new Date();
    var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true };
    var formattedDateTime = dateTime.toLocaleString('en-US', options);
    document.getElementById('currentDateTime').textContent = formattedDateTime;
  }, 1000);
</script>
