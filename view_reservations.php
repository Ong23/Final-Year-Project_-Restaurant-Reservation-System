<?php
require "header.php";
?>
    
<br><br>
<div class="container">
<h3 class="text-center"><br>View Reservations<br></h3>     

<?php
date_default_timezone_set("Asia/Kuala_Lumpur");

    if(isset($_SESSION['user_id'])){
        if(isset($_GET['delete'])){
            if($_GET['delete'] == "error") {   
                echo '<h5 class="bg-danger text-center">Error!</h5>';
            }
            if($_GET['delete'] == "success"){ 
                echo '<h5 class="bg-success text-center">Cancellation successful</h5>';
            }
        }  
        if(isset($_GET['confirm'])){
            if($_GET['confirm'] == "error") {   
                echo '<script>alert("Failed to send email notification")</script>';
                echo '<h5 class="bg-danger text-center">Error!</h5>';
            }
            if($_GET['confirm'] == "success"){ 
                $reserv_id = $_GET['reserv_id'];
                echo '<script>alert("Email notification has been sent")</script>';
                echo '<h5 class="bg-success text-center">You have confirmed reservation ID '.$reserv_id.'</h5>';
            }
        }  
        if(isset($_GET['reject'])){
            if($_GET['reject'] == "error") {   
                echo '<script>alert("Failed to send email notification")</script>';
                echo '<h5 class="bg-danger text-center">Error!</h5>';
            }
            if($_GET['reject'] == "success"){ 
                $reserv_id = $_GET['reserv_id'];
                echo '<script>alert("Email notification has been sent")</script>';
                echo '<h5 class="bg-success text-center">You have rejected reservation ID '.$reserv_id.'</h5> ';
            }
        }  
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
                echo '<h5 class="bg-danger text-center">Invalid reservation date. Please select date later than today ('.date('Y-m-d').')</h5>';
            }
            else if($_GET['error3'] == "invaliddate1") {   
                echo '<h5 class="bg-danger text-center">Selected reservation time must be 2 hours later than current time: '.date('H:i').'</h5>';
            }
        }

        if(isset($_GET['reservation'])) {   
            if($_GET['reservation'] == "success"){ 
                 //retrieve reserv ID
                 if(isset($_GET['reserv_id'])) {
                     $reserv_id = $_GET['reserv_id'];
                 
                     echo '<h5 class="bg-success text-center">Reservation successfull!</h5>';

                 }   
             }
             else if($_GET['reservation'] == "error"){ 
                if(isset($_GET['reserv_id'])) {
                    $reserv_id = $_GET['reserv_id'];
                
                    echo '<h5 class="bg-success text-center">Error. Fail to Make Reservation. Please try again</h5>';

                }   
             }
         }
         if(isset($_GET['edit'])) {   
            if($_GET['edit'] == "success"){ 
                 //retrieve reserv ID
                 if(isset($_GET['reserv_id'])) {
                     $reserv_id = $_GET['reserv_id'];
                 
                     echo '<h5 class="bg-success text-center">Edit successfull! (Reservation ID: '.$reserv_id.')</h5>';

                 }   
             }
             else if($_GET['edit'] == "error"){ 
                if(isset($_GET['reserv_id'])) {
                    $reserv_id = $_GET['reserv_id'];
                
                    echo '<h5 class="bg-success text-center">Error. Fail to edit Reservation ID: '.$reserv_id.'. Please try again</h5>';

                }   
             }
         }
         echo'<br>';

        require 'includes/view.reservation.inc.php';
        
    }
    else {
        echo '<script>window.location.href = "noLogin.php"; </script>';
    }
?>

</div>
<br><br>



