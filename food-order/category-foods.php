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

   <!-- Fetching category details -->
   <?php 
        //  Prevent entering this page mannually without passing category_id
        if(!isset($_GET['category_id'])){
            header("Location: ".SITEURL.'index.php');
        }else{
            // Get category id
            $ctg_id = $_GET['category_id'];

            // SQL-QUERY to  get category title
            $sql = "SELECT title FROM tbl_category WHERE id=$ctg_id";
            $res = mysqli_query($conn, $sql);

            // Store the result in this row 
            $row = mysqli_fetch_assoc($res);

            // Get category details
            $title = $row['title'];
        }
   ?>

    <!-- FOOD SEARCH -->
    <section class="food-search text-center">
        <div class="container">
            <h2>Foods on <a href="#" class="text-white"><?php echo "'$title'";?></a></h2>
        </div>
    </section>



    <!-- fOOD MEnu Section Starts Here -->
    <section class="food-menu">
        <div class="container">
            <h2 style="margin-left: 15px;"><?php echo $title;?></h2>

            <!-- Displaying all foods from DB based on the category -->
            <?php
                // Query select all foods & execute.
                $sql_select_ctg_foods = "SELECT * FROM tbl_food WHERE category_id='$ctg_id'";
                $res2 = mysqli_query($conn, $sql_select_ctg_foods);

                // If query result is successfull
                if($res == TRUE){
                    // variable to store num of foods available.
                    $count = mysqli_num_rows($res2);

                    // If there're foods 
                    if($count > 0){
                        // Loop through them and display.
                        while($row2 = mysqli_fetch_assoc($res2)){
                            $id = $row2['id'];
                            $food_title = $row2['title'];
                            $food_price = $row2['price'];
                            $food_desc = $row2['description'];
                            $image = $row2['image_name'];


                            ?>
                             <div class="food-menu-box">
                                <div class="food-menu-img">
                                  <img src="<?php echo SITEURL;?>assets/images/food/<?php echo $image;?>" alt="Image" class="img-responsive img-curve">
                                </div>

                                <div class="food-menu-desc">
                                    <h4><?php echo $food_title?></h4>
                                    <p class="food-price"><?php echo "₹".$food_price?></p>
                                    <p class="food-detail">
                                        <?php echo $food_desc?>
                                    </p>
                                    <br>

                                    <a href="<?php echo SITEURL;?>order.php?food_id=<?php echo $id;?>" class="btn-order">Order now</a>
                                </div>
                             </div>
                            <?php
                        }
                    //If there're no foods at all:
                    }else{
                        echo "<div style=' width: 100%; background-color: red; padding: 20px; color: white; border: none;'>Selected category foods are out of the restaurant!</div>";
                    }
                }
             ?>

            <div class="clearfix"></div>
        </div>

    </section>

    <!-- Scoials -->
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

     <!-- Footer -->
     <?php include('partials-front/footer.php')?>

</body>
</html>