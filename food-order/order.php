<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!-- Important to make website responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Website</title>

    <!-- Link our CSS file -->
    <link rel="stylesheet" href="style/frontend.css">
</head>

<body>
     <!-- Navbar  -->
     <?php include('partials-front/menu.php');?>

        
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

    <!-- Food search-->
    <section class="food-search">
        <div class="container">
            <h2 class="text-center text-white">Fill this form to confirm your order.</h2>

            <!-- PHP CODE of food to order details  -->
            <?php
               if(isset($_GET['food_id'])){
                $food_id = $_GET['food_id'];

                // QUERY to select food id details from DB.
                $food_selecting_sql = "SELECT * FROM tbl_food WHERE id='$food_id'";
                $food_res = mysqli_query($conn, $food_selecting_sql) or die(mysqli_error());;
                
                // Check the result if there's a food.
                if($food_res == TRUE){
                    $count = mysqli_num_rows($food_res);

                    // There's food to order
                    if($count == 1){
                        $food_row = mysqli_fetch_assoc($food_res); 
                        
                        // Food to order details 
                        $food_title = $food_row['title'];
                        $food_price = $food_row['price'];
                        $food_image = $food_row['image_name'];
                    }
                // No food to order id passed, redirect to home.
                }else{
                    header("Location: ".SITEURL);
                }

               }


            ?>

            <form action="#" class="order" method="POST">
                <fieldset>
                    <legend>Selected Food</legend>

                    <div class="food-menu-img">
                        <img src="<?php echo SITEURL;?>assets/images/food/<?php echo $food_image;?>" alt="Image" height="100%" width="100%" style="border-radius: 5px;">
                    </div>
    
                    <div class="food-menu-desc">
                        <h3><?php echo $food_title; ?></h3>
                        <p class="food-price">₹<?php echo $food_price ?></p>

                        <div class="order-label">Quantity</div>
                        <input type="number" name="qty" class="input-responsive" value="1" required>
                        
                    </div>

                </fieldset>
                
                <fieldset>
                    <legend>Delivery Details</legend>
                    <div class="order-label">Full Name</div>
                    <input type="text" name="full-name" placeholder="E.g. Jason Statham" class="input-responsive" required>

                    <div class="order-label">Phone Number</div>
                    <input type="tel" name="contact" placeholder="E.g. 1899xxxxxx" class="input-responsive" required>

                    <div class="order-label">Email</div>
                    <input type="email" name="email" placeholder="E.g. hi@j.statham.com" class="input-responsive" required>

                    <div class="order-label">Address</div>
                    <textarea name="address" rows="10" placeholder="E.g. Street, City, Country" class="input-responsive" required></textarea>

                    <input type="submit" name="submit" value="Confirm" class="btn-order">
                </fieldset>
            </form>
        </div>
    </section>
    <!-- fOOD sEARCH Section Ends Here -->

    <!-- social Section Starts Here -->
    <section class="social">
        <div class="container text-center">
            <ul>
                <li>
                    <a href="#"><img src="https://img.icons8.com/fluent/50/000000/facebook-new.png"/></a>
                </li>
                <li>
                    <a href="#"><img src="https://img.icons8.com/fluent/48/000000/instagram-new.png"/></a>
                </li>
                <li>
                    <a href="#"><img src="https://img.icons8.com/fluent/48/000000/twitter.png"/></a>
                </li>
            </ul>
        </div>
    </section>
    <!-- social Section Ends Here -->


    <!-- CODE FOR SAVING THE ORDER TO THE DB -->
    <?php
       if(isset($_POST['submit'])){
         //1. Get the customer info 
         $qty = $_POST['qty'];
         $total = $food_price * $qty;  
 
         $customer_name = $_POST['full-name'];
         $customer_phone = $_POST['contact'];
         $customer_email = $_POST['email'];
         $customer_address = $_POST['address'];

         $status = "Ordered";
         $order_date = date("Y-m-d h:i:sa"); //e.g-> 2025/12/31 9:49:24AM.
 
         //2. Validate inputs.
         $errors = [];
 
         // Validate name (only letters and spaces)
if (!preg_match("/^[a-zA-Z\s]+$/", $customer_name)) {
    $errors[] = "Name must only contain letters and spaces.";
}

// Validate phone (Only digits, exactly 10 digits, no spaces or symbols)
if (!preg_match("/^\d{10}$/", $customer_phone)) {
    // Regex to allow phone numbers in the format 9876543210
    $errors[] = "Phone must be exactly 10 digits with no spaces or symbols.";
}
        
 
 
         // 3. If there are errors, store them in the session
         if(!empty($errors)){
             $_SESSION['errors'] = $errors;
             header("Location: ".$_SERVER['PHP_SELF']."?food_id=$food_id");
             exit(); // Stop the script to prevent further execution
         }
 
         //3. Save to the DB.
         if(empty($errors)){

            // SQL for inserting order.
            $order_saving = "INSERT INTO tbl_order SET
            food='$food_title',
            price='$food_price',
            qty='$qty',
            total='$total',
            order_date='$order_date',
            status='$status',
            customer_name='$customer_name',
            customer_contact='$customer_phone',
            customer_email='$customer_email',
            customer_address='$customer_address'
         ";

            $order_res = mysqli_query($conn, $order_saving);

            if($order_res == TRUE){
                $_SESSION['order'] = "<br> <div class='success' id='session'>Your order was successfully submitted! </div>";
                header("Location: ".SITEURL);


            // Failed to save order session.
            }else{
                $_SESSION['order'] = "<div class='error' id='session'>Failed to submit your order!, please try again. </div>";
                header("Location: ".SITEURL);
            }
         }
       }

        // End output buffering
         ob_end_flush();
    ?>

    <!-- Footer -->
    <?php include('partials-front/footer.php')?>

    
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