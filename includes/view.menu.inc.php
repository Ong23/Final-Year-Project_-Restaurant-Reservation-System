
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
                    <div class="container">
                        <div class="gallery">
                ';
                while($row = $result->fetch_assoc()) {
                    if($row['active']==1)
                    {
                        echo'
                        <div class="container_gallery">
                            <img src='.$row['dish_photo'].' class="single-image">
                            <div class="why-text">
                                <h4>'.$row['dish_name'].'</h4> <br>
                                <p>'.$row['dish_desc'].'</p>
                                <h5>RM'.$row['price'].'</h5>    
                            </div>
                        </div>
                        ';  
                    } 
                    else
                    {
                        //Display not available
                        //inactive dish 
                    }
                }   
                echo "
                            
                        </div>
                    </div>
                ";
            }
        
        else {    
        echo "<p class='text-white text-center bg-danger'>No Dishes Available!<p>"; }
    }
    
    
    //Admin
    
    else if($role==2){
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo
                '
                    <div class="container">
                        <div class="gallery">
                ';
                while($row = $result->fetch_assoc()) {
                    if($row['active']==1)
                    {
                        echo'
                        <div class="container_gallery">
                            <img src='.$row['dish_photo'].' class="single-image">
                            <div class="why-text">
                                <h4>'.$row['dish_name'].'</h4> <br>
                                <p>'.$row['dish_desc'].'</p>
                                <h5>RM'.$row['price'].'</h5>    
                            </div>
                        </div>
                        ';  
                    } 
                    else
                    {
                        //Display not available
                        //inactive dish 
                    }
                }   
        }

    else {    
        echo "<p class='text-white text-center bg-danger'>No Dishes Available!<p>"; }
    }
    
mysqli_close($conn);
}

?>
