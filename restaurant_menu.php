<?php
require "header.php";
?>
<br><br>
<div class="container">
<h2 class="text-center"><br>Restaurant Menu<br></h2> 
<h6 class="text-center"><br>Lorem Ipsum is simply dummy text of the printing and typesetting
<br></h6> 


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
    require 'includes/dbh.inc.php';

    $sql = "SELECT * FROM dish";

    
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
    
?>
</div>