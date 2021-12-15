
<?php

date_default_timezone_set("Asia/Kuala_Lumpur");

if(isset($_SESSION['user_id'])){
    
    require 'includes/dbh.inc.php';
    
    
    $user = $_SESSION['user_id'];
    $role = $_SESSION['role'];

    $upcoming_reserv = array();
    $past_reserv = array();
    
    //rolos pelati
    if($role==1){
        
        $sql = "SELECT * FROM reservation WHERE user_fk = $user ORDER BY rdate DESC";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                if($row["rdate"] >= date('Y-m-d'))
                {
                    $upcoming_reserv[] = $row;
                }
                else
                {
                    $past_reserv[] = $row;
                }
            }
        }

        else 
        {    
            echo "<p class='text-white text-center bg-danger'>Your reservation list is empty!<p>"; 
        }
        //Upcoming
        echo
        '
        <h4 class="text-black text-left">Upcoming Reservation</h4>
        <div class="cart-box-main">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-main table-responsive">
                                    <table class="table text-center" >
                <thead>
                    <tr>
                        <th scope="col" colspan="2">Action</th>
                        <th scope="col">Status</th>
                        <th scope="col">ID</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Guests</th>
                        <th scope="col">Reservation Date</th>
                        <th scope="col">Time</th>
                        <th scope="col">Telephone</th>
                        <th scope="col">Register Date</th>
                        <th scope="col">Comments</th>
                    </tr>
                </thead> 
        ';
        for($i=0;$i<count($upcoming_reserv);$i++)
        {
            echo"
                    <tbody>
                        <tr>

            ";

            if($upcoming_reserv[$i]["rdate"] < date('Y-m-d'))
            {
                echo"
                    <td></td>
                    <td></td>
                ";
            }

            else if($upcoming_reserv[$i]["rdate"] == date('Y-m-d') && $upcoming_reserv[$i]["time_zone"] <= date('H:i', strtotime(date('H:i')) + 7200))
            {
                echo"
                    <td></td>
                    <td></td>
                ";

            }

            else if(($upcoming_reserv[$i]["status"] != "Cancelled" && $upcoming_reserv[$i]["status"] != "Rejected"))
            {
                echo"
                    <td><button value=".$upcoming_reserv[$i]["reserv_id"]." class='btn btn-info btn-sm edit' >Edit</button></td> 
                    <form action='includes/cancel.php' method='POST'>
                    <input name='reserv_id' type='hidden' value=".$upcoming_reserv[$i]["reserv_id"].">
                    <td class='table-danger'><button type='submit' name='delete-submit' class='btn btn-danger btn-sm'>Cancel</button></td>
                ";
            }

            else if($upcoming_reserv[$i]["status"] == "Rejected")
            {
                echo"
                    <td colspan='2'><button value=".$upcoming_reserv[$i]["reserv_id"]." class='btn btn-info btn-sm edit' >Change Reservation</button></td> 
                    
                ";
            }

            else
            {
                echo"<td></td>";
                echo"<td></td>";
            }

            echo'
                            <td>'.$upcoming_reserv[$i]["status"].'</td>   
                            <td>'.$upcoming_reserv[$i]["reserv_id"].'</td>          
                            <td><span id="fname'.$upcoming_reserv[$i]["reserv_id"].'"><strong>'.$upcoming_reserv[$i]["f_name"].' </span><span id="lname'.$upcoming_reserv[$i]["reserv_id"].'">'.$upcoming_reserv[$i]["l_name"].'<strong></span></td>
                            <td><span id="num_guests'.$upcoming_reserv[$i]["reserv_id"].'">'.$upcoming_reserv[$i]["num_guests"].'</td>
                            <td><span id="rdate'.$upcoming_reserv[$i]["reserv_id"].'">'.$upcoming_reserv[$i]["rdate"].'</span></td>
                            <td><span id="time'.$upcoming_reserv[$i]["reserv_id"].'">'.$upcoming_reserv[$i]["time_zone"].'</span></td>
                            <td><span id="tele'.$upcoming_reserv[$i]["reserv_id"].'">'.$upcoming_reserv[$i]["telephone"].'</span></td>
                            <td>'.$upcoming_reserv[$i]["reg_date"].'</td>
                            <td><span id="comments'.$upcoming_reserv[$i]["reserv_id"].'"><textarea readonly>'.$upcoming_reserv[$i]["comment"].'</textarea></span></td>
                            </form>
                        </tr>
                    </tbody>
            '; 
        }
        echo "</table>
            </div>
        </div>
        </div>
        </div>
        </div>
        <br><br>
        ";
        //Past
        echo
        '
        <h4 class="text-black text-left">Past Reservation</h4>
        <div class="cart-box-main">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-main table-responsive">
                                    <table class="table text-center" >
                <thead>
                    <tr>
                        <th scope="col">Full Name</th>
                        <th scope="col">Guests</th>
                        <th scope="col">Reservation Date</th>
                        <th scope="col">Time Zone</th>
                        <th scope="col">Telephone</th>
                        <th scope="col">Register Date</th>
                        <th scope="col">Status</th>
                        <th scope="col">Comments</th>
                    </tr>
                </thead> 
        ';
        for($i=0;$i<count($past_reserv);$i++)
        {
            echo"
                    <tbody>
                        <tr>
                            <form action='includes/cancel.php' method='POST'>
                            <input name='reserv_id' type='hidden' value=".$past_reserv[$i]["reserv_id"].">
            ";

            echo" "; //no more action for past reservation
            echo"          
                            <td><strong>".$past_reserv[$i]["f_name"]." ".$past_reserv[$i]["l_name"]."<strong></td>
                            <td>".$past_reserv[$i]["num_guests"]."</td>
                            <td>".$past_reserv[$i]["rdate"]."</td>
                            <td>".$past_reserv[$i]["time_zone"]."</td>
                            <td>".$past_reserv[$i]["telephone"]."</td>
                            <td>".$past_reserv[$i]["reg_date"]."</td>
                            <td>".$past_reserv[$i]["status"]."</td>  
                            <td><textarea readonly>".$past_reserv[$i]["comment"]."</textarea></td>
                            </form>
                        </tr>
                    </tbody>
            "; 
        }
        echo "</table>
            </div>
        </div>
        </div>
        </div>
        </div>
        <br><br>
        ";
    }
    

    //Admin
    
    else if($role==2){
        $sql = "SELECT * FROM reservation ORDER BY rdate DESC";
        
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                if($row["rdate"] >= date('Y-m-d'))
                {
                    $upcoming_reserv[] = $row;
                }
                else
                {
                    $past_reserv[] = $row;
                }
            }
        }

        else 
        {    
            echo "<p class='text-white text-center bg-danger'>Your reservation list is empty!<p>"; 
        }
        //Upcoming
        echo
        '
        <div class="d-flex">
            <div class="mr-auto p-2">
                <h4 class="text-black text-left">Upcoming Reservation</h4>
            </div>
            <div class="p-2">
                <button class="fa fa-print btn btn-success btn-l" data-toggle="modal" data-target="#myModal_export"> &nbsp; Print  / Export Reservation List</button> 
            </div>
        </div>

        <div class="cart-box-main">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-main table-responsive">
                                    <table class="table text-center" >
                <thead>
                    <tr>
                        <th scope="col" colspan="3">Action</th>
                        <th scope="col">Status</th>
                        <th scope="col">ID</th>
                        <th scope="col">Full Name</th>
                        <th scope="col">Guests</th>
                        <th scope="col">Reservation Date</th>
                        <th scope="col">Time Zone</th>
                        <th scope="col">Telephone</th>
                        <th scope="col">Register Date</th>
                        <th scope="col">Comments</th>
                    </tr>
                </thead> 
        ';
        for($i=0;$i<count($upcoming_reserv);$i++)
        {
            echo"
                    <tbody>
                        <tr>

            ";
            if($upcoming_reserv[$i]["rdate"] == date('Y-m-d') && $upcoming_reserv[$i]["time_zone"] < date('H:i'))
            {
                echo"
                    <td></td><td></td><td></td>
                ";
            }
            else if($upcoming_reserv[$i]["status"] == "Pending" ||$upcoming_reserv[$i]["status"] == "Edited")
            {
                echo"
                <td><button value=".$upcoming_reserv[$i]["reserv_id"]."  class='btn btn-info btn-sm edit' >Edit</button></td> 
                <form action='includes/adminActionReserv.php' method='POST'>
                <input name='reserv_id' type='hidden' value=".$upcoming_reserv[$i]["reserv_id"].">
                <td><button type='submit' name='confirm-submit' class='btn btn-success btn-sm' > Confirm</button></td>
                <td><button type='submit' name='reject-submit' class='btn btn-danger btn-sm' > Reject</button></td>
                ";
            }  
            else if($upcoming_reserv[$i]["status"] == "Confirmed" )
            {
                echo"
                    <td><button value=".$upcoming_reserv[$i]["reserv_id"]."  class='btn btn-info btn-sm edit' >Edit</button></td> 
                    <td><button type='submit' disabled name='confirm-submit' class='btn btn-secondary btn-sm'>Confirm</button></td>
                    <td><button type='submit' disabled name='reject-submit' class='btn btn-secondary btn-sm'>Reject</button></td>
                ";
            }
            else
            {
                echo"
                    <td><button value=".$upcoming_reserv[$i]["reserv_id"]." disabled class='btn btn-secondary btn-sm edit' >Edit</button></td> 
                    <td><button type='submit' disabled name='confirm-submit' class='btn btn-secondary btn-sm'>Confirm</button></td>
                    <td><button type='submit' disabled name='reject-submit' class='btn btn-secondary btn-sm'>Reject</button></td>
                ";
            }

            echo'
                            <td>'.$upcoming_reserv[$i]["status"].'</td>   
                            <td>'.$upcoming_reserv[$i]["reserv_id"].'</td>          
                            <td><span id="fname'.$upcoming_reserv[$i]["reserv_id"].'"><strong>'.$upcoming_reserv[$i]["f_name"].' </span><span id="lname'.$upcoming_reserv[$i]["reserv_id"].'">'.$upcoming_reserv[$i]["l_name"].'<strong></span></td>
                            <td><span id="num_guests'.$upcoming_reserv[$i]["reserv_id"].'">'.$upcoming_reserv[$i]["num_guests"].'</td>
                            <td><span id="rdate'.$upcoming_reserv[$i]["reserv_id"].'">'.$upcoming_reserv[$i]["rdate"].'</span></td>
                            <td><span id="time'.$upcoming_reserv[$i]["reserv_id"].'">'.$upcoming_reserv[$i]["time_zone"].'</span></td>
                            <td><span id="tele'.$upcoming_reserv[$i]["reserv_id"].'">'.$upcoming_reserv[$i]["telephone"].'</span></td>
                            <td>'.$upcoming_reserv[$i]["reg_date"].'</td>
                            <td><span id="comments'.$upcoming_reserv[$i]["reserv_id"].'"><textarea readonly>'.$upcoming_reserv[$i]["comment"].'</textarea></span></td>
                            </form>
                        </tr>
                    </tbody>
            '; 
        }
        echo "</table>
            </div>
        </div>
        </div>
        </div>
        </div>
        <br><br>
        ";
        //Past
        echo
        '
        <h4 class="text-black text-left">Past Reservation</h4>
        <div class="cart-box-main">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-main table-responsive">
                                    <table class="table text-center" >
                <thead>
                    <tr>
                        <th scope="col">Full Name</th>
                        <th scope="col">Guests</th>
                        <th scope="col">Reservation Date</th>
                        <th scope="col">Time Zone</th>
                        <th scope="col">Telephone</th>
                        <th scope="col">Register Date</th>
                        <th scope="col">Status</th>
                        <th scope="col">Comments</th>
                    </tr>
                </thead> 
        ';
        for($i=0;$i<count($past_reserv);$i++)
        {
            echo"
                    <tbody>
                        <tr>
                            <form action='includes/cancel.php' method='POST'>
                            <input name='reserv_id' type='hidden' value=".$past_reserv[$i]["reserv_id"].">
            ";

            echo" "; //no more action for past reservation
            echo"           
                            <td><strong>".$past_reserv[$i]["f_name"]." ".$past_reserv[$i]["l_name"]."<strong></td>
                            <td>".$past_reserv[$i]["num_guests"]."</td>
                            <td>".$past_reserv[$i]["rdate"]."</td>
                            <td>".$past_reserv[$i]["time_zone"]."</td>
                            <td>".$past_reserv[$i]["telephone"]."</td>
                            <td>".$past_reserv[$i]["reg_date"]."</td>
                            <td>".$past_reserv[$i]["status"]."</td> 
                            <td><textarea readonly>".$past_reserv[$i]["comment"]."</textarea></td>
                            </form>
                        </tr>
                    </tbody>
            "; 
        }
        echo "</table>
            </div>
        </div>
        </div>
        </div>
        </div>
        <br><br>
        ";
    }
    echo '
        
    ';

mysqli_close($conn);
}

