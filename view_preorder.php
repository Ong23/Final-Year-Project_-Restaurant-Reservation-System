<?php
require "header.php";
?>
    
<br><br>
<div class="container">
<h3 class="text-center"><br>Food Pre-order<br></h3>
<h5 class="text-center">Select a reservation to view/pre-order food<br></h5>     

<?php
    if(isset($_SESSION['user_id'])){
        if(isset($_GET['preorder'])){
            if($_GET['preorder']=="success"){
               echo '<h5 class="bg-success text-center">Food pre-order successfull!</h5>';
            }
        }
        if(isset($_GET['error3'])){
            if($_GET['error3']=="preorderError"){
               echo '<h5 class="bg-danger text-center">Error! Fail to pre-order food. Please contact admin for assistance</h5>';
            }
        }
        
        require 'includes/view.preorder.inc.php';
        
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

