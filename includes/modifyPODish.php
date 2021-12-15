<!--Modify Pre-Order Dish List-->

<?php

    session_start();
    

    if(isset($_POST["remove-submit"]))
    {   
        require 'dbh.inc.php';

        $rid = $_POST['rid'];
        $dish_id = $_POST['dish_id'];


        $sql = "DELETE FROM `preorder dish` WHERE dish_id = $dish_id AND reserv_id =$rid LIMIT 1";

        if (mysqli_query($conn, $sql)) {
            header("Location: ../edit_food_preorder.php?reserv_id=$rid&dish=$dish_id&remove=success");
            exit();
        } else {
            header("Location: ../edit_food_preorder.php?reserv_id=$rid&dish=$dish_id&remove=error");
            exit();
        }
    }

    if(isset($_POST["add-submit"]))
    {
        require 'dbh.inc.php';

        $rid = $_POST['rid'];
        $dish_id = $_POST['dish_id'];

        $sql = "INSERT INTO `preorder dish` (reserv_id, dish_id) VALUES ($rid, $dish_id)";

        if (mysqli_query($conn, $sql)) {
            header("Location: ../edit_food_preorder.php?reserv_id=$rid&dish=$dish_id&add=success");
            exit();
        } else {
            header("Location: ../edit_food_preorder.php?reserv_id=$rid&dish=$dish_id&add=error");
            exit();
        }
    }

    mysqli_close($conn);
?>