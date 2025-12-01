<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../style/style.css">
  <title>Food Ordering Application</title>
</head>
<body>
    <!-- Menu -->
    <?php 
      include('../partials/menu.php'); 

      // Prevent user trying to go update-category page manually without id.
      if(!isset($_GET['id'])){
        header("Location: ".SITEURL.'admin/categories/manage-categories.php');
      }
    ?>


    <div class="main-content">
      <div class="form-container">
        <h2>Update category</h2>

        <?php
         // 1. Get the id of admin to be updated.
         $id = $_GET['id'];
      
         // 2. Create SQL query to select previous of id to be updated.
         $sql = "SELECT * FROM tbl_category WHERE id=$id";
     
         // 3. Execute the query.
         $res = mysqli_query($conn, $sql);
     
         // 4. Redirect to the admin page if deletion is successful
         if ($res == TRUE) {
          $count = mysqli_num_rows($res);

          if($count == 1){
            $row = mysqli_fetch_assoc($res);

            $title = $row['title'];
            $current_image = $row['image_name'];
            $featured = $row['featured'];
            $active = $row['active'];

          }else{
            header("Location: ".SITEURL.'admin/categories/manage-categories.php');
          }
         }
       ?>

        <!-- Display any session errors here -->
        <?php
        if(isset($_SESSION['errors'])){
            foreach($_SESSION['errors'] as $error) {
                echo "<div class='error' id='session'> $error </div> <br>";
            }
            // Clear the errors session
            unset($_SESSION['errors']);
        }
        ?>

        <form action="#" method="POST" enctype="multipart/form-data">
          <div class="input-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?php echo $title ?>" required>
          </div> <br>

          <div style="display: flex; gap:30px;">
            <!-- Current image will be displayed here -->
            <div>
             <img src="<?php echo SITEURL; ?>assets/images/category/<?php echo $current_image; ?>" alt="image" width="100px">
            </div>

            <!-- Choose image -->
             <div class="input-group" style="width: 75%">
              <label for="image">Select image: </label>
              <input type="file" id="image" name="image">
             </div>
          </div> <br>

          <div style="display:flex; align-items:center; gap: 10px;">
            <h5 style="margin-right: 20px;"> Featured: </h5>
            <input type="radio" id="featured" name="featured" value="Yes"  <?php if($featured == "Yes"){echo "checked";}?> > Yes
            <input type="radio" id="featured" name="featured" value="No" style="margin-left: 20px;" <?php if($featured == "No"){echo "checked";}?> > No
          </div> <br>

          <div style="display:flex; align-items:center; gap: 10px;">
            <h5 style="margin-right: 20px;"> Is Active: </h5>
            <input type="radio" id="active" name="active" value="Yes" <?php if($active == "Yes"){echo "checked";}?> > Yes
            <input type="radio" id="active" name="active" value="No" <?php if($active == "No"){echo "checked";}?>  style="margin-left: 20px;"> No
          </div> <br><br>

          <input type="submit" class="submit-btn" value="UPDATE" name="submit"></input>
        </form>
      </div>
    </div>

 <!-- CODE for updating -->
 <?php
    if(isset($_POST['submit'])){
      // 1. Get updated data
      $updated_title = $_POST['title'];
      $updated_image_name = $_FILES['image']['name'];
      $updated_featured = $_POST['featured'];
      $updated_active = $_POST['active'];

      // 2. Validate the inputs
      $errors = [];

      // Validate Title (Only letters and spaces)
      if (!preg_match("/^[a-zA-Z\s]+$/", $updated_title)) {
          // Regex to allow only letters and spaces
          $errors[] = "Title must only contain letters and spaces.";
      }

      // 3. If there are errors, store them in the session
      if(!empty($errors)){
          $_SESSION['errors'] = $errors;
          header("Location: ".$_SERVER['PHP_SELF']."?id=$id");
          exit(); // Stop the script to prevent further execution
      }

      // 4. If there are no errors, proceed with database update
      if(empty($errors)){

        // Upload the new image and remove the old image if necessary
        if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
          // Upload the new image.
          $source_path = $_FILES['image']['tmp_name'];
          $destination_path = "../../assets/images/category/".$updated_image_name;
  
          // Finally upload the image
          $upload = move_uploaded_file($source_path, $destination_path);
  
          // Check whether it is uploaded or not
          if($upload == false){
            $_SESSION['upload'] = '<div class="error" id="session"> Failed to upload the image category! </div>';
            header('location'.SITEURL.'admin/categories/manage-categories.php');
            die();
          }

          // Remove the current image
          $path = "../../assets/images/category/".$current_image;
          $remove = unlink($path);

          if($remove == false){
             $_SESSION['remove'] = "<div class='error' id='session'> Failed to remove the old image!, $current_image</div>"; 
            die();
          }
        } else {
            $updated_image_name = $current_image; // No new image uploaded, retain old one
        }

        // Update the category data in the database
        $sql = "UPDATE tbl_category SET
        title='$updated_title',
        image_name='$updated_image_name',
        featured='$updated_featured',
        active='$updated_active'
        WHERE id = $id";

        // Executing query and saving data into db
        $res = mysqli_query($conn, $sql) or die(mysqli_error());

         // If data is inserted successfully, redirect to admin page.
         if($res == TRUE){
          $_SESSION['update'] = '<div class="success" id="session"> Category is updated successfully!</div>'; 
          header("Location: ".SITEURL.'admin/categories/manage-categories.php');
        }else{
          $_SESSION['update'] = '<div class="error" id="session"> Failed to update category!</div>'; 
          header("Location: ".SITEURL.'admin/categories/manage-categories.php');
        }

      }
    }
  ?>
  

    <!-- Footer -->
    <?php include('../partials/footer.php'); ?>


    <script>
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
