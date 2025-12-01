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
    <!-- Include Navbar  -->
    <?php include('partials-front/menu.php');?>


    <!-- FOOD SEARCH  -->
    <section class="food-search text-center">
        <div class="container">
            <form action="<?php echo SITEURL;?>food-search.php" method="POST">
                <input type="search" name="search" placeholder="Search for Food.." required>
                <input type="submit" name="submit" value="Search" class="btn btn-primary">
            </form>
        </div>
    </section>

    <!-- SESSION DISPLAYING -->
    <?php
        if(isset($_SESSION['order'])){
            echo $_SESSION['order'];
            unset($_SESSION['order']);
        }
    ?>

    <!-- Catgeories -->
    <section class="categories">
        <div class="container">
            <h2 style="padding-left: 15px;">Explore Foods</h2>

            <!-- Displaying categories from DB -->
           <?php
            // 1. Create SQL command to select only 3 active & featured categories & execute.
            $sql_select_ctg = "SELECT * FROM tbl_category WHERE active='Yes' AND featured='Yes' LIMIT 3"; 
            $res = mysqli_query($conn, $sql_select_ctg);

            // if successfull
            if($res == TRUE){
              $count = mysqli_num_rows($res);
            
              //If there're categories:
              if($count > 0){
                while($row = mysqli_fetch_assoc($res)){
                    $id = $row['id'];
                    $title = $row['title'];
                    $image = $row['image_name'];
                    
                    ?>
                         <a href="<?php echo SITEURL;?>category-foods.php?category_id=<?php echo $id?>">
                            <div class="box-3 float-container">
                                <img src="<?php echo SITEURL;?>assets/images/category/<?php echo $image;?>" alt="Image" style="width: 100%; height: 25em; object-fit: cover; border-radius: 15px;">

                                <h3 class="float-text text-white"><?php echo $title;?></h3>
                            </div>
                        </a>
                        
                    <?php
                }
              //If there're no categories at all:
              }else{
                echo "<div style=' width: 100%; background-color: red; padding: 20px; color: white; border: none;'>No categories are added!</div>";
              }
            }
           ?>
            <div class="clearfix"></div>
        </div>
    </section>

    <!-- Foods  -->
    <section class="food-menu">
        <div class="container">
            <h2 style="padding-left: 15px;">Foods</h2>

             <!-- Displaying active foods from DB -->
             <?php
                // 1. Create SQL command to select only active $ featured foods & execute.
                $sql_select_foods = "SELECT * FROM tbl_food WHERE active='Yes' AND featured='Yes' LIMIT 4";
                $res2 = mysqli_query($conn, $sql_select_foods);

                // If query result is successfull
                if($res2 == TRUE){
                    // variable to store num of foods available.
                    $count = mysqli_num_rows($res2);

                    // If there're foods 
                    if($count > 0){
                        // Loop through them and display.
                        while($row = mysqli_fetch_assoc($res2)){
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

        <p class="text-right" style="margin-right: 12em">
            <a href="<?php echo SITEURL;?>foods.php">See All</a>
        </p>
    </section>

    <!-- Social -->
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