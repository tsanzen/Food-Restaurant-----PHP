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
  <!-- Menu -->
  <?php
   include('../partials/menu.php'); 

    // Prevent user trying to go update-admin page manually without id.
    if(!isset($_GET['id'])){
      header("Location: ".SITEURL.'admin/categories/manage-categories.php');
    }
   ?>

  <!-- Main content -->
  <div class="main-content">
    <div class="form-container">
      <h2>Update Admin</h2>

      <?php
         // 1. Get the id of admin to be updated.
         $id = $_GET['id'];
      
         // 2. Create SQL query to select previous info of id to be updated.
         $sql = "SELECT * FROM tbl_admin WHERE id=$id";
     
         // 3. Execute the query.
         $res = mysqli_query($conn, $sql);
     
         // 4. Redirect to the admin page if deletion is successful
         if ($res == TRUE) {
          $count = mysqli_num_rows($res);

          if($count == 1){
            $row = mysqli_fetch_assoc($res);

            $full_name = $row['full_name'];
            $username = $row['username'];


          }else{
            header("Location: ".SITEURL.'admin/~/manage-admin.php');
          }
         }
      ?>

      <form action="#" method="POST">
        <div class="input-group">
          <label for="full-name">Full Name</label>
          <input type="text" id="full-name" name="full-name" value="<?php echo $full_name ?>" required>
        </div>

        <div class="input-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" value="<?php echo $username ?>"  required>
        </div> <br>

        <input type="submit" class="submit-btn" name="submit" value="UPDATE"></input>
      </form>
    </div>
  </div>

  <!-- CODE for updating -->
  <?php
    if(isset($_POST['submit'])){
      // 1. Get updated data
      $updated_name = mysqli_real_escape_string($conn, $_POST['full-name']);
      $updated_username = mysqli_real_escape_string($conn, $_POST['username']);

      // 2. Validate the inputs
        $errors = [];

        // Validate Full Name (Only letters and spaces)
        if(empty($updated_name)) {
            $errors[] = "Full Name is required.";
        } elseif (!preg_match("/^[a-zA-Z\s]+$/", $updated_name)) {
            // Regex to allow only letters and spaces
            $errors[] = "Full Name must only contain letters and spaces.";
        }

        // Validate Username
        if(empty($updated_username)) {
            $errors[] = "Username is required.";
        } elseif(strlen($updated_username) < 5) {
            $errors[] = "Username must be at least 5 characters long.";
        }

      // 3. If there are no errors, proceed with database insertion
      if(empty($errors)){
        $sql = "UPDATE tbl_admin SET
        full_name = '$updated_name',
        username = '$updated_username' 
        WHERE id = $id
        ";

        // Executing query and saving data into db
        $res = mysqli_query($conn, $sql) or die(mysqli_error());

         // If data is inserted successfully redirect to admin page.
         if($res == TRUE){
          $_SESSION['update'] = '<div class="success" id="session"> Admin is updated successfully</div>';
          header("location:".SITEURL.'admin/~/manage-admin.php');
        }else{
          $_SESSION['delete'] = '<div class="error" id="session"> Failed to update the admin!</div>';
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