<!--Edit Modal-->
<div class="container">
  <!-- The Modal -->
    <div class="modal fade" id="myModal_editReserv">
        <div class="modal-dialog">
            <div class="modal-content">
            <!-- Modal Header -->
                <div class="modal-header">
                  <h4 class="modal-title">Edit Reservation</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>      
            <!-- Modal body -->
                <div class="modal-body">   
    <!---Edit form -->
                    <div class="signup-form">
                        <form action="includes/edit_reserv.inc.php" method="post">
                            <p class="hint-text">Fill in the fields and click "Modify Reservation" button</p><hr>
                            <p>Note: Reservation status will be changed to "Edited" after modifying Reservation and will be confirmed 
                            or rejected by admin again.</p>
                            <input type="hidden" class="form-control" id= "reserv_id" name="reserv_id"  required="required">
                            <div class="form-group">
                                    <label>First Name:</label>
                                    <input type="text" class="form-control" id= "fname" name="fname" placeholder="First Name" required="required">
                            </div>
                            <div class="form-group">
                                    <label>Last Name:</label>
                                    <input type="text" class="form-control" id= "lname" name="lname" placeholder="Last Name" required="required">
                            </div>
                            <div class="form-group">
                                    <label>Date:</label>
                                    <input type="date" class="form-control" id= "rdate" name="rdate" placeholder="Date">
                            </div>
                            <div class="form-group">
                                    <label>Time:</label>
                                    <select class="form-control" id="time" name="time">
                                        <option>12:00</option>
                                        <option>16:00</option>
                                        <option>18:00</option>
                                        <option>20:00</option>
                                    </select>
                            </div>
                            <div class="form-group">
                                <label>Number of Persons</label>
                                <input type="number" class="form-control" min="2" id="num_guests" name="num_guests" placeholder="Guests" required="required">
                                    <small class="form-text text-muted">* Minimum 2 persons</small>
                            </div>
                            <div class="form-group">
                                <label for="guests">Contact Number</label>
                                <input type="telephone" class="form-control" id="tele" name="tele" placeholder="e.g.: 0167654321" required="required">
                            </div>
                            <div>
                                <label>Remarks</label>
                                <textarea class="form-control" id="comments" name="comments" placeholder="e.g.: Food allergies, etc." rows="3"></textarea>
                                <small class="form-text text-muted">Remarks must be less than 200 characters</small>
                            </div> 
                            <br>
                            <div>
                                <button type="submit" name="update-reserv-submit" class="btn btn-primary btn-lg btn-block">Modify Reservation</button>
                            </div>
                        </form>
                    </div> 	
                </div>        
                <!-- Modal footer -->
                <div class="modal-footer">

                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div> 
            </div>
        </div>
    </div>
</div>



<!--Print/Export Modal-->
<div class="container">
  <!-- The Modal -->
    <div class="modal fade" id="myModal_export">
        <div class="modal-dialog">
            <div class="modal-content">
            <!-- Modal Header -->
                <div class="modal-header">
                  <h4 class="modal-title">Print/Export</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>      
            <!-- Modal body -->
                <div class="modal-body">   
            <!---Print/Export form -->
                    <div class="signup-form">
                        <form action="includes/export.php" method="post">
                            <p>Select reservation and file type: </p><hr>
                            <h5>Reservation:</h5>
                            <div class="form-group text-left">
                                <input type="radio" id="all" name="reservation" value="all" checked> All Reservation
                            </div>
                            <div class="form-group text-left">
                                <input type="radio" id="upcoming" name="reservation" value="upcoming"> Upcoming (Confirmed) Reservation
                            </div>
                            <hr>
                            <h5>File Type:</h5>
                            <div class="form-group text-left">
                                <input type="radio" id="excel" name="file_type" value="excel" checked> Excel
                            </div>
                            <div class="form-group text-left">
                                <input type="radio" id="pdf" name="file_type" value="pdf"> PDF
                            </div>
                            <hr>
                            <div class="form-group text-center">
                                <button type="submit" formtarget="_blank" name="export-reserv" class="fa fa-download-pdf-o btn btn-success btn-lg"> Export</button>
                            </div>
                        </form>
                    </div> 	
                </div>        
                <!-- Modal footer -->
                <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div> 
            </div>
        </div>
    </div>
</div>

<script>
        //Pass data to Edit Modal
        $(document).ready(function(){
            $(document).on('click', '.edit', function(){
                var id=$(this).val();
                var fname=$('#fname'+id).text();
                var lname=$('#lname'+id).text();
                var rdate=$('#rdate'+id).text();
                var time=$('#time'+id).text();
                var num_guests=$('#num_guests'+id).text();
                var tele=$('#tele'+id).text();
                var comments=$('#comments'+id).text();

                $('#myModal_editReserv').modal('show');
                $('#reserv_id').val(id);
                $('#fname').val(fname);
                $('#lname').val(lname);
                $('#rdate').val(rdate);
                $('#time').val(time);
                $('#num_guests').val(num_guests);
                $('#tele').val(tele);
                $('#comments').val(comments);

        });
    });
</script>

