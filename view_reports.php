<?php
require "header.php";
?>
    
<br><br>
<div class="container">
<h3 class="text-center"><br>View Reports<br></h3>   
 <div class="row">
        <div class="col-md-6 offset-md-3">     

<?php
    //Top dishes: refer to this website
    //https://www.c-sharpcorner.com/blogs/how-to-get-all-row-count-for-all-tables-in-sql-server-database
    //https://www.mysqltutorial.org/mysql-find-duplicate-values/
    if(isset($_SESSION['user_id']))
    {
        require 'includes/dbh.inc.php';

        $user = $_SESSION['user_id'];
        $role = $_SESSION['role'];    

        if($role==2)
        {
            $from_date = '2021-01-01';
            $until_date = '2021-12-31';

            echo '  
                
            <div class="signup-form">
                <form target="_blank" action="includes/view_report.inc.php" method="POST">
                    <div class="form-group">
                        <label>Select Report</label>
                        <select id="report" name="report" onchange="report_type(this.value)" class="form-control">
                            <option value="guest_frequency">Table Booking Frequency</option>
                            <option value="top_dishes">Most Popular Dishes</option>
                        </select>
                    </div>
                    <hr>
                    <div class="form-group" id="guest_freq_input_field">
                        <label>From: </label>
                        <input type="date" class="form-control" value="'.$from_date.'" name="from_date" id="from_date" placeholder="From" >
                        <label>To: </label>
                        <input type="date" class="form-control" value="'.$until_date.'" name="until_date" id="until_date" placeholder="Until">
                        <label>Report Type: </label>
                        <select id="type" name="type" class="form-control">
                            <option value="top_10">Top 10 Customers</option>
                            <option value="all">All Customers</option>
                        </select>
                    </div>
            ';
            echo'

                    <div class="form-group" id="popular_dish_input_field" style="display: none; ">
                        <label>Report Type: </label>
                        <select id="d_type" name="d_type" class="form-control">
                            <option value="top_10">Top 10 Dish</option>
                            <option value="all">All Dish</option>
                            <option value="by_category">By Dish Category</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="report_submit" class="btn btn-dark btn-lg btn-block">View Report</button>
                    </div>
                </form>
                <br><br>
            </div>
            ';  
            /*
            <label>Dish Category: </label>
            <select id="category" name="category" class="form-control">
            ';
            $sql = "SELECT * FROM `dish category`";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo '<option value="'.$row['dish_cat_id'].'">'.$row['category_desc'].'</option>';
            }
            echo'
            </select>
            */
            mysqli_close($conn);
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

?>

<?php
/*
    require 'includes/dbh.inc.php';

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
            $sql = "SELECT dish.dish_id, dish.dish_name, COUNT(pod.dish_id) AS number_of_dishes 
            FROM dish LEFT JOIN `preorder dish` AS pod 
            ON (dish.dish_id = pod.dish_id)
            GROUP BY dish.dish_id 
            ORDER BY number_of_dishes DESC 
            LIMIT 10";

            $title = '';

            $counter = 1;
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                
                echo
                '
                
                            <div class="container">
                            <h2 class="text-black text-center">'.$title.'</h2>
                            <h2 class="text-black text-center">Top 10 Most Popular Food (Pre-Ordered)</h2>
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
                while($row = $result->fetch_assoc()) 
                {
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

    mysqli_close($conn);
    */
?>


</div>
<br><br>
<script>
    function report_type(val){
        var guest_freq_element=document.getElementById('guest_freq_input_field');
        var popular_dish_element=document.getElementById('popular_dish_input_field');

        if(val=='guest_frequency')
        {
            guest_freq_element.style.display='block';
            popular_dish_element.style.display='none';
        }
        else  
        {
            guest_freq_element.style.display='none';
            popular_dish_element.style.display='block';
        }
    }

</script> 
