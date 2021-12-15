<?php
require "header.php";
?>
    
<br><br>
<div class="container">
<h3 class="text-center"><br>Manage Restaurant Menu<br></h3>     

<?php
if(isset($_SESSION['user_id']))
{
    $user = $_SESSION['user_id'];
    $role = $_SESSION['role'];

    if($role==2)
    {
        echo '
        <br>
        <button class="fa fa-plus btn btn-success" data-toggle="modal" data-target="#myModal_newDish">
            &nbsp; Add New Dish
        </button>

        <button class="fa fa-plus btn btn-outline-success" data-toggle="modal" data-target="#myModal_newCategory">
            &nbsp; Add Dish Category
        </button>

        <button class="fa fa-plus btn btn-outline-danger" data-toggle="modal" data-target="#myModal_removeCategory">
            &nbsp; Remove Dish Category
        </button>
        ';
    }
}
?>

<?php
if(isset($_SESSION['user_id']))
{

    require 'includes/dbh.inc.php';

    $user = $_SESSION['user_id'];
    $role = $_SESSION['role'];

    if(isset($_GET['activate'])) { 
        $dish_id = $_GET['id'];

        if($_GET['activate'] == "error") {   
            echo '<br><br><h5 class="bg-danger text-center">Error! Fail to activate dish (ID: '.$dish_id.')</h5>';
        }
        else if($_GET['activate'] == "success"){ 
            echo '<br><br><h5 class="bg-success text-center">Dish (ID: '.$dish_id.') is activated</h5>';
        }
    }

    if(isset($_GET['deactivate'])) { 
        $dish_id = $_GET['id'];

        if($_GET['deactivate'] == "error") {   
            echo '<br><br><h5 class="bg-danger text-center">Error! Fail to deactivate dish (ID: '.$dish_id.')</h5>';
        }
        else if($_GET['deactivate'] == "success"){ 
            echo '<br><br><h5 class="bg-success text-center">Dish (ID: '.$dish_id.') is deactivated</h5>';
        }
    }

    if(isset($_GET['update'])) { 
        $dish_id = $_GET['id'];

        if($_GET['update'] == "error") {   
            echo '<br><br><h5 class="bg-danger text-center">Error! Fail to update dish (ID: '.$dish_id.')</h5>';
        }
        else if($_GET['update'] == "success"){ 
            echo '<br><br><h5 class="bg-success text-center">Dish (ID: '.$dish_id.') is updated successfully!</h5>';
        }
    }

    if(isset($_GET['changePhoto'])) { 
        $dish_id = $_GET['id'];

        if($_GET['changePhoto'] == "error") {   
            echo '<br><br><h5 class="bg-danger text-center">Error! Cannot change photo! (ID: '.$dish_id.')</h5>';
        }
        if($_GET['changePhoto'] == "errorImageSize") {   
            echo '<br><br><h5 class="bg-danger text-center">Error! Image file cannot be larger than 2MB! (ID: '.$dish_id.')</h5>';
        }
        if($_GET['changePhoto'] == "errorFileType") {   
            echo '<br><br><h5 class="bg-danger text-center">Error! File type must be .jpg, .png, .jpeg or .gif (ID: '.$dish_id.')</h5>';
        }
        if($_GET['changePhoto'] == "errorNoFile") {   
            echo '<br><br><h5 class="bg-danger text-center">Error! Please choose an image file to upload for dish ID: '.$dish_id.'!</h5>';
        }
        else if($_GET['changePhoto'] == "success"){ 
            echo '<br><br><h5 class="bg-success text-center">Dish photo (ID: '.$dish_id.') is changed successfully</h5>';
        }
    }

    if($role==2){
        $sql = "SELECT d.dish_id, d.dish_name, d.dish_desc, d.price, d.dish_photo, d.active, d.dish_cat_id, dc.category_desc
        FROM `dish` AS d JOIN `dish category` AS dc ON d.dish_cat_id = dc.dish_cat_id ORDER BY d.dish_id ASC";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
                    echo'
                        <div class="cart-box-main">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-main table-responsive">
                                            <table class="table text-center" >
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Images</th>
                                                        <th>Name</th>
                                                        <th>Description</th>
                                                        <th>Price(RM)</th>
                                                        <th>Category</th>
                                                        <th>Active</th>
                                                        <th colspan="3">Action</th>
                                                    </tr>
                                                </thead>
                                                
                    ';
                    while($row = $result->fetch_assoc()) {
                        $id = "'".$row['dish_id']."'";
                        $name = "'".$row['dish_name']."'";
                        $price = "'".$row['price']."'";

                        echo'
                                                <tbody>
                                                    <tr>
                                                        <form action="includes/modifyDish.php" method="post">
                                                        <td>
                                                            <p>'.$row['dish_id'].'</p>
                                                        </td>
                                                        <input name="dish_id" type="hidden" value='.$row['dish_id'].'>
                                                        <td class="thumbnail-img">
                                                            <img id="photo'.$row['dish_id'].'" class="img-fluid" src='.$row['dish_photo'].' alt="" />
                                                        </td>
                                                        <td class="name-pr">
                                                            <span id="name'.$row['dish_id'].'"><strong>'.$row['dish_name'].'  </strong></span>
                                                        </td>
                                                        <td class="name-pr">
                                                            <span id="desc'.$row['dish_id'].'"><strong>'.$row['dish_desc'].'  </strong></span>
                                                        </td>
                                                        <td class="price-pr">
                                                            <span id="price'.$row['dish_id'].'"><p>'.$row['price'].'</p></span>
                                                        </td>
                                                        <td>
                                                            <span id="cat'.$row['dish_id'].'"><p hidden>'.$row['dish_cat_id'].'</p></span>
                                                            <p>'.$row['category_desc'].'</p>
                                                        </td>
                        ';
                        //Active
                        if($row['active']==1)
                        {
                            echo'
                                <td>
                                    <p> Active </p>
                                </td>
                            ';
                        }
                        
                        else
                        {
                            echo'
                                <td>
                                    <p> Inactive </p>
                                </td>
                            ';
                        }
                                                        

                            if($row['active']==1)
                            {
                                echo'
                                
                                        <td>
                                            <input class="fa fa-ban btn btn-danger " type="submit" class="btn btn-default btn-sm"
                                                name="deactivate" value="Deactivate">
                                            </input>
                                        </td></form>
                                ';
                            }
                            else
                            {
                                echo'
                                
                                <td>
                                    <input class="fa fa-check btn btn-success " type="submit" class="btn btn-default btn-sm"
                                        name="activate" value="Activate">
                                    </input>
                                </td></form>
                                ';
                            }
                            echo'
                            <td>
                                <button class="fa fa-edit btn btn-primary edit" value='.$row['dish_id'].'>
                                    &nbsp; Edit
                                </button>
                            </td>

                            <td>
                                <button class="fa fa-photo btn btn-info changePhoto" value='.$row['dish_id'].'>
                                    &nbsp; Change Photo
                                </button>
                            </td>
                            </tr>
                                            
            ';
                            echo'
                                        
                                    </tr>
                                </tbody>
                            ';
                        }
                        echo'
                                            
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br><br>                    
                        ';
                }
        }
    else
    {
        echo "<p class='text-white text-center bg-danger'>You have no authorization to access menu management!<p>";
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

<!--New Dish Modal-->
<div class="container">
    <div class="modal fade" id="myModal_newDish">
        <div class="modal-dialog">
            <div class="modal-content">
            <!-- Modal Header -->
                <div class="modal-header">
                  <h4 class="modal-title">New Dish</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>      
            <!-- Modal body -->
                <div class="modal-body">   

                <?php
                if(isset($_GET['errorAddDish'])){
                    //script for modal to appear when error 
                    echo '  <script>
                                $(document).ready(function(){
                                $("#myModal_newDish").modal("show");
                                });
                            </script> ';
                            
                    //error handling for errors and success --sign up form

                    if($_GET['errorAddDish'] == "emptyfields") {   
                        echo '<h5 class="bg-danger text-center">Fill all fields (exclude dish description), Please try again!</h5>';
                    }
                    else if($_GET['errorAddDish'] == "invalidprice") {   
                        echo '<h5 class="bg-danger text-center">Price must be numeric!</h5>';
                    }
                    else if($_GET['errorAddDish'] == "error1") {   
                        echo '<h5 class="bg-danger text-center">Error Occured, Try again!</h5>';
                    }
                    else if($_GET['errorAddDish'] == "error2") {   
                        echo '<h5 class="bg-danger text-center">Error Occured, Try again!</h5>';
                    }
                }
                if(isset($_GET['insert'])) { 
                        //script for modal to appear when success
                    echo '  <script>
                                $(document).ready(function(){
                                $("#myModal_newDish").modal("show");
                                });
                            </script> ';

                    if($_GET['insert'] == "error") {   
                        echo '<br><h5 class="bg-danger text-center">Error! not Inserted</h5>';
                    }
                    else if($_GET['insert'] == "success"){ 
                        echo '<br><h5 class="bg-success text-center">New Dish is added successfully</h5>';
                    }
                }



            if(isset($_GET['image_insert'])) { 
                    //script for modal to appear when success
                echo '  <script>
                            $(document).ready(function(){
                            $("#myModal_newDish").modal("show");
                            });
                        </script> ';

                if($_GET['image_insert'] == "error1") {   
                    echo '<br><h5 class="bg-danger text-center">Error! File is not an image!</h5>';
                }

                else if($_GET['image_insert'] == "error2") {   
                    echo '<br><h5 class="bg-danger text-center">Error! File already existed!</h5>';
                }

                else if($_GET['image_insert'] == "error3") {   
                    echo '<br><h5 class="bg-danger text-center">Error! File size cannot larger than 2MB!</h5>';
                }

                else if($_GET['image_insert'] == "error4") {   
                    echo '<br><h5 class="bg-danger text-center">Error! Only JPG, JPEG, PNG & GIF files are allowed.</h5>';
                }

                else if($_GET['image_insert'] == "error5") {   
                    echo '<br><h5 class="bg-danger text-center">Error in uploading image! Make sure the image meets the requirement mentioned in Note below.</h5>';
                }

                else if($_GET['image_insert'] == "success"){ 
                    echo '<br><h5 class="bg-success text-center">New Dish is added successfully</h5>';
                }

                else if($_GET['image_insert'] == "upload_success"){ 
                    echo '<br><h5 class="bg-success text-center">New Dish added and image uploaded successfully</h5>';
                }
            }
                echo'<br>';
                ?>
    
    <!---Add DIsh form -->
                    <div class="signup-form">
                        <form action="createDish.inc.php" method="post" enctype="multipart/form-data">
                            <p class="hint-text">Fill in the fields and click "Create New Dish" button</p>
                            <div class="form-group">
                                    <input type="text" class="form-control" name="dish_name" placeholder="Dish Name" required="required">
                                    <small class="form-text text-muted"></small>
                            </div>
                            <div class="form-group">
                                    <input type="text" class="form-control" name="dish_desc" placeholder="Dish Description">
                            </div>
                            <div class="form-group">
                                    <input type="text" class="form-control" name="price" placeholder="Price" required="required">
                            </div>
                            <div class="form-group">
                                <label>Dish Category: </label>
                                <?php 
                                    $sql = "SELECT * FROM `dish category`";
                                    $result = $conn->query($sql);

                                    echo '<select name="category" class="form-select"> ';

                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="'.$row['dish_cat_id'].'">'.$row['category_desc'].'</option>';
                                     }
                                    echo '</select>';
                                ?>
                                <button class="fa fa-plus btn btn-outline-success" data-toggle="modal" data-target="#myModal_newCategory" data-dismiss="modal">
                                    &nbsp; Add Dish Category
                                </button>
                            </div>
                            <!--Image Upload-->
                            <div class="form-group">
                                <label>Select image to upload:</label>
                                <input type="file" onchange="readURL(this);" name="fileToUpload" id="fileToUpload" accept="image/*">
                                <img id="blah" src="" style="width: 168px"/>
                            </div>
                            <div class="form-group">
                                <label><strong>Note: Image Size < 2MB, File Type: .jpg, .png, .jpeg, .gif </strong></label>
                            </div>
                            <div class="form-group">
                                <button type="submit" name="add-submit" class="btn btn-success btn-lg btn-block">Create New Dish</button>
                            </div>
                        </form>
                    </div> 	
                </div>        
                <!-- Modal footer -->
                <div class="modal-footer">

                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div> 
            </div>
        </div>
    </div>
