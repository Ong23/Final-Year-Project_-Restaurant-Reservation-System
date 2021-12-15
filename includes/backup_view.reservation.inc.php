<?php



if(isset($_SESSION['user_id'])){
    
    require 'includes/dbh.inc.php';
    
    
    $user = $_SESSION['user_id'];
    $role = $_SESSION['role'];
    
    //rolos pelati
    if($role==1){
        
        $sql = "SELECT * FROM reservation WHERE user_fk = $user ORDER BY rdate DESC";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo
            '
            <div class="cart-box-main">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-main table-responsive">
                                        <table class="table text-center" >
                    <thead>
                        <tr>
                            <th scope="col">Action</th>
                            <th scope="col">Status</th>
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
            while($row = $result->fetch_assoc()) {
                //if($row["rdate"] > date('Y-m-d'))
                echo"
                    <tbody>
                    <tr>
                    <form action='includes/cancel.php' method='POST'>
                    <input name='reserv_id' type='hidden' value=".$row["reserv_id"].">
                ";   
                if($row["status"] != "Cancelled" && $row["status"] != "Rejected"){
                    echo"<td class='table-danger'><button type='submit' name='delete-submit' class='btn btn-danger btn-sm'>Cancel</button></td>
                    ";
                } 
                else{
                    echo"<td></td>
                    ";
                }

                echo"
                
                            <td>".$row["status"]."</td>            
                            <th scope='row'>".$row["f_name"]." ".$row["l_name"]."</th>
                            <td>".$row["num_guests"]."</td>
                            <td>".$row["rdate"]."</td>
                            <td>".$row["time_zone"]."</td>
                            <td>".$row["telephone"]."</td>
                            <td>".$row["reg_date"]."</td>
                            <td><textarea readonly>".$row["comment"]."</textarea></td>

                ";
                echo"
                    </form>
                    </tr>
                    </tbody>
                ";
            } 
                echo "  
                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <br><br>
                ";
            }

    else {    
        echo "<p class='text-white text-center bg-danger'>Your reservation list is empty!<p>"; }
    }
    
    
    //Admin
    
    else if($role==2){
        $sql = "SELECT * FROM reservation ORDER BY rdate DESC";
        
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo
            '
            <div class="cart-box-main">
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-main table-responsive">
                                        <table class="table text-center" >
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">Guests</th>
                            <th scope="col">Tables</th>
                            <th scope="col">Reservation Date</th>
                            <th scope="col">Time Zone</th>
                            <th scope="col">Telephone</th>
                            <th scope="col">Register Date</th>
                            <th scope="col">Comments</th>
                            <th scope="col">Status</th>
                            <th colspan="2" scope="col">Action</th>
                        </tr>
                    </thead> ';
            while($row = $result->fetch_assoc()) {
            echo"
                    <tbody>
                        <tr>
                        <form action='includes/adminActionReserv.php' method='POST'>
                        <input name='reserv_id' type='hidden' value=".$row["reserv_id"].">
                        <th scope='row'>".$row["reserv_id"]."</th> 
                        <td>".$row["f_name"]." ".$row["l_name"]."</td>
                        <td>".$row["num_guests"]."</td>
                        <td>".$row["num_tables"]."</td>
                        <td>".$row["rdate"]."</td>
                        <td>".$row["time_zone"]."</td>
                        <td>".$row["telephone"]."</td>
                        <td>".$row["reg_date"]."</td>
                        <td><textarea readonly>".$row["comment"]."</textarea></td>
                        <td>".$row["status"]."</td>
            ";
            if($row["status"] == "Pending"){
                echo"<td class='table-success'><button type='submit' name='confirm-submit' class='btn btn-success btn-sm'>Confirm</button></td>
                <td class='table-danger'><button type='submit' name='reject-submit' class='btn btn-danger btn-sm'>Reject</button></td>
                ";
            }  
            echo"
                        </form>
                        </tr>
                    </tbody>
            ";
            }   
            echo "
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <br><br>  
            ";
        
        
    }
    else {    echo "<p class='text-white text-center bg-danger'>Your reservation list is empty!<p>"; }
    
    }
    


mysqli_close($conn);
}


