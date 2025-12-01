<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style/style.css">
  <title>Food Ordering Application</title>
</head>
<body>

  <?php
   // Constants
   include('../config/constants.php');

    // Authorization
    if(!isset($_SESSION['user'])){
      header('location:'.SITEURL.'admin/login.php');
    }

   
  ?>

  <!-- Menu -->
  <div class="menu">
        <div class="left-hand">
          <img src="../assets/images/logo.png" alt="Logo">
        </div>

        <div style="display: flex">
          <div class="right-hand text-center">
            <ul>
              <li><a href="<?php echo SITEURL?>admin/dashboard.php">Home</a></li>
              <li><a href="<?php echo SITEURL?>admin/foods/manage-food.php">Food</a></li>
              <li><a href="<?php echo SITEURL?>admin/categories/manage-categories.php">Category</a></li>
              <li><a href="<?php echo SITEURL?>admin/orders/manage-order.php">Orders</a></li>
              <li><a href="<?php echo SITEURL?>admin/~/manage-admin.php">Admin</a></li> 
            </ul>
          </div>
          <div>
          <a href="logout.php" style="margin-left: 45px; background-color: red; padding: 10px 20px; color: white; border-radius: 3px; text-decoration: none;">Logout</a>
          </div>
        </div>
    </div>

  <!-- Main content -->
   <div class="main-content">
     <h1>Dashboard</h1>
     <br><br>

     <div class="cols">
      <div class="col-4">
        <h1>
          <?php
            $ctg_query = "SELECT * FROM tbl_category";
            $ctg_res = mysqli_query($conn, $ctg_query);
            $ctg_count = mysqli_num_rows($ctg_res);

            if($ctg_count > 0){
              echo $ctg_count;
            }else{
              echo "0";
            }
          ?>
        </h1>
        Categories
      </div>

      <div class="col-4">
      <h1>
          <?php
            $foods_query = "SELECT * FROM tbl_food";
            $food_res = mysqli_query($conn, $foods_query);
            $food_count = mysqli_num_rows($food_res);

            if($food_count > 0){
              echo $food_count;
            }else{
              echo "0";
            }
          ?>
        </h1>
          Foods
      </div>

      <div class="col-4">
        <h1>
            <?php
              $orders_query = "SELECT * FROM tbl_order";
              $orders_res = mysqli_query($conn, $orders_query);
              $order_count = mysqli_num_rows($orders_res);

              if($order_count > 0){
                echo $order_count;
              }else{
                echo "0";
              }
            ?>
        </h1>     
        Orders
      </div>

      <div class="col-4">
          <?php
              $sql = "SELECT SUM(total) AS Total FROM tbl_order WHERE status='Delivered'";
              $res = mysqli_query($conn, $sql);

              $rows = mysqli_fetch_assoc($res);
              $total = $rows['Total'];

              if($total > 0){
                echo "<h1 style='color: green; font-weight: semibold;'>₹$total</h1>";
              }else{
                echo "<h1 style='color: red; font-weight: semibold;'>$0</h1>";
              }
         ?>
        Generated Revenue
      </div>
     </div>

     <div class="clearfix"></div>
   </div>

  <!-- Footer -->
  <?php
    include('partials/footer.php')
  ?>

</body>
</html>