</div>

</div>
<br><br>

<!--New Category Modal-->
<div class="container">
  <!-- The Modal -->
    <div class="modal fade" id="myModal_newCategory">
        <div class="modal-dialog">
            <div class="modal-content">
            <!-- Modal Header -->
                <div class="modal-header">
                  <h4 class="modal-title">New Dish Category</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>      
            <!-- Modal body -->
                <div class="modal-body">   

                <?php
                if(isset($_GET['error'])){
                    //script for modal to appear when error 
                    echo '  <script>
                                $(document).ready(function(){
                                $("#myModal_newCategory").modal("show");
                                });
                            </script> ';
                            
                    //error handling for errors and success --sign up form

                    if($_GET['error'] == "emptyfields") {   
                        echo '<h5 class="bg-danger text-center">Fill all fields (exclude dish description), Please try again!</h5>';
                    }
                    else if($_GET['error'] == "error1") {   
                        echo '<h5 class="bg-danger text-center">Error Occured, Try again!</h5>';
                    }
                    else if($_GET['error'] == "error2") {   
                        echo '<h5 class="bg-danger text-center">Error Occured, Try again!</h5>';
                    }
                }
                if(isset($_GET['insertCat'])) { 
                        //script for modal to appear when success
                    echo '  <script>
                                $(document).ready(function(){
                                $("#myModal_newCategory").modal("show");
                                });
                            </script> ';

                    if($_GET['insertCat'] == "error") {   
                        echo '<br><h5 class="bg-danger text-center">Error! not Inserted</h5>';
                    }
                    else if($_GET['insertCat'] == "success"){ 
                        echo '<br><h5 class="bg-success text-center">New Dish Category is added successfully</h5>';
                    }
                }
                echo'<br>';
                ?>
    
    <!---Add Category form -->
                    <div class="signup-form">
                        <form action="createDish.inc.php" method="post">
                            <p class="hint-text">Fill in the field and click "Create New Category" button</p>
                            <div class="form-group">
                                    <input type="text" class="form-control" name="dish_cat" placeholder="Dish Category" required="required">
                            </div>
                            <div>
                                <button type="submit" name="add-cat-submit" class="btn btn-success btn-lg btn-block">Create New Category</button>
                            </div>
                        </form>
                    </div> 	
                </div> 
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>      
            </div>
        </div>
    </div>
