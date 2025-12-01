<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../style/style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <title>Food Ordering Application</title>
</head>
<body>
  <!-- Menu  -->
  <?php include('../partials/menu.php'); ?>


  <!-- Main content -->
  <div class="main-content">
    <div class="form-container">
      <h2>New Admin</h2>
      <form action="#" method="POST">
        <div class="input-group">
          <label for="full-name">Full Name</label>
          <input type="text" id="full-name" name="full-name" placeholder="Full name " required>
        </div>

        <div class="input-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="Username" required>
        </div>

        <div class="input-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Password" required>
        </div>

          <input type="submit" class="submit-btn" name="submit"></input>
        </form>
      </div>
    </div>
  </div>


  
  <!-- Process the value from the form and save it into the Database  -->

  <?php
    // Check whether the submit button is clicked or not 
    if(isset($_POST['submit'])){

        // 1st -> Get the data from the form
        $full_name = mysqli_real_escape_string($conn, trim($_POST['full-name']));
        $username = mysqli_real_escape_string($conn, trim($_POST['username']));
        $password = mysqli_real_escape_string($conn, trim($_POST['password']));

        // 2nd -> Validate the inputs
        $errors = [];

        // Validate Full Name (Only letters and spaces)
        if(empty($full_name)) {
            $errors[] = "Full Name is required.";
        } elseif (!preg_match("/^[a-zA-Z\s]+$/", $full_name)) {
            // Regex to allow only letters and spaces
            $errors[] = "Full Name must only contain letters and spaces.";
        }

        // Validate Username
        if(empty($username)) {
            $errors[] = "Username is required.";
        } elseif(strlen($username) < 5) {
            $errors[] = "Username must be at least 5 characters long.";
        }

        // Validate Password
        if(empty($password)) {
            $errors[] = "Password is required.";
        } elseif(strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long.";
        }

        // If there are no errors, proceed with database insertion
        if(empty($errors)) {
            // 3rd -> Sanitize inputs (to avoid XSS attacks and other issues)
            $full_name = htmlspecialchars($full_name);
            $username = htmlspecialchars($username);
            $password = htmlspecialchars($password);

            // Hash the password for security
            $hashed_password = md5($password);

            //SQL-QUERY for inserting.
            $sql = "INSERT INTO tbl_admin SET
              full_name='$full_name',
              username='$username',
              password='$hashed_password'
            ";

            // Executing query and saving data into db
            $res = mysqli_query($conn, $sql) or die(mysqli_error());

            // Check if data is inserted successfully or not.
            if($res == TRUE){
              $_SESSION['add'] = '<div class="success" id="session"> Admin added successfully. </div>';

              // Redirect
              header("location:".SITEURL.'admin/~/manage-admin.php');
            }else{
              $_SESSION['add'] = '<div class="error" id="session"> Failed to add admin! </div>';
              
              // Redirect
              header("location:".SITEURL.'admin/~/manage-admin.php');
            }
        } else {
              // Display errors
              foreach($errors as $error) {
                  echo "<p style='color: red; padding:10px 30px;'>$error</p>";
              }

            echo "<p style='margin-bottom: 45px;'></p>";
        }
    }
?>

  <!-- Footer -->
  <?php include('../partials/footer.php'); ?>

</body>
</html>
