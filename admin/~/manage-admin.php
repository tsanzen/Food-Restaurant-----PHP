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
     <!-- SESSION MESSAGES -->
     <?php
        // Login
        if(isset($_SESSION['login'])){
          echo $_SESSION['login'];
          unset($_SESSION['login']);
        }

        // Add
        if(isset($_SESSION['add'])){
          echo $_SESSION['add'];
          unset($_SESSION['add']);
        }

        if(isset($_SESSION['update'])){
          echo $_SESSION['update'];
          unset($_SESSION['update']);
        }

        // delete
        if(isset($_SESSION['delete'])){
          echo $_SESSION['delete'];
          unset($_SESSION['delete']);
        }

          // Password change
          if(isset($_SESSION['password-change'])){
            echo $_SESSION['password-change'];
            unset($_SESSION['password-change']);
          }
       ?>
       <br><br>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-right: 3%">
      <h1>Admins</h1>
      
      <!-- New Admin Button -->
      <a class="add-btn" href="add-admin.php">
        New <i class="fas fa-user-tie add-icon"></i>
        <span class="tooltip">New Admin</span>
      </a>
    </div>

    <!-- Table for displaying admins -->
    <table class="tbl-full">
      <tr>
        <th>S.N</th>
        <th>Name</th>
        <th>Username</th>
        <th>Actions</th>
      </tr>

      <?php
        // query to get all admin
        $sql = "SELECT * FROM tbl_admin";

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
              $full_name = $rows['full_name'];
              $username = $rows['username'];

              // display them in our table
              ?>
                <tr>
                  <td><?php echo $sn++?></td>
                  <td style="width: 45%;"><?php echo $full_name?></td>
                  <td style="width: 30%;"><?php echo $username?></td>
                  <td class='action-icons'>
                    <!-- Edit -->
                    <a href="<?php echo SITEURL?>admin/~/update-admin.php?id=<?php echo $id?>" class="action-icon edit">
                      <i class="fas fa-edit"></i>
                      <span class="tooltip">Edit</span>
                    </a>
                    <!-- Delete -->
                    <a href="<?php echo SITEURL?>admin/~/delete-admin.php?id=<?php echo $id?>" class="action-icon delete">
                      <i class="fas fa-trash-alt"></i>
                      <span class="tooltip">Del..</span>
                    </a>
                    <!-- Change password -->
                    <a href="<?php echo SITEURL?>admin/~/change-password.php?id=<?php echo $id?>" class="action-icon">
                      <i class="fas fa-lock change-icon"></i>
                      <span class="tooltip">Change password</span>
                    </a>
                  </td>
                </tr>
              <?php
            }
          } else {
            echo "<tr><td colspan='4'>No Admins Found</td></tr>";
          }
        } else {
          echo "<tr><td colspan='4'>Error fetching data</td></tr>";
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