</div>




<!--Remove Category Modal-->
<div class="container">
  <!-- The Modal -->
    <div class="modal fade" id="myModal_removeCategory">
        <div class="modal-dialog">
            <div class="modal-content">
            <!-- Modal Header -->
                <div class="modal-header">
                  <h4 class="modal-title">Remove Dish Category</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>      
            <!-- Modal body -->
                <div class="modal-body">   

                <?php
                if(isset($_GET['error'])){
                    //script for modal to appear when error 
                    echo '  <script>
                                $(document).ready(function(){
                                $("#myModal_removeCategory").modal("show");
                                });
                            </script> ';
                            
                    //error handling for errors and success --sign up form

                    if($_GET['error'] == "emptyfields") {   
                        echo '<h5 class="bg-danger text-center">Fill all fields (exclude dish description), Please try again!</h5>';
                    }
                    else if($_GET['error'] == "error1") {   
                        echo '<h5 class="bg-danger text-center">Error Occured, Try again!</h5>';
                    }
                    else if($_GET['error'] == "error2") {   
                        echo '<h5 class="bg-danger text-center">Error Occured, Try again!</h5>';
                    }
                }
                if(isset($_GET['remove'])) { 
                    //script for modal to appear when success
                echo '  <script>
                            $(document).ready(function(){
                            $("#myModal_removeCategory").modal("show");
                            });
                        </script> ';

                if($_GET['remove'] == "error") {   
                    echo '<br><h5 class="bg-danger text-center">Error! not Removed</h5>';
                }
                else if($_GET['remove'] == "success"){ 
                    echo '<br><h5 class="bg-success text-center">Dish Category is removed successfully</h5>';
                }
            }
            echo'<br>';
                echo'<br>';
                ?>
    
    <!---Remove Category form -->
                    <div class="signup-form">
                        <form action="createDish.inc.php" method="post">
                            <p class="hint-text">Select a category and click "Remove Category" button to remove category</p>
                            <div class="form-group">
                                <label>Dish Category: </label>
                                <?php 
                                    $sql = "SELECT * FROM `dish category`";
                                    $result = $conn->query($sql);

                                    echo '<select name="dish_cat" class="form-select"> ';
                                    
                                    while ($row = $result->fetch_assoc()) {
                                        if($row['dish_cat_id']!=1)
                                        {
                                            echo '<option value="'.$row['dish_cat_id'].'">'.$row['category_desc'].'</option>';
                                        }
                                    }
                                    echo '</select>';
                                ?>
                            </div>
                            <div>
                                <button type="submit" name="remove-cat-submit" class="btn btn-danger btn-lg btn-block">Remove Category</button>
                            </div>
                        </form>
                    </div> 	
                </div>  
                <!-- Modal footer -->
                <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>   
            </div>
        </div>
    </div>
