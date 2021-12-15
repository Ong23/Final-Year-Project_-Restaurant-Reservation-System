<?php
require "header.php";
?>
    
<br><br>
<div class="container">
<h3 class="text-center"><br>View Restaurant Menu<br></h3>     

<?php
    
    if(isset($_SESSION['user_id'])){
        if(isset($_GET['delete'])){
            if($_GET['delete'] == "error") {   
                echo '<h5 class="bg-danger text-center">Error!</h5>';
            }
            if($_GET['delete'] == "success"){ 
                echo '<h5 class="bg-success text-center">Delete successful</h5>';
            }
        }  

        require 'includes/view.menu.inc.php';
        
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

