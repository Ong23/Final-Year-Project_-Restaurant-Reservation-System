<?php

date_default_timezone_set("Asia/Kuala_Lumpur");

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
            '<div class="cart-box-main">
                <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-main table-responsive">
                            <table class="table text-center" >
                    <thead>
                        <tr>
                            <th scope="col" colspan="3">Action</th>
                            <th scope="col">ID</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">Guests</th>
                            <th scope="col">Reservation Date</th>
                            <th scope="col">Time</th>
                            <th scope="col">Telephone</th>
                            <th scope="col">Comments</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead> 
            ';
            while($row = $result->fetch_assoc()) {                
                if($row["rdate"] >= date('Y-m-d'))
                {    
                    echo"
                            <tbody>
                                <tr>
                                <form action='includes/cancel.php' method='POST'>
                    ";
                    
                    if($row["rdate"] == date('Y-m-d') && $row["time_zone"] <= date('H:i', strtotime(date('H:i')) + 7200))
                    {
                        echo"
                                <td><button type='submit' name='view' class='fa fa-eye btn btn-success btn-sm'> View</button></td>
                                <td><button disabled type='submit' name='edit' class='fa fa-edit btn btn-secondary btn-sm'> Edit</button></td>
                                <td><button disabled type='submit' name='preorder' class='fa fa-shopping-cart btn btn-secondary btn-sm'> Pre-Order</button></td>
                        ";    
                    }
                    else if($row["status"]== 'Rejected' || $row["status"]== 'Cancelled')
                    {
                        echo"
                                <td><button type='submit' name='view' class='fa fa-eye btn btn-success btn-sm'> View</button></td>
                                <td><button disabled type='submit' name='edit' class='fa fa-edit btn btn-secondary btn-sm'> Edit</button></td>
                                <td><button disabled type='submit' name='preorder' class='fa fa-shopping-cart btn btn-secondary btn-sm'> Pre-Order</button></td>
                        ";  
                    }
                    else 
                    {
                        echo"
                            <td><button type='submit' name='view' class='fa fa-eye btn btn-success btn-sm'> View</button></td>
                            <td><button type='submit' name='edit' class='fa fa-edit btn btn-primary btn-sm'> Edit</button></td>
                            <td><button type='submit' name='preorder' class='fa fa-shopping-cart btn btn-info btn-sm'> Pre-Order</button></td>
                        ";
                    }
                    
                        echo"
                                    <input name='reserv_id' type='hidden' value=".$row["reserv_id"].">
                                    <td>".$row["reserv_id"]."</td>
                                    <th scope='row'>".$row["f_name"]." ".$row["l_name"]."</th>
                                    <td>".$row["num_guests"]."</td>
                                    <td>".$row["rdate"]."</td>
                                    <td>".$row["time_zone"]."</td>
                                    <td>".$row["telephone"]."</td>
                                    <td><textarea readonly>".$row["comment"]."</textarea></td>
                                    <td>".$row["status"]."</td>

                        ";   
                    echo"
                        </form>
                        </tr>
                        </tbody>
                    ";
                }
                 //$row["rdate"] == date('Y-m-d') && $row["time_zone"] <= date('H:i', strtotime(date('H:i')) + 7200)
                //
                
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
            '<div class="cart-box-main">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="table-main table-responsive">
                            <table class="table text-center" >
                    <thead>
                        <tr>
                            <th scope="col" colspan="3">Action</th>
                            <th scope="col">ID</th>
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
            while($row = $result->fetch_assoc()) {
                echo"
                        <tbody>
                            <tr>
                            <form action='includes/cancel.php' method='POST'>
                ";
                if($row["rdate"] < date('Y-m-d'))
                {
                    echo"
                        <td><button type='submit' name='view' class='fa fa-eye btn btn-success btn-sm'> View</button></td>
                        <td><button disabled type='submit' name='edit' class='fa fa-edit btn btn-secondary btn-sm'> Edit</button></td>
                        <td><button disabled type='submit' name='preorder' class='fa fa-shopping-cart btn btn-secondary btn-sm'> Pre-Order</button></td>
                    ";
                }
                else if($row["rdate"] == date('Y-m-d') && $row["time_zone"] <= date('H:i', strtotime(date('H:i')) + 7200))
                {
                    echo"
                            <td><button type='submit' name='view' class='fa fa-eye btn btn-success btn-sm'> View</button></td>
                            <td><button disabled type='submit' name='edit' class='fa fa-edit btn btn-secondary btn-sm'> Edit</button></td>
                            <td><button disabled type='submit' name='preorder' class='fa fa-shopping-cart btn btn-secondary btn-sm'> Pre-Order</button></td>
                    ";    
                }
                else if($row["status"]== 'Rejected' || $row["status"]== 'Cancelled')
                {
                    echo"
                            <td><button type='submit' name='view' class='fa fa-eye btn btn-success btn-sm'> View</button></td>
                            <td><button disabled type='submit' name='edit' class='fa fa-edit btn btn-secondary btn-sm'> Edit</button></td>
                            <td><button disabled type='submit' name='preorder' class='fa fa-shopping-cart btn btn-secondary btn-sm'> Pre-Order</button></td>
                    ";  
                }
                else 
                {
                    echo"
                        <td><button type='submit' name='view' class='fa fa-eye btn btn-success btn-sm'> View</button></td>
                        <td><button type='submit' name='edit' class='fa fa-edit btn btn-primary btn-sm'> Edit</button></td>
                        <td><button type='submit' name='preorder' class='fa fa-shopping-cart btn btn-info btn-sm'> Pre-Order</button></td>
                    ";
                }
                echo"
                            <input name='reserv_id' type='hidden' value=".$row["reserv_id"].">
                            <td>".$row["reserv_id"]."</td>
                            <th scope='row'>".$row["f_name"]." ".$row["l_name"]."</th>
                            <td>".$row["num_guests"]."</td>
                            <td>".$row["rdate"]."</td>
                            <td>".$row["time_zone"]."</td>
                            <td>".$row["telephone"]."</td>
                            <td>".$row["reg_date"]."</td>
                            <td>".$row["status"]."</td>
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
    else {    echo "<p class='text-white text-center bg-danger'>Your reservation list is empty!<p>"; }
    
    }
    


mysqli_close($conn);
}


