<?php
session_start();

if(isset($_POST["activate"]))
{
    require 'dbh.inc.php';
    
    $dish_id = $_POST['dish_id'];

    $sql = "UPDATE dish SET active = 1 WHERE dish_id =$dish_id";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../manage_menu.php?id=$dish_id&activate=success");
        exit();
    } else {
        header("Location: ../manage_menu.php?id=$dish_id&activate=error");
        exit();
    }
}

if(isset($_POST["deactivate"]))
{
    require 'dbh.inc.php';

    $dish_id = $_POST['dish_id'];

    $sql = "UPDATE dish SET active = 0 WHERE dish_id =$dish_id";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../manage_menu.php?id=$dish_id&deactivate=success");
        exit();
    } else {
        header("Location: ../manage_menu.php?id=$dish_id&deactivate=error");
        exit();
    }
}

if(isset($_POST["update-dish-submit"]))
{
    require 'dbh.inc.php';

    $dish_id = $_POST['dish_id'];

    $dish_name = $_POST['name'];
    $dish_desc= $_POST['dish_desc'];
    $price= $_POST['price'];
    $dish_cat_id= $_POST['category'];

    $sql = "UPDATE dish SET dish_name='$dish_name', dish_desc='$dish_desc', price=$price, dish_cat_id='$dish_cat_id' WHERE dish_id =$dish_id";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../manage_menu.php?id=$dish_id&update=success");
        exit();
    } else {
        header("Location: ../manage_menu.php?id=$dish_id&update=error");
        exit();
    }
}

?>