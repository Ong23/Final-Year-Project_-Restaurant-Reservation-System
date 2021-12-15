<?php

date_default_timezone_set("Asia/Kuala_Lumpur");



session_start();

//between function.. elenxei an oi xaraktires einai mesa sta oria p thetoume
function between($val, $x, $y){
    $val_len = strlen($val);
    return ($val_len >= $x && $val_len <= $y)?TRUE:FALSE;
}

if(isset($_POST['reserv-submit'])) {//elenxw an exei bei sti selida mesw tou submit

    require 'dbh.inc.php';

    $user= $_SESSION['user_id'];
    $fname= $_POST['fname'];
    $lname= $_POST['lname'];
    $date= $_POST['date'];
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
        header("Location: ../reservation.php?error3=emptyfields");
        exit();
    }
        else if(!preg_match("/^[a-zA-Z ]*$/", $fname) || !between($fname,2,20)) {
        header("Location: ../reservation.php?error3=invalidfname");
        exit();
    }
        else if(!preg_match("/^[a-zA-Z ]*$/", $lname) || !between($lname,2,40)) {
        header("Location: ../reservation.php?error3=invalidlname");
        exit();
    }
        else if(!preg_match("/^[0-9]*$/", $guests) || !between($guests,1,3)) {
        header("Location: ../reservation.php?error3=invalidguests");
        exit();
    }
    //!preg_match("/^[a-zA-Z0-9]*$/", $tele)
        else if(!is_numeric($tele) || !between($tele,6,20)) {
        header("Location: ../reservation.php?error3=invalidtele");
        exit();
    }    
        else if(!preg_match("/^[a-zA-Z 0-9]*$/", $comments) || !between($comment,0,200)) {
        header("Location: ../reservation.php?error3=invalidcomment");
        exit();
    }
        else if($date < date('Y-m-d')) {
        header("Location: ../reservation.php?error3=invaliddate");
        exit();
    }
        else if($date == date('Y-m-d') && $time <= date('H:i', strtotime(date('H:i')) + 7200)) {
        header("Location: ../reservation.php?error3=invalidtime");
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
        
        $sql = "SELECT SUM(num_tables) FROM reservation WHERE rdate='$date' AND time_zone='$time'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $current_tables=$row["SUM(num_tables)"];
            }
        }
        if($current_tables + $tables > $a_tables){
            header("Location: ../reservation.php?error3=full");
        }
          
           
    
        else {
    
         $sql = "INSERT INTO reservation(f_name, l_name, num_guests, num_tables, rdate, time_zone, telephone, comment, user_fk, status) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')";
            $stmt = mysqli_stmt_init($conn);
                 if(!mysqli_stmt_prepare($stmt, $sql)){
                    header("Location: ../reservation.php?error3=sqlerror1");
                    exit();
                }
                else {       
                    mysqli_stmt_bind_param($stmt, "sssssssss", $fname, $lname, $guests, $tables, $date, $time, $tele, $comments, $user);
                    mysqli_stmt_execute($stmt);
                    $records = mysqli_query($conn, "SELECT * from reservation WHERE user_fk = $user ORDER BY reserv_id DESC LIMIT 1");
                    $data = mysqli_fetch_array($records);
                    $reserv_id = $data['reserv_id'];
                    header("Location: ../reservation.php?reservation=success&reserv_id=$reserv_id");
                    //select user's first (latest) reservation and retrieve id and put into URL
                    exit();
                }
        }
    }
    
   mysqli_stmt_close($stmt);
   mysqli_close($conn);
}
    


