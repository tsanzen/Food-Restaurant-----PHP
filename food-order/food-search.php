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
    <?php 
        include('partials-front/menu.php');

        // Prevent from entering this page if not search keyword
        if(!isset($_POST['search'])){
            header("Location: ".SITEURL.'index.php');
        }

        // Get the search keyword
        $search_keyword = mysqli_real_escape_string($conn, $_POST['search']);

    ?>

    <!-- FOOD SEARCH -->
    <section class="food-search text-center">
        <div class="container">
            
            <h2>Foods on your search <a href="#" class="text-white"><?php echo "'$search_keyword'"; ?></a></h2>

        </div>
    </section>



    <!-- FOOD MENU  -->
    <section class="food-menu">
        <div class="container">
            <?php

                // SQL QUERY to get foods based on search keyword
                $sql = "SELECT * FROM tbl_food WHERE title LIKE '%$search_keyword%' OR description LIKE '$search_keyword'";

                // Execute query 
                $res = mysqli_query($conn, $sql);

                // Count rows 
                $count = mysqli_num_rows($res);

                // Check whethere seached food available or not
                if($count > 0){
                    // Gets all rows from db in array format.
                    while ($row = mysqli_fetch_assoc($res)){
                        $id = $row['id'];
                        $food_title = $row['title'];
                        $food_desc = $row['description'];
                        $food_image = $row['image_name'];
                        $food_price = $row['price'];
                    }

                    // Display them.
                    ?>
                    <h2>Based on your search</h2>
                    <div class="food-menu-box">
                        <div class="food-menu-img">
                            <img src="<?php echo SITEURL;?>assets/images/food/<?php echo $food_image;?>" alt="Image" class="img-responsive img-curve">
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


                // Food not found 
                }else{
                    echo "<div style=' width: 100%; background-color: red; padding: 20px; color: white; border: none;'>Sorry, '$search_keyword' is not found!</div>";
                }
            ?>
            <div class="clearfix"></div>
        </div>

    </section>

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

   <!-- Footer -->
   <?php include('partials-front/footer.php')?>

</body>
</html>