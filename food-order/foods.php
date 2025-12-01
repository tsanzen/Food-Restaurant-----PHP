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

    <!-- FOOD SEARCH -->
    <section class="food-search text-center">
        <div class="container">
            
            <form action="<?php echo SITEURL;?>food-search.php" method="POST">
                <input type="search" name="search" placeholder="Search for Food.." required>
                <input type="submit" name="submit" value="Search" class="btn btn-primary">
            </form>

        </div>
    </section>



    <!-- FOOD MENU -->
    <section class="food-menu">
        <div class="container">
            <h2>Available Foods</h2>

              <!-- Displaying all foods from DB -->
              <?php
                // Query select all foods & execute.
                $sql_select_all_food = "SELECT * FROM tbl_food";
                $res = mysqli_query($conn, $sql_select_all_food);

                // If query result is successfull
                if($res == TRUE){
                    // variable to store num of foods available.
                    $count = mysqli_num_rows($res);

                    // If there're foods 
                    if($count > 0){
                        // Loop through them and display.
                        while($row = mysqli_fetch_assoc($res)){
                            $id = $row['id'];
                            $food_title = $row['title'];
                            $food_price = $row['price'];
                            $food_desc = $row['description'];
                            $image = $row['image_name'];


                            ?>
                            <div class="food-menu-box">
                                <div class="food-menu-img">
                                  <img src="<?php echo SITEURL;?>assets/images/food/<?php echo $image;?>" alt="Image" class="img-responsive img-curve">
                                </div>

                                <div class="food-menu-desc">
                                    <!-- Just display first 21 chars of the title & the desc if they're above that -->
                                    <h4>
                                        <?php 
                                           if(strlen($food_title) > 21){
                                            $some_title = substr($food_title, 0, 21);
                                            echo  $some_title . "...";
                                           }else{
                                            echo $food_title;
                                           }
                                        ?>
                                    </h4>
                                    <p class="food-price"><?php echo "₹".$food_price?></p>
                                    <p class="food-detail">
                                        <?php 
                                            $some_desc = substr($food_desc, 0, 21);
                                            echo  $some_desc . "...";
                                        ?>
                                    </p>
                                    <br>

                                    <a href="<?php echo SITEURL;?>order.php?food_id=<?php echo $id;?>" class="btn-order">Order now</a>
                                </div>
                             </div>
                            <?php
                        }
                    //If there're no foods at all:
                    }else{
                        echo "<div style=' width: 100%; background-color: red; padding: 20px; color: white; border: none;'>No foods are added!</div>";
                    }
                }
             ?>

            

          


            <div class="clearfix"></div>

            

        </div>

    </section>

    <!-- Socials -->
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