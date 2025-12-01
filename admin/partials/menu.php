<?php include('../../config/constants.php'); ?>

<?php
  // Authorization
  include('login-check.php');
?>

 <!-- Menu -->
 <div class="menu">
      <div class="left-hand">
        <img src="../../assets/images/logo.png" alt="Logo">
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
         <a href="../logout.php" style="margin-left: 45px; background-color: red; padding: 10px 20px; color: white; border-radius: 3px; text-decoration: none;">Logout</a>
        </div>
      </div>

  </div>