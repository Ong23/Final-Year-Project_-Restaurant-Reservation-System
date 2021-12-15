<?php
require "header.php";
?>
    
<br><br>
<div class="container">
<h3 class="text-center"><br>Food Pre-Order List<br></h3>     


<?php

date_default_timezone_set("Asia/Kuala_Lumpur");

require 'includes/dbh.inc.php';

    if(isset($_SESSION['user_id'])){
        $reserv_id = $_GET['reserv_id'];
        $user = $_SESSION['user_id'];
        $role = $_SESSION['role'];

        if($role==1){

            //Get User Id for authorization
            $records = mysqli_query($conn,"SELECT * FROM reservation WHERE reserv_id =$reserv_id LIMIT 1");
            while($data = mysqli_fetch_array($records))
            {
                $uid = $data['user_fk'];
                $rdate = $data['rdate'];
                $status = $data['status'];
            }
            if($uid == $_SESSION['user_id']){
                $sql = "SELECT * FROM dish 
                INNER JOIN `preorder dish` AS po 
                ON dish.dish_id=po.dish_id AND reserv_id = $reserv_id
                ";
                $result = $conn->query($sql);
                $dish_name_arr = array();

                if ($result->num_rows > 0) {
                    //Need modify
                    if($rdate < date('Y-m-d'))
                    {
                        echo"";
                    }
                    else
                    {
                        if($status == 'Confirmed' || $status == 'Pending' || $status == 'Edited')
                        {
                            echo"
                                <br>
                                <button class='fa fa-plus btn btn-lg btn-success' onclick='edit_food($reserv_id)'>
                                    &nbsp; Edit Food Pre-Order List
                                </button>
                                <br>
                            ";
                        }
                        else
                        {
                            echo"";
                        }
                    }
                    while($row = $result->fetch_assoc()) //Retriee Data
                    {
                        $PO_dish = array();
                        $PO_dish['id'] = $row['PODish_id'];
                        $PO_dish['name'] = $row['dish_name'];
                        $PO_dish['photo'] = $row['dish_photo'];
                        $props[] = $PO_dish;
        
                        array_push($dish_name_arr, $row['dish_id']);
                        /*
                        echo"
                            <tbody>
                                <tr>
                                    <form action='includes/modifyPODish.php' method='POST'>
                                        <input type='hidden' id='dish_id' name='dish_id' value=".$row['dish_id'].">
                                        <td style='width: 200px'>
                                            <img class='img-fluid' src=".$row["dish_photo"]." alt='' />
                                        </td>
                                        <td>".$row["dish_name"]."</td>
                                        <td><button type='submit' name='add-submit' class='btn btn-success btn-sm'>Add</button></td>      
                                        <td><button type='submit' name='remove-submit' class='btn btn-danger btn-sm'>Remove</button></td>
                        ";    
                        echo"
                                    </form>
                                </tr>
                                </tbody>
                        ";
                            } 
                                echo "  </table>";
                                */
                    }
                    $array_PODish = array_count_values($dish_name_arr);
                     
                    echo
                    '
                        <table class="table table-hover table-responsive-sm text-center">
                            <thead>
                                <tr>
                                    <th scope="col">Photo</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Price(RM)</th>
                                    <th scope="col">Quantity</th>
                                </tr>
                            </thead> 
                    ';
                    //display result
                    foreach($array_PODish as $key => $val)
                    {
                        echo"
                            <tbody>
                                <tr>
                                    <form action='includes/modifyPODish.php' method='POST'>
                        ";
                        $sql2 = "SELECT * FROM dish WHERE dish_id='$key'";
                        $result = $conn->query($sql2);
                        while($row = $result->fetch_assoc()) //Dish Photo
                        {
                             echo"
                                    <td style='width: 200px'>
                                        <img class='img-fluid' src=".$row["dish_photo"]." alt='' />
                                    </td>
                                    <td>".$row["dish_name"]."</td>
                                    <td>".$row["price"]."</td>
                            ";
                        }
                        echo"
                                    <input type='hidden' value='$key' name='dish_name'>
                                    <input type='hidden' value='$reserv_id' name='rid'>
                                    <td>".$val."</td>
                        ";    
                        echo"
                                    </form>
                                </tr>
                            </tbody>
                        ";
                        }
        
                        echo '</table>';
                    }
                        else {    
                            echo "<h5 class='text-center'>Pre-Order Food list in this reservation (ID: $reserv_id) is empty!<h5><br><br><br>"; 
                            if($rdate > date('Y-m-d'))
                            {
                                echo "
                                    <div class='container'>
                                        <div class='row'>
                                            <div class='col text-center'>
                                                <button class='btn btn-success btn-lg' onclick='something($reserv_id)'>Pre-Order Food Now!</button>
                                            </div>
                                        </div>
                                    </div>
                                ";
                            }
                            
                        }
                    }
                    else
                    {
                        echo "<br><br><p class='text-center text-danger'>You have no authorization to view other user's pre-ordered food.</p>";
                    }
                    
                }
        
        
        //Admin
        
        else if($role==2){
            $records = mysqli_query($conn,"SELECT * FROM reservation WHERE reserv_id =$reserv_id LIMIT 1");
            while($data = mysqli_fetch_array($records))
            {
                $uid = $data['user_fk'];
                $rdate = $data['rdate'];
                $status = $data['status'];
            }
            $sql = "SELECT * FROM dish 
            INNER JOIN `preorder dish` AS po 
            ON dish.dish_id=po.dish_id AND reserv_id = $reserv_id
            ";
            $result = $conn->query($sql);
            $dish_name_arr = array();

        if ($result->num_rows > 0) {
            
            if($rdate < date('Y-m-d'))
                    {
                        echo"";
                    }
                    else
                    {
                        if($status == 'Confirmed' || $status == 'Pending' || $status == 'Edited')
                        {
                            echo"
                                <br>
                                <button class='fa fa-plus btn btn-lg btn-success' onclick='edit_food($reserv_id)'>
                                    &nbsp; Edit Food Pre-Order List
                                </button>
                                <br>
                            ";
                        }
                        else
                        {
                            echo"";
                        }
                    }
            while($row = $result->fetch_assoc()) //Retriee Data
            {
                $PO_dish = array();
                $PO_dish['id'] = $row['PODish_id'];
                $PO_dish['name'] = $row['dish_name'];
                $PO_dish['photo'] = $row['dish_photo'];
                $props[] = $PO_dish;

                array_push($dish_name_arr, $row['dish_id']);
                /*
                echo"
                    <tbody>
                        <tr>
                            <form action='includes/modifyPODish.php' method='POST'>
                                <input type='hidden' id='dish_id' name='dish_id' value=".$row['dish_id'].">
                                <td style='width: 200px'>
                                    <img class='img-fluid' src=".$row["dish_photo"]." alt='' />
                                </td>
                                <td>".$row["dish_name"]."</td>
                                <td><button type='submit' name='add-submit' class='btn btn-success btn-sm'>Add</button></td>      
                                <td><button type='submit' name='remove-submit' class='btn btn-danger btn-sm'>Remove</button></td>
                ";    
                echo"
                            </form>
                        </tr>
                        </tbody>
                ";
                    } 
                        echo "  </table>";
                        */
            }
            $array_PODish = array_count_values($dish_name_arr);
             
            echo
            '
                <table class="table table-hover table-responsive-sm text-center">
                    <thead>
                        <tr>
                            <th scope="col">Photo</th>
                            <th scope="col">Name</th>
                            <th scope="col">Price(RM)</th>
                            <th scope="col">Quantity</th>
                        </tr>
                    </thead> 
            ';
            //display result
            foreach($array_PODish as $key => $val)
            {
                echo"
                    <tbody>
                        <tr>
                            <form action='includes/modifyPODish.php' method='POST'>
                ";
                $sql2 = "SELECT * FROM dish WHERE dish_id='$key'";
                $result = $conn->query($sql2);
                while($row = $result->fetch_assoc()) //Dish Photo
                {
                     echo"
                            <td style='width: 200px'>
                                <img class='img-fluid' src=".$row["dish_photo"]." alt='' />
                            </td>
                            <td>".$row["dish_name"]."</td>
                            <td>".$row["price"]."</td>
                    ";
                }
                echo"
                            <input type='hidden' value='$key' name='dish_name'>
                            <input type='hidden' value='$reserv_id' name='rid'>
                            <td>".$val."</td>
                ";    
                echo"
                            </form>
                        </tr>
                    </tbody>
                ";
                }

                echo '</table>';
            }
    
        else {    
            echo "<h5 class='text-center'>Pre-Order Food list in this reservation (ID: $reserv_id) is empty!<h5><br><br><br>"; }
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

<script>
function something(id)
{

    window.location.href = "preorder_food.php?reserv_id="+id;
}

function edit_food(id)
{

    window.location.href = "edit_food_preorder.php?reserv_id="+id;
}
</script>
</script>