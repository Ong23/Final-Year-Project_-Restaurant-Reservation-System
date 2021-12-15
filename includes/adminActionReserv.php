<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//confirm reservation

if(isset($_POST['confirm-submit'])) {
 
    require 'dbh.inc.php';
    //require '../phpmailer/PHPMailerAutoload.php'; (Older Version of PHPMailer)

    require '../phpmailer/includes/PHPMailer.php';
    require '../phpmailer/includes/SMTP.php';
    require '../phpmailer/includes/Exception.php';

    $reservation_id = $_POST['reserv_id'];

    //Send email notification
    $sql2 = "SELECT * FROM users AS u 
    INNER JOIN reservation AS r ON r.user_fk=u.user_id AND r.reserv_id = $reservation_id";
    $result = $conn->query($sql2);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {

            echo "<script> alert('Sending email to ".$row['emailUsers']."')</script>";

            $splitString = strstr($row['emailUsers'], '@');
	        $getDomain = substr($splitString, 1, strlen($splitString));
            if(strcmp($getDomain, "gmail.com") != 0) {

                $mail = new PHPMailer(true);
                $mail->IsSMTP();
                $mail->SMTPAuth = true;
                $mail->Host = "smtp.office365.com";
                $mail->Port = 587;
                $mail->SMTPSecure = 'tls';

                $mail->Username = "wongjkeat@hotmail.com";
                $mail->Password = "74123Wong";

                $mail->SMTPDebug  = 3;
                $mail->setFrom('wongjkeat@hotmail.com', 'MonkaS Restaurant');
                $mail->AddAddress($row['emailUsers']); 
                $mail->addReplyTo('wongjkeat@hotmail.com');

                $mail->isHTML(true);

                $mail -> Subject = 'Your Reservation is Confirmed!';
                $mail -> Body = 'Dear '.$row['f_name'].' '.$row['l_name'].', <br><br>
                                Reservation (ID: '.$reservation_id.')<br><br> <strong>Table for '.$row['num_guests'].' on '.$row['rdate'].' at '.$row['time_zone'].' is confirmed.<strong>';
            }
            else{
                $mail = new PHPMailer(true);

                $mail->isSMTP();
                $mail->SMTPAuth = true;
                $mail->Host = 'smtp.gmail.com';
                $mail->Username = 'wongjkeat@gmail.com';     
                $mail->Password = '74123Wong';                         
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;


                $mail->setFrom('no-reply@gmail.com', 'MonkaS Restaurant');
                $mail->addAddress($row['emailUsers']);

                $mail->addReplyTo('wongjkeat@gmail.com');

                $mail->isHTML(true);

                $mail -> Subject = 'Your Reservation is Confirmed!';
                $mail -> Body = 'Dear '.$row['f_name'].' '.$row['l_name'].', <br><br>
                                Reservation (ID: '.$reservation_id.')<br><br> <strong>Table for '.$row['num_guests'].' on '.$row['rdate'].' at '.$row['time_zone'].' is confirmed.<strong>';

                
            }
            if(!$mail->send()) {
                echo "<script> alert('ERROR: Not Sent')</script>";
                echo '<script> alert(Mailer Error: ' .$mail->ErrorInfo.'</script>';
                header("Location: ../view_reservations.php?confirm=error");
            } else {
                echo "<script> alert('Message has been sent') </script>";
                
                $sql = "UPDATE reservation SET status = 'Confirmed' WHERE reserv_id =$reservation_id";
                if (mysqli_query($conn, $sql)) {
                    header("Location: ../view_reservations.php?confirm=success&reserv_id=$reservation_id");
                } else {
                    header("Location: ../view_reservations.php?confirm=error");
                }
            }
        }
    }
}

//reject reservation

