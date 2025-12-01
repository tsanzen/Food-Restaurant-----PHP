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

    <!-- CAtegories Section Starts Here -->
    <section class="categories">
        <div class="container">
            <h2 style="padding-left: 25px;">All Categories</h2>

            <!-- Displaying all categories from DB -->
             <?php
                // Query select all ctg.. & execute.
                $sql_select_all_ctg = "SELECT * FROM tbl_category";
                $res = mysqli_query($conn, $sql_select_all_ctg);

                // If query result is successfull
                if($res == TRUE){
                    // variable to store num of categories available.
                    $count = mysqli_num_rows($res);

                    // If there're categories 
                    if($count > 1){
                        // Loop through them and display.
                        while($row = mysqli_fetch_assoc($res)){
                            $id = $row['id'];
                            $title = $row['title'];
                            $image = $row['image_name'];

                            ?>
                             <a href="<?php echo SITEURL;?>category-foods.php?category_id=<?php echo $id?>">
                                <div class="box-3 float-container">
                                    <img src="<?php echo SITEURL;?>assets/images/category/<?php echo $image;?>" alt="Image" class="img-responsive img-curve">

                                    <h3 class="float-text text-white"><?php echo $title; ?></h3>
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

</body>
</html>