</div>




<!--Edit Modal-->
<div class="container">
  <!-- The Modal -->
    <div class="modal fade" id="myModal_edit">
        <div class="modal-dialog">
            <div class="modal-content">
            <!-- Modal Header -->
                <div class="modal-header">
                  <h4 class="modal-title">Edit Dish</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>      
            <!-- Modal body -->
                <div class="modal-body">   
    <!---Edit form -->
                    <div class="signup-form">
                        <form action="includes/modifyDish.php" method="post">
                            <p class="hint-text">Fill in the field and click "Update Dish" button</p>
                            <input type="hidden" class="form-control" id= "dish_id" name="dish_id"  required="required">
                            <div class="form-group">
                                    <label>Dish Name:</label>
                                    <input type="text" class="form-control" id= "name" name="name" placeholder="Dish Name" required="required">
                            </div>
                            <div class="form-group">
                                    <label>Dish Description:</label>
                                    <input type="text" class="form-control" id= "dish_desc" name="dish_desc" placeholder="Dish Description">
                            </div>
                            <div class="form-group">
                                    <label>Price (RM):</label>
                                    <input type="text" class="form-control" id= "price" name="price" placeholder="Price" required="required">
                            </div>
                            <div class="form-group">
                                <label>Dish Category: </label>
                                <?php 
                                    $sql = "SELECT * FROM `dish category`";
                                    $result = $conn->query($sql);

                                    echo '<select id="category" name="category" class="form-select"> ';
                                    
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<option value="'.$row['dish_cat_id'].'">'.$row['category_desc'].'</option>';
                                    }
                                    echo '</select>';
                                ?>
                                <button class="fa fa-plus btn btn-outline-success" data-toggle="modal" data-target="#myModal_newCategory" data-dismiss="modal">
                                    &nbsp; Add Dish Category
                                </button>
                            </div>
                            <br>
                            <div>
                                <button type="submit" name="update-dish-submit" class="btn btn-primary btn-lg btn-block">Update Dish</button>
                            </div>
                        </form>
                    </div> 	
                </div>        
                <!-- Modal footer -->
                <div class="modal-footer">

                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div> 
            </div>
        </div>
    </div>
