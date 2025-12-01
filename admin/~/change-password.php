<?php


// Including menu & session messages
include('../partials/menu.php'); 

// Check if there is a session message
if (isset($_SESSION['message'])) {
    // Get the message and display it
    $message = $_SESSION['message'];
    $message_class = $message['type'] == 'success' ? 'success' : 'error';

    // Output the session message
    echo "<p class='$message_class' id='session'>{$message['text']}</p>";

    // Clear the session message after displaying it
    unset($_SESSION['message']);

    // Start output buffering at the very beginning
    ob_start(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../style/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <title>Food Ordering Application</title>
  <style>
    /* Inline CSS for form elements */
    .form-container {
      width: 50%;
      height: 80%;
      padding: 30px;
      background: #f9f9f9;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      margin: 0;
      float: left;
    }

    /* Input wrapper styling */
    .input-wrapper {
      display: flex;
      margin-bottom: 15px;
    }

    .input-wrapper label {
      width: 27%;
      font-weight: bold;
      margin-right: 10px;
    }

    .password-container {
      position: relative;
      width: 80%;
    }

    .password-container input {
      width: 100%;
      padding: 8px;
      font-size: 16px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }

    .password-container i {
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 18px;
      color: #555;
    }

    .submit-btn {
      width: 100%;
      padding: 10px;
      font-size: 18px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .submit-btn:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>

<!-- Main content -->
<div class="main-content" style="display: flex;">
  <div class="form-container">
    <h2>Change password</h2> 

    <form action="#" method="POST">
      <!-- Old Password Input -->
      <div class="input-wrapper">
        <label for="old-password">Old Password</label>
        <div class="password-container">
          <input type="password" id="old-password" name="old-password" required>
          <i class="fas fa-eye" id="old-password-eye" onclick="togglePasswordVisibility('old-password', 'old-password-eye')"></i>
        </div>
      </div>

      <!-- New Password Input -->
      <div class="input-wrapper">
        <label for="new-password">New Password</label>
        <div class="password-container">
          <input type="password" id="new-password" name="new-password" required>
          <i class="fas fa-eye" id="new-password-eye" onclick="togglePasswordVisibility('new-password', 'new-password-eye')"></i>
        </div>
      </div>

      <!-- Confirm Password Input -->
      <div class="input-wrapper">
        <label for="confirm-password">Confirm Password</label>
        <div class="password-container">
          <input type="password" id="confirm-password" name="confirm-password" required>
          <i class="fas fa-eye" id="confirm-password-eye" onclick="togglePasswordVisibility('confirm-password', 'confirm-password-eye')"></i>
        </div>
      </div> 

      <input type="submit" class="submit-btn" name="submit" value="Change">
    </form>
  </div>
</div>

<!-- PHP code for changing the password -->
<?php

// Check if form is submitted
if(isset($_POST['submit'])) {
    // Initialize variables
    $id = $_GET['id'];
    $old_password = mysqli_real_escape_string($conn, md5($_POST['old-password'])); 
    $new_password = mysqli_real_escape_string($conn, $_POST['new-password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm-password']);

    // For validation errors
    $errors = [];

    // Database connection (Assuming $conn is already initialized)
    // Query to check if the user exists with the given ID
    $sql = "SELECT * FROM tbl_admin WHERE id=$id";
    $res = mysqli_query($conn, $sql);

    if($res == TRUE) {
        // Check if the user exists
        $count = mysqli_num_rows($res);

        if($count == 1) {
            // Fetch user data
            $user = mysqli_fetch_assoc($res);
            $hashed_old_password = $user['password']; // Get the stored password hash from the database

            // Validate passwords
            if(strlen($new_password) < 8 || strlen($confirm_password) < 8) {
                $errors[] = "Password must be at least 8 characters long.";
            } else if($new_password != $confirm_password) {
                $errors[] = "Passwords don't match!";
            } else {
                // Check if the old password matches the stored hashed password
                if ($old_password == $hashed_old_password) {
                    // Hash the new password
                    $hashed_new_password = md5($new_password);

                    // Update the password in the database
                    $sql_update = "UPDATE tbl_admin SET password = '$hashed_new_password' WHERE id=$id";
                    $res_update = mysqli_query($conn, $sql_update);

                    if($res_update) {
                        $_SESSION['password-change'] = '<div class="success" id="session"> Password is updated successfully. </div>'; 
                        header("Location: " . SITEURL . 'admin/~/manage-admin.php');
                        exit();
                    } else {
                        $_SESSION['password-change'] = '<div class="error" id="session"> Failed to update the password! </div>';
                        header("Location: " . SITEURL . 'admin/~/manage-admin.php');
                        exit();
                    }
                } else {
                    // Old password is incorrect
                    $errors[] = "Old password is incorrect.";
                }
            }
        } else {
            // No user found
            $_SESSION['password-change'] = '<div class="error" id="session"> No user is found! </div>';;
            header("Location: " . SITEURL . 'admin/~/manage-admin.php');
            exit();
        }
    } else {
        // Error in query
        header("Location: " . SITEURL . 'admin/~/manage-admin.php');
        exit();
    }

    // If there are errors, store them in session
    if (!empty($errors)) {
        $_SESSION['message'] = ['type' => 'error', 'text' => implode("<br>", $errors)];
    }
}

// End output buffering
ob_end_flush();
?>

<!-- Footer -->
<?php include('../partials/footer.php'); ?>

<script>
// JavaScript for password visibility toggling
function togglePasswordVisibility(inputId, iconId) {
  var input = document.getElementById(inputId);
  var icon = document.getElementById(iconId);
  if (input.type === "password") {
    input.type = "text";
    icon.classList.remove('fa-eye');
    icon.classList.add('fa-eye-slash');
  } else {
    input.type = "password";
    icon.classList.remove('fa-eye-slash');
    icon.classList.add('fa-eye');
  }
}

// REMOVE SESSION MESSAGE AFTER THREE SECONDS
document.addEventListener("DOMContentLoaded", function() {
  setTimeout(function() {
    const messageDiv = document.getElementById("session");
    if(messageDiv) {
      messageDiv.style.display = "none";
    }
  }, 3000);
});
</script>
</body>
</html>
