<?php
require "header.php";
?>
    
<br><br>
<div class="container">
<h3 class="text-center"><br>Edit Pre-ordered Food List<br></h3>


<?php
    require 'includes/dbh.inc.php';

    if(isset($_SESSION['user_id'])){
        if(isset($_GET['reserv_id']))
        {
            $reserv_id = $_GET['reserv_id'];
        }

    
        //Get User Id for authorization
        $records = mysqli_query($conn,"SELECT * FROM reservation WHERE reserv_id =$reserv_id LIMIT 1");
        while($data = mysqli_fetch_array($records))
        {
            $uid = $data['user_fk'];
        }
        if($uid == $_SESSION['user_id'] || $_SESSION['role']== 2)
        {
            require 'includes/editPOFood.inc.php';
        }
        else
        {
            echo "<br><br><p class='text-center text-danger'>You have no authorization to perform action for other user's pre-ordered food.</p>";
        }
        
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
<br><br>

