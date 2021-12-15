<?php

session_start();

// Add new dish
// Check if image file is a actual image or fake image
if(isset($_POST["add-submit"])) {

    require 'includes/dbh.inc.php';

    $target_dir = "img/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    $user= $_SESSION['user_id'];
    
    $dish_name= $_POST['dish_name'];
    $dish_desc= $_POST['dish_desc'];
    $price= $_POST['price'];
    $dish_cat_id= $_POST['category'];

    //Error CHecking
    if(empty($dish_name) || empty($price)) {
        header("Location: manage_menu.php?errorAddDish=emptyfields");
        exit();
    }
    else if(!is_numeric($price)) {
        header("Location: manage_menu.php?errorAddDish=invalidprice");
        exit();
    }

    //Check Image
    if(!empty($_FILES["fileToUpload"]["tmp_name"]))
    {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) 
        {
            $uploadOk = 1;
        } 
        else 
        {
            header("Location: manage_menu.php?image_insert=error1");
            $uploadOk = 0;
        }

        if ($_FILES["fileToUpload"]["size"] >= 2097152) 
        {
            header("Location: manage_menu.php?image_insert=error3");
            $uploadOk = 0;
        }

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) 
        {
            header("Location: manage_menu.php?image_insert=error4");
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) 
        {
            header("Location: manage_menu.php?image_insert=error5");
        // if everything is ok, try to upload file
        } 
        else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file) || $uploadOk == 1) 
            {
                header("Location: manage_menu.php?image_insert=upload_success");
            } 
            
            else 
            {
                header("Location: manage_menu.php?image_insert=error5");
            }
            $query ="INSERT INTO `dish` (dish_name, dish_desc, price, dish_photo, active, dish_cat_id) 
            VALUES ( '". $dish_name."','". $dish_desc."', '". $price."','". $target_file."', 1 ,'". $dish_cat_id."')";

            mysqli_query($conn, $query);
        }
    }
    else{
        $target_file = "img/default.png";
        $query ="INSERT INTO `dish` (dish_name, dish_desc, price, dish_photo, active, dish_cat_id) 
                    VALUES ( '". $dish_name."','". $dish_desc."', '". $price."','". $target_file."', 1 ,'". $dish_cat_id."')";
        //$target_file 
        if (mysqli_query($conn, $query)) {
            header("Location: manage_menu.php?insert=success");
        } else {
            header("Location: manage_menu.php?insert=error");
        }
    }
    
    mysqli_close($conn);
}

// Change photo
// Almost same as add new dish photo check
if(isset($_POST["change-photo-submit"])) {

    require 'includes/dbh.inc.php';

    $dish_id = $_POST['dish_id1'];

    $target_dir = "img/";
    $target_file = $target_dir . basename($_FILES["fileToUpload1"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    

    $user= $_SESSION['user_id'];

    //Check Image
    if(!empty($_FILES["fileToUpload1"]["tmp_name"]))
    {
        $check = getimagesize($_FILES["fileToUpload1"]["tmp_name"]);
        if($check !== false) 
        {
            $uploadOk = 1;
        } 
        else 
        {
            header("Location: manage_menu.php?id=$dish_id&changePhoto=error");
            $uploadOk = 0;
        }

        if ($_FILES["fileToUpload1"]["size"] >= 2000000) 
        {
            $error = "errorImageSize";
            $uploadOk = 0;
        }

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) 
        {
            $error = "errorFileType";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) 
        {
            header("Location: manage_menu.php?id=$dish_id&changePhoto=$error");
        // if everything is ok, try to upload file
        } 
        else {
            if (move_uploaded_file($_FILES["fileToUpload1"]["tmp_name"], $target_file) || $uploadOk == 1) 
            {
                header("Location: manage_menu.php?id=$dish_id&changePhoto=success");

                $query = "UPDATE dish SET dish_photo='$target_file' WHERE dish_id=$dish_id";
    
                mysqli_query($conn, $query);
            } 
            
            else 
            {
                header("Location: manage_menu.php?id=$dish_id&changePhoto=error");
            }
        }
    }
    else{

        header("Location: manage_menu.php?id=$dish_id&changePhoto=errorNoFile");

    }
    
    mysqli_close($conn);
}

if(isset($_POST["add-cat-submit"])) {

    require 'includes/dbh.inc.php';

    $dish_cat= $_POST['dish_cat'];

    $query ="INSERT INTO `dish category` (category_desc) 
                    VALUES ( '". $dish_cat."')";
        //$target_file 
    if (mysqli_query($conn, $query)) {
        header("Location: manage_menu.php?insertCat=success");
    } else {
        header("Location: manage_menu.php?insertCat=error");
    }
}

if(isset($_POST["remove-cat-submit"])) {

    require 'includes/dbh.inc.php';

    $dish_cat= $_POST['dish_cat'];

    $query ="DELETE FROM `dish category` WHERE dish_cat_id=$dish_cat";
        //$target_file 
    if (mysqli_query($conn, $query)) {

        //No Null is allow in dish_cat_id
        $query ="UPDATE dish SET dish_cat_id=1 WHERE dish_cat_id IS NULL";

        if (mysqli_query($conn, $query)){
            header("Location: manage_menu.php?remove=success");
        }else {
            header("Location: manage_menu.php?remove=error");
        }
        
    } else {
        header("Location: manage_menu.php?remove=error");
    }
}

?>