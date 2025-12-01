<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style/style.css">
  <title>Login - Food Ordering System</title>

  <style>
    .login{
      width: 40%;
      margin: auto;
      border: 1px solid #333;
      padding: 30px;
    }
  </style>
</head>
<body> 

<?php 
  include("../config/constants.php");
      if(isset($_SESSION['login'])){
       echo $_SESSION['login'];
       unset($_SESSION['login']);
      }
  ?> 

<div class="login">

    <div class="form-container">
      <h2>Login</h2>

      <form action="#" method="POST">
        <div class="input-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="Username" style="width: 100%;" required>
        </div>

        <div class="input-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Password" style="width: 100%;" required>
        </div>

        <input type="submit" class="submit-btn" name="login" value="Login" style="width: 100%;"></input>
      </form>
    </div>
</div>

<!-- REMOVE SESSION MESSAGE AFTER THREE SECONDS -->
<script>
    document.addEventListener("DOMContentLoaded", function(){
      setTimeout(function(){
        const messageDiv = document.getElementById("session");

        if(messageDiv){
          messageDiv.style.display = "none";
        }
      }, 3000);
    })
   </script>

</body>
</html>

<!-- Backend -->
<?php
// Check whether the login button is clicked or not.
if (isset($_POST['login'])) {
  // Get the values
  $username = mysqli_real_escape_string($conn, $_POST['username']);
  $password = mysqli_real_escape_string($conn, md5($_POST['password']));

  // SQL query to check whether the username & password exist
  $sql = "SELECT * FROM tbl_admin WHERE username='$username' AND password='$password'";

  // Execute the query
  $res = mysqli_query($conn, $sql);

  // Count rows to check if the user exists or not
  $count = mysqli_num_rows($res);

  if ($count == 1) {
    // Session to check whether the user is logged in or not
    $_SESSION['user'] = $username;

    // Redirect to the admin page with success message
    $_SESSION['login'] = "<div class='success' id='session'>Successfully logged in. </div>";
    header('location:' . SITEURL . 'admin/~/manage-admin.php');
  } else {
    // Incorrect user message
    $_SESSION['login'] = "<div class='error' id='session' style='width: 40%; margin: auto;'>Username not found!, Please try again. </div>";
   header('location: ' . SITEURL . 'admin/login.php'); 
  }
}
?>
