<?php

session_start();
//delete reservation
if(isset($_SESSION['user_id'])){

    if(isset($_POST['delete-submit'])) {
    
        require 'dbh.inc.php';
        
        $reservation_id = $_POST['reserv_id'];
            
        $sql = "UPDATE reservation SET status = 'Cancelled' WHERE reserv_id =$reservation_id";
        if (mysqli_query($conn, $sql)) {
            header("Location: ../view_reservations.php?delete=success");
        } else {
            header("Location: ../view_reservations.php?delete=error");
        }
    }

    //view preorder list

    if(isset($_POST['view'])) {

        require 'dbh.inc.php';

        $reservation_id = $_POST['reserv_id'];

        //user id from reservation
        //$records = mysqli_query($conn,"SELECT * FROM reservation WHERE reserv_id =$reservation_id LIMIT 1");
        //while($data = mysqli_fetch_array($records))
        //{
        //    $id = $data['user_fk'];
        //}  
        header("Location: ../preorder_list.php?reserv_id=$reservation_id");
    }

    if(isset($_POST['preorder'])) {

        require 'dbh.inc.php';

        $reservation_id = $_POST['reserv_id'];

        //user id from reservation
        //$records = mysqli_query($conn,"SELECT * FROM reservation WHERE reserv_id =$reservation_id LIMIT 1");
        //while($data = mysqli_fetch_array($records))
        //{
        //    $id = $data['user_fk'];
        //}

        //Check if reservation already have pre-ordered food record
        $sql = "SELECT * FROM `preorder dish` WHERE reserv_id = $reservation_id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) { //if already have order
            header("Location: ../preorder_food.php?reserv_id=$reservation_id&status=ordered");
        }
        else{ //if no order
            header("Location: ../preorder_food.php?reserv_id=$reservation_id");
        }

    }
    //Edit Reservation
    if(isset($_POST['edit'])) {

        require 'dbh.inc.php';

        $reservation_id = $_POST['reserv_id'];

        header("Location: ../edit_food_preorder.php?reserv_id=$reservation_id");
        exit();
    }
}

mysqli_close($conn);
?>

    


