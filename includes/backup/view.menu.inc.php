<br>
<div class="text-center">
    <form method="POST">
        <input type="submit" name="all" value="All" class="btn btn-secondary">
        <?php
            require 'includes/dbh.inc.php';

            $records = mysqli_query($conn,"SELECT * FROM `dish category` ");
            while($data = mysqli_fetch_array($records))
            {
                //Name attribute does not allow white space in HTML 4
                $str = str_replace(' ', '_', $data["category_desc"]);
                echo '<input type="submit" name="'.$str.'" value="'.$data["category_desc"].'" class="btn btn-secondary">
    
                ';
            }  
        ?>
    </form>
</div>
<br>

<?php


if(isset($_SESSION['user_id'])){
    
    require 'includes/dbh.inc.php';

    $sql = "SELECT * FROM dish";

    $user = $_SESSION['user_id'];
    $role = $_SESSION['role'];
    
    //Filter SQL Query
    $records = mysqli_query($conn,"SELECT * FROM `dish category` ");
    while($data = mysqli_fetch_array($records))
    {
        $id = $data['dish_cat_id'];

        $str = str_replace(' ', '_', $data["category_desc"]);
        if(isset($_POST[$str])){
            $sql = "SELECT * FROM dish WHERE dish_cat_id = $id";

            //echo "SELECT * FROM dish WHERE dish_cat_id = $id";
            //echo $data["category_desc"];
        }
    }  


    //USER
    if($role==1){
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo
            '
                <div class="menu-box">
                    <div class="container">
                        <div class="row special-list">
            ';
            while($row = $result->fetch_assoc()) {
                if($row['active']==1)
                {
                    echo'
                        <div class="col-lg-4 col-md-6 special-grid drinks">
                            <div class="gallery-single fix">
                                <img src='.$row['dish_photo'].' class="img-fluid" alt="Image">
                                <div class="why-text">
                                    <h4>'.$row['dish_name'].'</h4>
                                    <p>'.$row['dish_desc'].'</p>
                                    <h5>RM'.$row['price'].'</h5>
                                </div>
                            </div>
                        </div>
                    ';  
                } 
                else
                {
                    //Display not available
                    //inactive dish
                    echo'
                        <div class="col-lg-4 col-md-6 special-grid drinks">
                            <div class="gallery-single fix">
                                <div class="container_inactive">
                                    <img src='.$row['dish_photo'].' class="img-fluid" style="opacity: 0.3" alt="Image">
                                    <div class="centered_inactive">Not Available</div>
                                </div>
                                <div class="why-text">
                                    <h4>'.$row['dish_name'].'</h4>
                                    <p>'.$row['dish_desc'].'</p>
                                    <h5>RM'.$row['price'].'</h5>
                                </div>
                            </div>
                        </div>
                    ';  
                }
            }   
            echo "
                        </div>
                    </div>
                </div>
            ";
        }

    else {    
        echo "<p class='text-white text-center bg-danger'>No Dishes Available!<p>"; }
    }
    
    
    //Admin
    
    else if($role==2){
        $sql = "SELECT * FROM dish";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo
            '
                <div class="menu-box">
                    <div class="container">
                        <div class="row special-list">
            ';
            while($row = $result->fetch_assoc()) {
            echo'
                <div class="col-lg-4 col-md-6 special-grid drinks">
                    <div class="gallery-single fix">
                        <img src='.$row['dish_photo'].' class="img-fluid" alt="Image">
                        <div class="why-text">
                            <h4>'.$row['dish_name'].'</h4>
                            <p>'.$row['dish_desc'].'</p>
                            <h5>RM'.$row['price'].'</h5>
                        </div>
                    </div>
                </div>
            ';   
            }   
            echo "
                        </div>
                    </div>
                </div>
            ";
        }

    else {    
        echo "<p class='text-white text-center bg-danger'>No Dishes Available!<p>"; }
    }
    
mysqli_close($conn);
}

?>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
      </div>
    </div>
  </div>
</div>

<script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js'></script>
<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js'></script>
<script src="js/function.js"></script>