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
    <?php include('../partials/menu.php'); ?>


    <div class="main-content">
      <div class="form-container">
        <h2>Update Food</h2>

          <!-- Display any session errors here -->
          <?php
            // Start output buffering at the very beginning
            ob_start(); 

            if(isset($_SESSION['upload'])){
              echo $_SESSION['upload'] . "<br>";
              unset($_SESSION['upload']);
            } 

            if(isset($_SESSION['errors'])){
              foreach($_SESSION['errors'] as $error) {
                  echo "<div class='error' id='session'> $error </div> <br>";
              }
              // Clear the errors session
              unset($_SESSION['errors']);
            }
          ?>

                  

          <!--  Get food to update details for autofilling form -->
          <?php
            // If there's a food id to update
            if(isset($_GET['id'])){
              // 1. Get the id of the food to update .
              $food_id = $_GET['id'];
            
              // 2. Create a sql query for selecting it & execute.
              $sql = "SELECT * FROM tbl_food WHERE id=$food_id";
              $res = mysqli_query($conn, $sql);

              // 3. Extract the details out.
              if($res == TRUE){
                $count = mysqli_num_rows($res);

                // Check if food to update is available or not.
                if($count > 0){
                  $row = mysqli_fetch_assoc($res);

                  // Details 
                  $title = $row['title'];
                  $description = $row['description'];
                  $current_image = $row['image_name'];
                  $current_category = $row['category_id'];
                  $price = $row['price'];
                  $featured = $row['featured'];
                  $active = $row['active'];
                }
            }
            // Prevent user trying to go update-category page manually without id.
            }else{
              header("Location: ".SITEURL.'admin/foods/manage-food.php');
            }
            

          ?>

        <form action="#" method="POST" enctype="multipart/form-data">
          <div class="input-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" value="<?php echo $title?>" required>
          </div>

          <div class="input-group">
            <label for="description">Food Description</label>
            <textarea id="description" name="description" rows="5" required style="padding: 10px; width: 50%" ><?php echo $description?></textarea>
          </div>

          <div class="input-group">
            <label for="price">Price</label>
            <input type="number" id="price" name="price" value="<?php echo $price?>" required>
          </div>
          

          <div style="display: flex; gap:30px;">
            <!-- Current image will be displayed here -->
            <div>
             <img src="<?php echo SITEURL; ?>assets/images/food/<?php echo $current_image; ?>" alt="image" width="100px">
            </div>

            <!-- Choose image -->
             <div class="input-group" style="width: 75%">
              <label for="image">Select image: </label>
              <input type="file" id="image" name="image">
             </div>
          </div> <br>

          
          <div class="input-group">
            <label for="category">Category</label>
            <select style="width: 50%; padding: 5px; color: gray; font-size: 16px;" name="category"  value="<?php echo $category?>">
              <?php
                // Create SQL query to display available categories
                $sql = "SELECT * FROM tbl_category WHERE active='Yes'";
                $res = mysqli_query($conn, $sql);

                // Count rows to check whether we have categories or not.
                $count = mysqli_num_rows($res);

                if($count > 0){
                  // IF WE HAVE CATEGORIES
                  while($row = mysqli_fetch_assoc($res)){
                    // Get the details of each categories row
                    $id = $row['id'];
                    $title = $row['title'];

                    // Display on dropdown
                    ?>
                      <option <?php if($current_category == $id) echo "selected"; ?> value="<?php echo $id; ?>"><?php echo $title; ?></option>
                    <?php
                  }
                }else{
                  //IF WE DONOT HAVE CATEGORIES
                  ?>
                    <option value="0">No Categories is Found!</option>
                  <?php
                }
              ?>
            </select>
          </div><br>

         
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
          <input type="submit" class="submit-btn" name="submit" value="UPDATE"></input>
        </form>
      </div>
    </div>

    <?php
      if(isset($_POST['submit'])){
        // 1. Get values from the form.
        $updated_title = trim($_POST['title']);
        $updated_desc = trim($_POST['description']);
        $updated_price = trim($_POST['price']);
        $updated_category = trim($_POST['category']);
        $updated_image_name = $_FILES['image']['name'];
        $updated_featured = "";
        $updated_active = "";
          // For radio input type we need to check whether the button is selected or not.
          if(isset($_POST['featured'])){
            $updated_featured = trim($_POST['featured']);
          }else{
            $updated_featured = "No";
          }

          if(isset($_POST['active'])){
            // Get the value
            $updated_active = trim($_POST['active']);
          }else{
            $updated_active = "No";
          }

          // 2. Validate the inputs
          $errors = [];

          // Validate Title (Only letters and spaces)
          if (!preg_match("/^[a-zA-Z\s]+$/", $updated_title)) {
              // Regex to allow only letters and spaces
              $errors[] = "Title must only contain letters and spaces.";
          }

          if (!preg_match("/^[a-zA-Z\s]+$/", $updated_desc)) {
            // Regex to allow only letters and spaces
            $errors[] = "Description must only contain letters and spaces.";
          }

          // If there are errors, store them in the session
          if(!empty($errors)){
              $_SESSION['errors'] = $errors;
              header("Location: ".$_SERVER['PHP_SELF']."?id=$id");
              exit(); // Stop the script to prevent further execution
          }

          // 3. If there are no errors, proceed with database update
          if(empty($errors)){
            // Check whether image is selected or not & set the value for img-name
            if(isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
              // to upload the image, we need image name, src_path, and its destination_path.
              $source_path = $_FILES['image']['tmp_name'];
              $destination_path = "../../assets/images/food/".$updated_image_name;
  
              /**
              
              // Auto rename our image like "category-1, category-2 etc".
              $extension = explode('.', $image_name);
  
              $prev_images = "SELECT COUNT(DISTINCT image_name) FROM tbl_category";
              $res = mysqli_query($conn, $prev_images) or die(mysqli_error());
              echo $prev_images;
              die();
  
              **/
  
              // Finally upload the image
              $upload = move_uploaded_file($source_path, $destination_path);
  
                // Check whether it is uploaded or not
                if($upload == false){
                  $_SESSION['upload'] = '<div class="error" id="session"> Failed to upload the food image! </div>';
                  header('location'.SITEURL.'admin/foods/manage-food.php');
                  die();
                }

                // Remove the current image
                $path = "../../assets/images/food/".$current_image;
                $remove = unlink($path);

                if($remove == false){
                  $_SESSION['remove'] = "<div class='error' id='session'> Failed to remove the old image!, $current_image</div>"; 
                  die();
                }
              } else {
                $updated_image_name = $current_image; // No new image uploaded, retain old one
              }
            }
  
            
          $sql = "UPDATE tbl_food SET
            title='$updated_title',
            description='$updated_desc',
            price=$updated_price,
            image_name='$updated_image_name',
            category_id=$updated_category,
            featured='$updated_featured',
            active='$updated_active'
          ";
            // Executing the query
            $res = mysqli_query($conn, $sql) or die(mysqli_error());
  
            // check whether the query is executed or not.
            if($res == true){
              $_SESSION['update'] = '<div class="success" id="session"> Food is updated successfully. </div>';
  
              // Redirect
              header("location:".SITEURL.'admin/foods/manage-food.php');
            }else{
              $_SESSION['update'] = '<div class="error" id="session"> Failed to updated the food! </div>';
  
              // Redirect
              header("location:".SITEURL.'admin/foods/manage-food.php');
            }
          }          
        

        // End output buffering
        ob_end_flush();
    ?>


    <!--  REMOVE SESSION MESSAGE AFTER THREE SECONDS -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
          setTimeout(function() {
            const messageDiv = document.getElementById("session");
            if(messageDiv) {
              messageDiv.style.display = "none";
            }
              }, 3000);
        });
    </script>

    <!-- Footer -->
    <?php include('../partials/footer.php'); ?>
</body>
</html>