</div>



<!--Change Photo Modal-->
<div class="container">
  <!-- The Modal -->
    <div class="modal fade" id="myModal_changePhoto">
        <div class="modal-dialog">
            <div class="modal-content">
            <!-- Modal Header -->
                <div class="modal-header">
                  <h4 class="modal-title">Change Dish Photo</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>      
            <!-- Modal body -->
                <div class="modal-body">   

    
    <!---Change Photo form -->
                    <div class="signup-form">
                        <form action="createDish.inc.php" method="post" enctype="multipart/form-data">
                            <p class="hint-text">Choose image file and click "Change Photo" button</p>
                            <input type="hidden" class="form-control" id= "dish_id1" name="dish_id1"  required="required">
                            <!--Image Upload-->
                            <div class="form-group">
                                <label>Select image to upload:</label>
                                <input type="file" onchange="readURL1(this);" name="fileToUpload1" id="fileToUpload1" accept="image/*">
                                <img id="blah1" src="" style="width: 168px"/>
                            </div>
                            <div class="form-group">
                                <label><strong>Note: Image Size < 2MB, File Type: .jpg, .png, .jpeg, .gif </strong></label>
                            </div>
                            <br>
                            <div>
                                <button type="submit" name="change-photo-submit" class="btn btn-info btn-lg btn-block">Change Photo</button>
                            </div>
                        </form>
                    </div> 	
                </div>        
                <!-- Modal footer -->
                <div class="modal-footer">

                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div> 
            </div>
        </div>
    </div>
</div>

<script>

    //New Dish Modal
    function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
    }

    //Change Photo Modal
    function readURL1(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah1')
                        .attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
    }
        //Pass data to Edit Modal
        $(document).ready(function(){
                $(document).on('click', '.edit', function(){
                    var id=$(this).val();
                    var name=$('#name'+id).text();
                    var desc=$('#desc'+id).text();
		            var price=$('#price'+id).text();
                    var cat=$('#cat'+id).text();
            
                    $('#myModal_edit').modal('show');
                    $('#dish_id').val(id);
                    $('#name').val(name);
                    $('#dish_desc').val(desc);
                    $('#price').val(price);
                    $('#category').val(cat);
            });
        });

        //Pass data to Change Photo Modal
        $(document).ready(function(){
                $(document).on('click', '.changePhoto', function(){
                    var id=$(this).val();

                    $('#myModal_changePhoto').modal('show');
                    $('#dish_id1').val(id);

                    var photo=$('#photo'+id).attr('src');
                    $('#blah1').attr('src', photo);

            });
        });
        
</script>

