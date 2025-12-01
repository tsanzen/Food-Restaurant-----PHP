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
        <h2>New Food</h2>

          <!-- Display any session errors here -->
          <?php
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

        <form action="#" method="POST" enctype="multipart/form-data">
          <div class="input-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" placeholder="Title of the food..." required>
          </div>

          <div class="input-group">
            <label for="description">Food Description</label>
            <textarea id="description" name="description" rows="5" placeholder="Description of the food..." required style="padding: 10px; width: 50%" > </textarea>
          </div>

          <div class="input-group">
            <label for="price">Price</label>
            <input type="number" id="price" name="price" step="0.01" required>
          </div>
          

          <div class="input-group">
            <label for="image">Select image: </label>
            <input type="file" id="image" name="image" required>
          </div>

          
          <div class="input-group">
            <label for="category">Category</label>
            <select style="width: 50%; padding: 5px; color: gray; font-size: 16px;" name="category">
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
                      <option value="<?php echo $id; ?>"><?php echo $title; ?></option>
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
            <input type="radio" id="featured" name="featured" value="Yes"> Yes
            <input type="radio" id="featured" name="featured" value="No" style="margin-left: 20px;"> No
          </div> <br>

          <div style="display:flex; align-items:center; gap: 10px;">
            <h5 style="margin-right: 20px;"> Is Active: </h5>
            <input type="radio" id="active" name="active" value="Yes"> Yes
            <input type="radio" id="active" name="active" value="No" style="margin-left: 20px;"> No
          </div> <br><br>

          <input type="submit" class="submit-btn" name="submit" value="Add"></input>
        </form>
      </div>
    </div>

    <?php
      if(isset($_POST['submit'])){
        // 1. Get values from the form.
        $title = mysqli_real_escape_string($conn, trim($_POST['title']));
        $desc = mysqli_real_escape_string($conn, trim($_POST['description']));
        $price = mysqli_real_escape_string($conn, trim($_POST['price']));
        $category = mysqli_real_escape_string($conn, trim($_POST['category']));
        $image_name = "";
        $featured = "";
        $active = "";
          // For radio input type we need to check whether the button is selected or not.
          if(isset($_POST['featured'])){
            $featured = trim($_POST['featured']);
          }else{
            $featured = "No";
          }

          if(isset($_POST['active'])){
            // Get the value
            $active = trim($_POST['active']);
          }else{
            $active = "No";
          }

          // 2. Validate the inputs
          $errors = [];

          // Validate Title (Only letters and spaces)
          if (!preg_match("/^[a-zA-Z\s]+$/", $title)) {
              // Regex to allow only letters and spaces
              $errors[] = "Title must only contain letters and spaces.";
          }

          if (!preg_match("/^[a-zA-Z\s]+$/", $desc)) {
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
            if(isset($_FILES['image']['name'])){
              // to upload the image, we need image name, src_path, and its destination_path.
              $image_name = $_FILES['image']['name'];
              $source_path = $_FILES['image']['tmp_name'];
              $destination_path = "../../assets/images/food/".$image_name;

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
                $_SESSION['upload'] = '<div class="error" id="session"> Failed to upload the image category! </div>';
                header('location'.SITEURL.'admin/foods/add-food.php');
              }
            }
            

          // 2. Insert into the DB
          $sql = "INSERT INTO tbl_food SET
            title='$title',
            description='$desc',
            price=$price,
            image_name='$image_name',
            category_id=$category,
            featured='$featured',
            active='$active'
          ";
            // Executing the query
            $res = mysqli_query($conn, $sql) or die(mysqli_error());

            // check whether the query is executed or not.
            if($res == true){
              $_SESSION['add'] = '<div class="success" id="session"> New food is added successfully. </div>';

              // Redirect
              header("location:".SITEURL.'admin/foods/manage-food.php');
            }else{
              $_SESSION['add'] = '<div class="error" id="session"> Failed to add new food! </div>';

              // Redirect
              header("location:".SITEURL.'admin/foods/manage-food.php');
            }
          }
        }
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