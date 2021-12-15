<?php
require "header.php";
?>

    
    <!-- end of nav bar -->

<br><br>
<div class="container">
    <h3 class="text-center"><br>New Reservation<br></h3>   
    <div class="row">
        <div class="col-md-6 offset-md-3">   
 
        
        
        
    
<?php

date_default_timezone_set("Asia/Kuala_Lumpur");

if(isset($_SESSION['user_id'])){
    echo '<p class="text-black text-center">Welcome '. $_SESSION['username'] .'<br> Create your reservation here!</p>';

    //error handling:
    
    if(isset($_GET['error3'])){
        if($_GET['error3'] == "emptyfields") {  
            echo '<h5 class="bg-danger text-center">Fill all fields, Please try again!</h5>';
        }
        else if($_GET['error3'] == "invalidfname") {   
            echo '<h5 class="bg-danger text-center">Invalid First Name, Please try again!</h5>';
        }
        else if($_GET['error3'] == "invalidlname") {   
            echo '<h5 class="bg-danger text-center">Invalid Last Name, Please try again!</h5>';
        }
        else if($_GET['error3'] == "invalidtele") {   
            echo '<h5 class="bg-danger text-center">Invalid Telephone, Pleast try again!</h5>';
        }
        else if($_GET['error3'] == "invalidcomment") {   
            echo '<h5 class="bg-danger text-center">Invalid Comment, Pleast try again!</h5>';
        }
        else if($_GET['error3'] == "invalidguests") {   
            echo '<h5 class="bg-danger text-center">Invalid Guests, Pleast try again!</h5>';
        }
        else if($_GET['error3'] == "full") {   
            echo '<h5 class="bg-danger text-center">Reservations are full this date and timezone, Please try again!</h5>';
        }
        else if($_GET['error3'] == "preorderError") {   
            echo '<h5 class="bg-danger text-center">Error! Please contact administrator for assistance!</h5>';
        }
        else if($_GET['error3'] == "invaliddate") {   
            echo '<h5 class="bg-danger text-center">Invalid reservation date. Please select date later than today or today ('.date('Y-m-d').')</h5>';
        }
        else if($_GET['error3'] == "invalidtime") {   
            echo '<h5 class="bg-danger text-center">Please make reservation 2 hours later than current time: '.date('H:i').'</h5>';
        }
    }
        if(isset($_GET['reservation'])) {   
           if($_GET['reservation'] == "success"){ 
                //retrieve reserv ID
                if(isset($_GET['reserv_id'])) {
                    $reserv_id = $_GET['reserv_id'];
                
                    echo '<h5 class="bg-success text-center">Reservation  successfull!</h5>';

                    //Redirect to Pre-Order Food page
                    echo '<script type="text/javascript">'; 
                    echo 'if(confirm("Do you want to pre-order food for your reservation (ID:'.$reserv_id.')?")){
                        window.location.href = "preorder_food.php?reserv_id='.$reserv_id.'";
                    }';
                    echo '</script>';
                }   
            }
        }
        echo'<br>';



    

    
    
     //reservation form  
    echo '  
        
    <div class="signup-form">
        <form action="includes/reservation.inc.php" method="post">
            <div class="form-group">
            <label>First Name</label>
                <input type="text" class="form-control" name="fname" placeholder="First Name" required="required">
                
            </div>
            <div class="form-group">
            <label>Last Name</label>
                <input type="text" class="form-control" name="lname" placeholder="Last Name" required="required">
                
            </div>   
            <div class="form-group">
            <label>Date</label>
        	<input type="date" class="form-control" name="date" placeholder="Date" required="required">
            </div>
            <div class="form-group">
		<label>Time</label>
		<select class="form-control" name="time">
		<option>12:00</option>
		<option>16:00</option>
		<option>18:00</option>
		<option>20:00</option>
		</select>
            </div>
            <div class="form-group">
            <label>Number of Persons</label>
        	<input type="number" class="form-control" min="2" name="num_guests" placeholder="Guests" required="required">
                <small class="form-text text-muted">* Minimum 2 persons</small>
            </div>
            <div class="form-group">
            <label for="guests">Contact Number</label>
                <input type="telephone" class="form-control" name="tele" placeholder="e.g.: 0167654321" required="required">
            </div>
            <div class="form-group">
            <label>Remarks</label>
                <textarea class="form-control" name="comments" placeholder="e.g.: Food allergies, etc." rows="3"></textarea>
                <small class="form-text text-muted">Remarks must be less than 200 characters</small>
            </div>        
            <div class="form-group">
		<label class="checkbox-inline"><input type="checkbox" required="required"> I accept the <a href="#">Terms of Use</a> &amp; <a href="#">Privacy Policy</a></label>
            </div>
            <div class="form-group">
            <button type="submit" name="reserv-submit" class="btn btn-success btn-lg btn-block">Submit Reservation</button>
            </div>
        </form>
        <br><br>
    </div>
    ';  
    }

    else {
        echo '	<p class="text-center text-danger"><br>You are currently not logged in!<br></p>
       <p class="text-center">You need to 
       <a class="text-primary" data-toggle="modal" data-target="#myModal_reg">create account</a> or 
        <a class="text-primary" data-toggle="modal" data-target="#myModal_login">login</a>
       to make a reservation!<br><br><p>'; 
    }
    ?>

             
        </div>
    </div>
</div>
<br><br>
