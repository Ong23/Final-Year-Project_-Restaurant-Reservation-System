<?php
require "header.php";
?>
<br><br>
<div class="container">
        

<?php

    require 'includes/dbh.inc.php';
    if(isset($_SESSION['user_id']))
    {
        $user = $_SESSION['user_id'];
        $role = $_SESSION['role'];
        if($role==2)
        {
            if(isset($_GET['rpt']))
            {
                $report = $_GET['rpt'];

                if($report=='guests_freq')
                {
                    if(isset($_GET['errorRpt']))
                    {
                        if($_GET['errorRpt']='invalidbtwdate')
                        {
                            echo '
                            <div class="container">
                            <div class="row">
                            <div class="col-md-6 offset-md-3">     
                                <h5 class="text-danger text-center">
                                Invalid date selected! "From Date" must be earlier (smaller) than "Until Date"
                                </h5> 
                            </div>
                            </div>
                            </div>  
                            ';
                        }
                    }
                    else
                    {
                        $f_date = $_GET['from'];
                        $to_date = $_GET['to'];
                        $type = $_GET['type'];
                        if($_GET['type'] == 'top_10')
                        {
                            $title = '(TOP 10 Customers)';
                            $sql_limit = 'LIMIT 10';
                        }
                        else if($_GET['type'] == 'all')
                        {
                            $title = '';
                            $sql_limit = '';
                        }

                        //Export Buttons
                        echo'
                            <br><br>
                            <form action="includes/export.php" method="post">
                                <div>
                                    <input type="hidden" id="rpt" name="rpt" value="'.$report.'">
                                    <input type="hidden" id="from_date" name="from_date" value="'.$f_date.'">
                                    <input type="hidden" id="to_date" name="to_date" value="'.$to_date.'">
                                    <input type="hidden" id="type" name="type" value="'.$type.'">
                                    <button type="submit" formtarget="_blank" name="excel-submit" class="fa fa-download btn btn-success btn-sm">  Export to Excel File</button> 
                                    <button type="submit" formtarget="_blank" name="pdf-submit" class="fa fa-file-pdf-o btn btn-info btn-sm"> Export to PDF File</button>
                                </div>
                            </form>
                        ';

                        $sql = "SELECT users.user_id, users.uidUsers, max(reservation.rdate) AS `last_rdate`,users.emailUsers, COUNT(reservation.user_fk) AS number_of_reservation 
                        FROM users LEFT JOIN reservation 
                        ON (users.user_id = reservation.user_fk) 
                        AND reservation.rdate BETWEEN '$f_date' AND '$to_date'
                        WHERE users.role_id != 2
                        GROUP BY users.user_id 
                        ORDER BY number_of_reservation DESC 
                        $sql_limit";

                        $total_no_of_reserv = 0;
                        $counter = 1;
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            
                            echo
                            '
                            
                                        <div class="container">
                                        <h2 class="text-black text-center">'.$title.'</h2>
                                        <h2 class="text-black text-center">Table Booking Frequency</h2>
                                        <h4 class="text-black text-center">From '.$f_date.' To '.$to_date.'</h4>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="table-main table-responsive">
                                                        <table class="table text-center" >
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">No.</th>
                                                                    <th scope="col">ID</th>
                                                                    <th scope="col">User Name</th>
                                                                    <th scope="col">Email</th>
                                                                    <th scope="col">Last Reservation Date</th>
                                                                    <th scope="col" style="width:248px">No. of Reservation Made</th>
                                                                </tr>
                                                            </thead> 
                            ';
                            while($row = $result->fetch_assoc()) 
                            {
                                $total_no_of_reserv += $row['number_of_reservation'];
                                echo'
                                                            <tbody>
                                                                <tr>
                                                                    <td>'.$counter.'</td>
                                                                    <td>'.$row['user_id'].'</td>
                                                                    <td>'.$row['uidUsers'].'</td>
                                                                    <td>'.$row['emailUsers'].'</td>
                                ';
                                if($row['last_rdate'] == NULL)
                                {
                                    echo'   
                                        <td>No reservation made</td>
                                    ';
                                }
                                else
                                {
                                    echo'
                                        <td>'.$row['last_rdate'].'</td>
                                    ';
                                }
                                echo'
                                                                    <td>'.$row['number_of_reservation'].'</td>
                                                                </tr>
                                                            <tbody>
                                ';
                                $counter++;
                            }
                            echo'
                                                        <tbody>
                                                            <tr>
                                                                <th colspan="5" class="text-right">Total</th>
                                                                <th>'.$total_no_of_reserv.'</th>
                                                            </tr>
                                                        </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            </div>
                            ';
                        }
                    }
                        
                }
                else if($report=='top_dishes')
                {
                    $title = '';
                    $title_end = '';
                    $type = $_GET['type'];

                    if($_GET['type'] == 'top_10')
                    {
                        $title = 'TOP 10';
                        $sql_limit = 'ORDER BY number_of_dishes DESC LIMIT 10';
                    }
                    else if($_GET['type'] == 'all')
                    {
                        $title = '';
                        $sql_limit = 'ORDER BY number_of_dishes DESC';
                    }
                    else if($_GET['type'] == 'by_category')
                    {
                        $title_end = 'By Dish Category';
                        $sql_limit = 'ORDER BY dish.dish_cat_id ASC, number_of_dishes DESC';
                    }

                    //Export Buttons
                    echo'
                        <br><br>
                        <form action="includes/export.php" method="post">
                            <div>
                                <input type="hidden" id="rpt" name="rpt" value="'.$report.'">
                                <input type="hidden" id="type" name="type" value="'.$type.'">
                                <button type="submit" formtarget="_blank" name="excel-submit" class="fa fa-download btn btn-success btn-sm">  Export to Excel File</button> 
                                <button type="submit" formtarget="_blank" name="pdf-submit" class="fa fa-file-pdf-o btn btn-info btn-sm"> Export to PDF File</button>
                            </div>
                        </form>
                    ';

                    $sql = "SELECT dish.dish_id, dish.dish_name, dish.dish_cat_id, COUNT(pod.dish_id) AS number_of_dishes 
                    FROM dish LEFT JOIN `preorder dish` AS pod 
                    ON (dish.dish_id = pod.dish_id)
                    GROUP BY dish.dish_id 
                    $sql_limit";


                    $counter = 1;
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        
                        echo
                        '
                        
                                    <div class="container">
                                    <h2 class="text-black text-center">'.$title.' Most Popular Food (Pre-Ordered)</h2>
                                    <h2 class="text-black text-center">'.$title_end.' </h2>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="table-main table-responsive">
                                                    <table class="table text-center" >
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">No.</th>
                                                                <th scope="col">Dish ID</th>
                                                                <th scope="col">Dish Name</th>
                                                                <th scope="col" style="width:248px">Number of Pre-Orders</th>
                                                            </tr>
                                                        </thead> 
                        ';
                        $repeat = false;
                        $id = 0;
                        while($row = $result->fetch_assoc()) 
                        {
                            if($_GET['type'] == 'by_category')
                            {
                                if($id != $row['dish_cat_id'])
                                {
                                    $repeat = false;
                                    $counter = 1;
                                }

                                if(!$repeat)
                                {
                                    
                                    $id = $row['dish_cat_id'];
                                    $sql2 = "SELECT * FROM `dish category` WHERE dish_cat_id = $id";
                                    $result2 = $conn->query($sql2);
                                    while ($row2 = $result2->fetch_assoc()) {
                                        $cat_desc = $row2['category_desc'];
                                    }
                                    echo'
                                            <tr class="table-active">
                                                <th colspan="4" class="text-left">Dish Category: '.$cat_desc.'</th>
                                            </tr>
                                    ';
                                    $repeat = true;
                                }
                            }
                            

                                echo'
                                                            <tbody>
                                                                <tr>
                                                                    <td>'.$counter.'</td>
                                                                    <td>'.$row['dish_id'].'</td>
                                                                    <td>'.$row['dish_name'].'</td>
                                                                    <td>'.$row['number_of_dishes'].'</td>
                                                                </tr>
                                                            <tbody>
                                ';
                                $counter++;
                        }
                        echo'
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                        </div>
                        ';
                    }
                }
            }
        }
        else
        {
            echo "<p class='text-white text-center bg-danger'>You have no authorization to access the report!<p>";
        }
    }
    else
    {
        
        echo '	<p class="text-center text-danger"><br>You are currently not logged in!<br></p>
           <p class="text-center">You need to 
           <a class="text-primary" data-toggle="modal" data-target="#myModal_reg">create account</a> or 
            <a class="text-primary" data-toggle="modal" data-target="#myModal_login">login</a>
           to make a reservation!<br><br><p>'; 
        
    }

    mysqli_close($conn);
?>

