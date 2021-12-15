<?php

session_start();
date_default_timezone_set("Asia/Kuala_Lumpur");

//between function.. elenxei an oi xaraktires einai mesa sta oria p thetoume
function between($val, $x, $y){
    $val_len = strlen($val);
    return ($val_len >= $x && $val_len <= $y)?TRUE:FALSE;
}

if(isset($_POST['update-reserv-submit'])) {//elenxw an exei bei sti selida mesw tou submit


    require 'dbh.inc.php';
    
    $user= $_SESSION['user_id'];
    $reserv_id = $_POST['reserv_id'];
    $fname= $_POST['fname'];
    $lname= $_POST['lname'];
    $date= $_POST['rdate'];
    $time= $_POST['time'];
    $guests= $_POST['num_guests'];
    $tele = $_POST['tele'];
    $comments = $_POST['comments'];
    
    if($guests==1 || $guests==2){
        $tables=1;
    }
    else{
        $tables=ceil(($guests-2)/2);
    }
    
    
    if(empty($fname) || empty($lname) || empty($date) || empty($time) || empty($guests) || empty($tele)) {
        header("Location: ../view_reservations.php?error3=emptyfields");
        exit();
    }
        else if(!preg_match("/^[a-zA-Z ]*$/", $fname) || !between($fname,2,20)) {
        header("Location: ../view_reservations.php?error3=invalidfname");
        exit();
    }
        else if(!preg_match("/^[a-zA-Z ]*$/", $lname) || !between($lname,2,40)) {
        header("Location: ../view_reservations.php?error3=invalidlname");
        exit();
    }
        else if(!preg_match("/^[0-9]*$/", $guests) || !between($guests,1,3)) {
        header("Location: ../view_reservations.php?error3=invalidguests");
        exit();
    }
        else if(!preg_match("/^[a-zA-Z0-9]*$/", $tele) || !between($tele,6,20)) {
        header("Location: ../view_reservations.php?error3=invalidtele");
        exit();
    }    
        else if(!preg_match("/^[a-zA-Z 0-9]*$/", $comments) || !between($comments,0,200)) {
        header("Location: ../view_reservations.php?error3=invalidcomment");
        exit();
    }
        else if($date < date('Y-m-d')) {
        header("Location: ../view_reservations.php?error3=invaliddate");
        exit();
    }
        else if($date == date('Y-m-d') && $time <= date('H:i', strtotime(date('H:i')) + 7200)) {
        header("Location: ../view_reservations.php?error3=invaliddate1");
        exit();
    }
    
    else{
     //checkarw ta available trapezia ana mera   
        $sql = "SELECT t_tables FROM tables WHERE t_date='$date'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $a_tables=$row["t_tables"];
            }
        }
        else{$a_tables=20;} //default timi
        
        
    //elenxos trapeziwn ews 20 trapezia gia kathe imerominia
        
        $sql = "SELECT SUM(num_tables) FROM reservation WHERE rdate='$date' AND time_zone='$time' AND reserv_id !=$reserv_id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $current_tables=$row["SUM(num_tables)"];
            }
        }
        if($current_tables + $tables > $a_tables){
            header("Location: ../view_reservations.php?error3=full");
        }
          
           
    
        else {
            $sql = "UPDATE reservation SET f_name='$fname', l_name='$lname', num_guests=$guests, num_tables=$tables, 
            rdate='$date', time_zone='$time', telephone='$tele', comment='$comments', status='Edited' WHERE reserv_id =$reserv_id";

           if (mysqli_query($conn, $sql)) {
                header("Location: ../view_reservations.php?edit=success&reserv_id=$reserv_id");
                exit();
            } else {
                header("Location: ../view_reservations.php?edit=error&reserv_id=$reserv_id");
                exit();
            }
        }
    }
    
   mysqli_close($conn);
}
 
?>
