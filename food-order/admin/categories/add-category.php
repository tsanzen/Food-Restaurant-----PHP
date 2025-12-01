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
        <h2>New category</h2>
        <form action="#" method="POST" enctype="multipart/form-data">
          <div class="input-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" placeholder="Title of the category" required>
          </div>

          <div class="input-group">
            <label for="image">Select image: </label>
            <input type="file" id="image" name="image" required>
          </div>


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

          <input type="submit" class="submit-btn" name="submit"></input>
        </form>
      </div>
    </div>

    <?php
      if(isset($_POST['submit'])){
        // 1. Get values from the form.
        $title = mysqli_real_escape_string($conn, trim($_POST['title']));
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

          // Check whether image is selected or not & set the value for img-name
          if(isset($_FILES['image']['name'])){
            // to upload the image, we need image name, src_path, and its destination_path.
            $image_name = $_FILES['image']['name'];
            $source_path = $_FILES['image']['tmp_name'];
            $destination_path = "../../assets/images/category/".$image_name;

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
              header('location'.SITEURL.'admin/categories/manage-categories.php');
            }
          }
          

        // 2. Insert into the DB
        $sql = "INSERT INTO tbl_category SET
          title='$title',
          image_name='$image_name',
          featured='$featured',
          active='$active'
        ";
          // Executing the query
          $res = mysqli_query($conn, $sql) or die(mysqli_error());

          // check whether the query is executed or not.
          if($res == true){
            $_SESSION['add'] = '<div class="success" id="session"> New Category is created successfully. </div>';

            // Redirect
            header("location:".SITEURL.'admin/categories/manage-categories.php');
          }else{
            $_SESSION['add'] = '<div class="error" id="session"> Failed to add new category! </div>';

            // Redirect
            header("location:".SITEURL.'admin/categories/manage-categories.php');
          }
        }
    ?>



    <!-- Footer -->
    <?php include('../partials/footer.php'); ?>
</body>
</html>