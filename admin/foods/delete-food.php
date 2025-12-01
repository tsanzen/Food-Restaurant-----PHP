
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Deletion</title>
    <style>
        /* Blur the background */
        body {
            font-family: Arial, sans-serif;
            background: url('your-background-image.jpg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .blur-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(8px);
            background: rgba(0, 0, 0, 0.6);
        }

        /* Center the modal */
        .confirmation-modal {
            position: relative;
            z-index: 999;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            text-align: center;
            width: 300px;
        }

        .confirmation-modal button {
            padding: 10px 20px;
            border: none;
            margin: 10px;
            cursor: pointer;
            border-radius: 2px;
        }

        .confirm-btn {
            background-color: green;
            color: white;
        }

        .cancel-btn {
            background-color: red;
            color: white;
        }
    </style>
</head>
<body>
    <?php 
        include("../../config/constants.php");

        // Prevent user trying to go delete-category page manually without id and image_name.
        if(!isset($_GET['id']) AND !isset($_GET['image_name'])){
            header("Location: ".SITEURL.'admin/foods/manage-food.php');

        }

    
    ?>


    <!-- Background Blur Layer -->
    <div class="blur-background"></div>

    <!-- Confirmation Modal -->
    <div class="confirmation-modal">
        <h3>Are you sure you want to delete this food?</h3>
        <form action="" method="POST">
            <button type="submit" name="confirm_delete" class="confirm-btn">Sure</button>
            <button type="button" class="cancel-btn" onclick="window.location.href='manage-food.php';">Cancel</button>
        </form>
    </div>

    <?php
        // Handle the confirmation deletion
        if (isset($_POST['confirm_delete'])) {
      
            //1. Get values 
            $id = $_GET['id'];
            $image_name = $_GET['image_name'];

            //2. Remove the physicall image if available
            if($image_name !== ""){
                $path = "../../assets/images/food/".$image_name;
                $remove = unlink($path);

                if($remove == false){
                    $_SESSION['remove'] = '<div class="error" id="session"> Failed to remove food image!</div>'; 
                    header("Location: ".SITEURL.'admin/foods/manage-food.php');

                    // STOP THE PROCESS
                    die();
                }
            }

            //3. Delete Id from the DB

            //  Create SQL query to delete Admin.
            $sql = "DELETE FROM tbl_food WHERE id=$id";
      
            //  Execute the query.
            $res = mysqli_query($conn, $sql);
      
            //  Redirect to the admin page if deletion is successful
            if ($res == TRUE) {
                $_SESSION['delete'] = '<div class="success" id="session"> Food is deleted successfully</div>';
                header("Location: ".SITEURL.'admin/foods/manage-food.php');
            }else{
                $_SESSION['delete'] = '<div class="error" id="session"> Failed to delete the food!</div>';
                header("Location: ".SITEURL.'admin/foods/manage-food.php');
            }
        }
      ?>
      
</body>
</html>
