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
     <h1>Orders</h1>
    </div>

    <!-- Table for displaying admins -->
    <table class="tbl-full">
      <tr>
        <th>S.N</th>
        <th>Order</th>
        <th>Status</th>
        <th>Customer</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Address</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
        <th>Action</th>
      </tr>

      <?php
        // query to get all orders, latest order at first. 
        $sql = "SELECT * FROM tbl_order ORDER BY id DESC";

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
              $ordered_food = $rows['food'];
              $order_status = $rows['status'];
              $customer_name = $rows['customer_name'];
              $customer_phone = $rows['customer_contact'];
              $customer_email = $rows['customer_email'];
              $customer_address = $rows['customer_address'];
              $price = $rows['price'];
              $qty = $rows['qty'];
              $total = $rows['total'];

              // display them in our table
              ?>
                <tr>
                  <td><?php echo $sn++?></td>
                  <td><?php echo $ordered_food?></td>
                  <td style="width: 5%;">
                    <?php
                      switch($order_status){
                        case "Ordered": 
                          echo "<div style='color: green; font-weight: semibold;'>$order_status</div>";
                          break;

                        case "On Delivery": 
                          echo "<div style='color: orange; font-weight: semibold;'>$order_status</div>";
                          break;

                        case "Delivered":
                          echo "<div style='color: green; font-weight: semibold;'>$order_status</div>";
                          break;
                        
                        default: 
                          echo "<div style='color: red; font-weight: semibold;'>$order_status</div>";
                      }
                    ?>
                  </td>
                  <td><?php echo $customer_name?></td>
                  <td><?php echo $customer_email?></td>
                  <td><?php echo $customer_phone?></td>
                  <td>
                    <?php
                       if(strlen($customer_address) > 21){
                        $some_address = substr($customer_address, 0, 21);
                        echo  $some_address . "...";
                       }else{
                        echo $customer_address;
                       }
                    ?>
                  </td>
                  <td><?php echo "₹".$price?></td>
                  <td><?php echo $qty?></td>
                  <td><?php echo "₹".$total?></td>

                  <td>
                    <!-- SPACE -->
                    <a style="margin: 0px 8px; "></a>

                    <!-- Edit -->
                    <a href="<?php echo SITEURL?>admin/orders/update-order.php?order_id=<?php echo $id?>" class="action-icon edit">
                      <i class="fas fa-edit"></i>
                      <span class="tooltip">Update</span>
                    </a>

                  </td>
                </tr>
              <?php
            }
          } else {
            echo "<tr><td colspan='11'>No Orders Found!</td></tr>";
          }
        } else {
          echo "<tr><td colspan='11'>Error fetching data</td></tr>";
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
