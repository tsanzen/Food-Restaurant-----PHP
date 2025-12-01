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
  <?php include('../partials/menu.php'); ?>

  <!-- Main content -->
  <div class="main-content">
      <!-- SESSION MESSAGE -->
       <?php
        // Add
        if(isset($_SESSION['add'])){
          echo $_SESSION['add'];
          unset($_SESSION['add']);
        }

        // Update
        if(isset($_SESSION['update'])){
          echo $_SESSION['update'];
          unset($_SESSION['update']);
        }

        // Delete
        if(isset($_SESSION['delete'])){
          echo $_SESSION['delete'];
          unset($_SESSION['delete']);
        }

        // Image remove failure
        if(isset($_SESSION['remove'])){
          echo $_SESSION['remove'];
          unset($_SESSION['remove']);
        }
       ?>
       <br><br>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-right: 3%">
     <h1>Categories</h1>
      
      <!-- New Category Button -->
      <a class="add-btn" href="add-category.php">
        New CT
        <span class="tooltip">Add Category</span>
      </a>
    </div>

    <!-- Table for displaying admins -->
    <table class="tbl-full">
      <tr>
        <th>S.N</th>
        <th>Title</th>
        <th>Image</th>
        <th>Featured</th>
        <th>Active</th>
        <th>Actions</th>
      </tr>

      <?php
        // query to get all admin
        $sql = "SELECT * FROM tbl_category";

        // query executing
        $res = mysqli_query($conn, $sql);

        // check whether the query is executed or not 
        if($res == TRUE){
          // count rows whether we have data or not 
          $count = mysqli_num_rows($res);

          // serial number variable
          $sn = 1;

          // check num of rows 
          if($count > 0){
            while($rows = mysqli_fetch_assoc($res)) {
              $id = $rows['id'];
              $title = $rows['title'];
              $image_name = $rows['image_name'];
              $featured = $rows['featured'];
              $active = $rows['active'];

              // display them in our table
              ?>
                <tr>
                  <td><?php echo $sn++?></td>
                  <td><?php echo $title?></td>
                  
                  <td>
                    <?php 
                      if($image_name !== ""){
                        // display the image
                        ?> 
                          <img src="<?php echo SITEURL; ?>assets/images/category/<?php echo $image_name; ?>" alt="image" height="90px" style="border-radius: 2px;">
                        <?php
                      }else{
                        echo "<div class='error'> Image not added. </div>";
                      }
                    ?>
                  </td>

                  
                  <td><?php echo $featured?></td>
                  <td><?php echo $active?></td>

                  <td>
                    <!-- Edit -->
                    <a href="<?php echo SITEURL?>admin/categories/update-category.php?id=<?php echo $id?>" class="action-icon edit">
                      <i class="fas fa-edit"></i>
                      <span class="tooltip">Edit</span>
                    </a>

                    <!-- SPACE -->
                    <a style="margin: 0px 15px; "></a>

                    <!-- Delete -->
                    <a href="<?php echo SITEURL?>admin/categories/delete-category.php?id=<?php echo $id?>&image_name=<?php echo $image_name?>" class="action-icon delete">
                      <i class="fas fa-trash-alt"></i>
                      <span class="tooltip">Del..</span>
                    </a>
                  </td>
                </tr>
              <?php
            }
          } else {
            echo "<tr><td colspan='6'>No Categories Found</td></tr>";
          }
        } else {
          echo "<tr><td colspan='6'>Error fetching data</td></tr>";
        }
      ?>
    </table>
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

  <!-- Footer -->
  <?php include('../partials/footer.php'); ?>

</body>
</html>
