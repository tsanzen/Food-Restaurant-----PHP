<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Important to make website responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Website</title>

    <!-- Link our CSS files -->
    <link rel="stylesheet" href="../../style/frontend.css">
    <link rel="stylesheet" href="../../style/style.css">

</head>

<body>
     <!-- Navbar  -->
     <?php include('../partials/menu.php');?>

     <!-- Prevent user trying to go delete-category page manually without id and image_name. -->
     <?php
       if(!isset($_GET['order_id'])){
        header("Location: ".SITEURL.'admin/orders/manage-order.php');
    }
     ?>

        
        <!-- Display any session errors here -->
        <?php
           // Start output buffering at the very beginning
           ob_start(); 

            if(isset($_SESSION['errors'])){
                foreach($_SESSION['errors'] as $error) {
                    echo "<div class='error' id='session'> $error </div> <br>";
                }
                // Clear the errors session
                unset($_SESSION['errors']);
            }
        ?>

    <div class="main-content">
        <div class="form-container">
            <!-- PHP CODE of food to order details  -->
            <?php
               if(isset($_GET['order_id'])){
                $order_id = $_GET['order_id'];

                // QUERY to select food id details from DB.
                $order_retrieving = "SELECT * FROM tbl_order WHERE id='$order_id'";
                $res = mysqli_query($conn, $order_retrieving) or die(mysqli_error());;
                
                // Check the result if there's a food.
                if($res == TRUE){
                    $count = mysqli_num_rows($res);

                    // There's food to order
                    if($count == 1){
                        $order_row = mysqli_fetch_assoc($res); 
                        
                        // Food to order details 
                        $ordered_food = $order_row['food'];
                        $status = $order_row['status'];
                        $order_price = $order_row['price'];
                        $ordered_qty = $order_row['qty'];
                        $customer_name = $order_row['customer_name'];
                        $customer_phone = $order_row['customer_contact'];
                        $customer_email = $order_row['customer_email'];
                        $customer_address = $order_row['customer_address'];
                    }
                // No food to order id passed, redirect to home.
                }else{
                    header("Location: ".SITEURL);
                }

               }


            ?>

            <form action="#" style="width: 80%;" method="POST">
               <div style="display: flex; gap: 2em">
                  <div style="width: 100%;">
                      <fieldset>
                        <legend>Selected Food</legend>
                        <div class="order-label">Ordered Food</div>
                        <input type="text" value="<?php echo $ordered_food?>" style="color: gray; font-size: 14px;" class="input-responsive" readonly> <br><br>

                        <div class="order-label">Order price</div>
                        <input type="text" value="<?php echo $order_price?>" style="color: gray; font-size: 14px;" class="input-responsive" readonly> <br><br>

                        <div class="order-label">Quantity</div>
                        <input type="number" name="qty" class="input-responsive" value="<?php echo $ordered_qty?>" class="input-responsive" required> 

                        <!-- Status to update -->
                        <div class="order-label">Order Status</div>
                        <select style="width: 100%; padding: 5px; margin-top: 14px; color: gray; font-size: 16px;" name="order-status" required>
                          <option value="Ordered" <?php if($status == "Ordered") echo "selected"?>>Ordered</option>
                          <option value="Delivered" <?php if($status == "Delivered") echo "selected"?>>Delivered</option>
                          <option value="On Delivery" <?php if($status == "On Delivery") echo "selected"?>>On Delivery</option>
                          <option value="Cancelled" <?php if($status == "Cancelled") echo "selected"?>>Cancelled</option>
                        </select>
                    </fieldset>
                  </div>

                  <div style="width: 100%;">
                    <fieldset>
                      <legend>Delivery Details</legend>
                      <div class="order-label">Customer</div>
                      <input type="text" value="<?php echo $customer_name?>" style="color: gray; font-size: 14px;" class="input-responsive" readonly>

                      <div class="order-label">Contact</div>
                      <input type="tel" value="<?php echo $customer_phone?>"  style="color: gray; font-size: 14px;" class="input-responsive" readonly>

                      <div class="order-label">Email</div>
                      <input type="email" value="<?php echo $customer_email?>" style="color: gray; font-size: 14px;" class="input-responsive" readonly>

                      <div class="order-label">Address</div>
                      <textarea name="address" rows="3" value="<?php echo $customer_address?>"  class="input-responsive" ></textarea>
                    </fieldset>
                  </div>
               </div>

              <input type="submit" name="submit" value="Update" class="submit-btn" style="width: 95%; margin: 0 30px;">

            </form>
        </div>
    </div>


    <!-- CODE FOR SAVING THE ORDER TO THE DB -->
    <?php
       if(isset($_POST['submit'])){
         //1. Get the customer info 
         $qty = $_POST['qty'];
         $updated_total = $order_price * $qty;  
         $updated_status = $_POST['order-status'];
 
      
         //2. Updated table.
         if(empty($errors)){

            // SQL for inserting order.
            $updated_order= "UPDATE tbl_order SET
            qty='$qty',
            status='$updated_status', 
            total='$updated_total'
            WHERE id=$order_id
         ";

            $order_res = mysqli_query($conn, $updated_order);

            if($order_res == TRUE){
                $_SESSION['update'] = "<div class='success' id='session'>Order is updated successfully. </div>";
                header("Location: ".SITEURL.'admin/orders/manage-order.php');


            // Failed to save order session.
            }else{
                $_SESSION['update'] = "<div class='error' id='session'>Failed to update order!, please try again. </div>";
                header("Location: ".SITEURL.'admin/orders/manage-order.php');
            }
         }
       }

        // End output buffering
         ob_end_flush();
    ?>

    <!-- Footer -->
    <?php include('../partials/footer.php')?>

    
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