<?php

    require 'dbh.inc.php'; //DB connection

    if(isset($_GET['info2']))
    {
        $dish_id = $_GET['info2'];
        $data2 = json_decode($dish_id);
    }
    if(isset($_GET['id']))
    {
        $id = $_GET['id'];
    }
    if(is_array($data2) ){    
        for($i = 0; $i <= count($data2) ; $i++)
        {
            $query ="INSERT INTO `preorder dish` ( reserv_id, dish_id) VALUES ('".$id."','".$data2[$i]."')";
            mysqli_query($conn, $query);
        }
        header("Location: http://localhost/Restaurant-Reservation/edit_food_preorder.php?reserv_id=$id&addItems=success");
        exit();
    }

    else{
        header("Location: http://localhost/Restaurant-Reservation/edit_food_preorder.php?reserv_id=$id&addItems=error");
        exit();
    }
    mysqli_close($conn);

?>
