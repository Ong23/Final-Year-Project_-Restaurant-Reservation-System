<!--Back up for view.menu.inc.php-->
<?php


if(isset($_SESSION['user_id'])){
    
    require 'includes/dbh.inc.php';

    
    $user = $_SESSION['user_id'];
    $role = $_SESSION['role'];
    
    //USER
    if($role==1){
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
