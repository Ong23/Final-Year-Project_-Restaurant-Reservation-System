<?php

    require 'includes/dbh.inc.php'; //DB connection

    if(isset($_GET['info2']))
    {
        $dish_id = $_GET['info2'];
        $data2 = json_decode($dish_id);
    }
    if(isset($_GET['id']))
    {
        $id = $_GET['id'];
    }

    
    //Get User Id for authorization
    /*
    $records = mysqli_query($conn,"SELECT * FROM reservation WHERE reserv_id =$id LIMIT 1");
    while($data = mysqli_fetch_array($records))
    {
        $uid = $data['user_fk'];
    }
    if($uid == $_SESSION['user_id']){*/
        /*
        foreach($data as $value){
            foreach($data2 as $value2){
                $query ="INSERT INTO `preorder dish` (dish_name, reserv_id, dish_id) VALUES ( '". $value."','".$id."','".$value2."')";
                mysqli_query($conn, $query);
            }
        }
        echo $data[$i];
                echo $data2[$i];
                echo "<br>";
        */
        if(is_array($data2) ){    
            if(empty($data2))
            {
                header("Location: http://localhost/Restaurant-Reservation/preorder_food.php?reserv_id=$id&preorder=empty");
                exit();
            }
            else
            {
                for($i = 0; $i <= count($data2) ; $i++)
                {
                    $query ="INSERT INTO `preorder dish` (reserv_id, dish_id) VALUES ('".$id."','".$data2[$i]."')";
                    mysqli_query($conn, $query);
                }
                header("Location: http://localhost/Restaurant-Reservation/view_preorder.php?preorder=success");
                exit();
            } 
        }
        else{
            header("Location: http://localhost/Restaurant-Reservation/view_preorder.php?error3=preorderError");
            exit();
        }
    /*}
    else{
        echo "<br><br><p class='text-center text-danger'>You have no authorization to perform this action.</p>";
    }*/
    mysqli_close($conn);

?>