if(isset($_POST['reject-submit'])) {
 
    //require 'dbh.inc.php';
    
    //$reservation_id = $_POST['reserv_id'];
       
    //$sql = "UPDATE reservation SET status = 'Rejected' WHERE reserv_id =$reservation_id";
    //if (mysqli_query($conn, $sql)) {
    //    header("Location: ../view_reservations.php?reject=success&reserv_id=$reservation_id");
    //} else {
    //    header("Location: ../view_reservations.php?reject=error");
    //}
    require 'dbh.inc.php';
    //require '../phpmailer/PHPMailerAutoload.php'; (Older Version of PHPMailer)

    require '../phpmailer/includes/PHPMailer.php';
    require '../phpmailer/includes/SMTP.php';
    require '../phpmailer/includes/Exception.php';
    
    $reservation_id = $_POST['reserv_id'];

    //Send email notification
    $sql2 = "SELECT * FROM users AS u 
    INNER JOIN reservation AS r ON r.user_fk=u.user_id AND r.reserv_id = $reservation_id";
    $result = $conn->query($sql2);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {

            echo "<script> alert('Sending email to ".$row['emailUsers']."')</script>";

            $splitString = strstr($row['emailUsers'], '@');
	        $getDomain = substr($splitString, 1, strlen($splitString));
            if(strcmp($getDomain, "gmail.com") != 0) {

                $mail = new PHPMailer(true);
                $mail->IsSMTP();
                $mail->SMTPAuth = true;
                $mail->Host = "smtp.office365.com";
                $mail->Port = 587;
                $mail->SMTPSecure = 'tls';

                $mail->Username = "wongjkeat@hotmail.com";
                $mail->Password = "74123Wong";

                $mail->SMTPDebug  = 3;
                $mail->setFrom('wongjkeat@hotmail.com', 'MonkaS Restaurant');
                $mail->AddAddress($row['emailUsers']); //Receiver Email (NEED TO CHANGE)
                $mail->addReplyTo('wongjkeat@hotmail.com');

                $mail->isHTML(true);

                $mail -> Subject = 'Your Reservation is Rejected';
                $mail -> Body = 'Dear '.$row['f_name'].' '.$row['l_name'].', <br><br>We apologise that we reject your reservation (ID: '.$reservation_id.').
                <br> But you are allowed to change the reservation in our website, and we are happy to help you to check the availability of your reservation.';

            }
            else{
                $mail = new PHPMailer(true);

                $mail->isSMTP();
                $mail->SMTPAuth = true;
                $mail->Host = 'smtp.gmail.com';
                $mail->Username = 'wongjkeat@gmail.com';     
                $mail->Password = '74123Wong';                         
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;


                $mail->setFrom('no-reply@gmail.com', 'MonkaS Restaurant');
                $mail->addAddress($row['emailUsers']);

                $mail->addReplyTo('wongjkeat@gmail.com');

                $mail->isHTML(true);

                $mail -> Subject = 'Your Reservation is Rejected';
                $mail -> Body = 'Dear '.$row['f_name'].' '.$row['l_name'].', <br><br>We apologise that we reject your reservation (ID: '.$reservation_id.').
                <br> But you are allowed to change the reservation in our website, and we are happy to help you to check the availability of your reservation.';

                
            }
            if(!$mail->send()) {
                echo "<script> alert('ERROR: Not Sent')</script>";
                echo '<script> alert(Mailer Error: ' .$mail->ErrorInfo.'</script>';
                header("Location: ../view_reservations.php?confirm=error");
            } else {
                echo "<script> alert('Message has been sent') </script>";
                
                $sql = "UPDATE reservation SET status = 'Rejected' WHERE reserv_id =$reservation_id";
                if (mysqli_query($conn, $sql)) {
                    header("Location: ../view_reservations.php?reject=success&reserv_id=$reservation_id");
                } else {
                    header("Location: ../view_reservations.php?reject=error");
                }
            }
        }
    }
}

if(isset($_POST['edit-submit'])) {
    require 'dbh.inc.php';

    echo "Admin Edit Page";
}

mysqli_close($conn);
?>

    


