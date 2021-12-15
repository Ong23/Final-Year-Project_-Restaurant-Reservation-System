<?php


require 'dbh.inc.php';

    if(isset($_SESSION['user_id']))
    {
        $reserv_id = $_GET['reserv_id'];
        $user = $_SESSION['user_id'];

        //Retrieve All Dish
        $sql = "SELECT * FROM dish 
        INNER JOIN `preorder dish` AS po 
        ON dish.dish_id=po.dish_id AND reserv_id = $reserv_id
        ";
        $result = $conn->query($sql);

        //Remove 1 item
        if(isset($_GET['remove']))
        {
            $dish_id = $_GET['dish'];
            if($_GET['remove']=='success')
            {   
                echo '<br><h5 class="bg-success text-center">Dish ID: '.$dish_id.' (quantity: 1) is removed successfully!</h5>';
            }
            else if($_GET['remove']=='error')
            {
                echo '<br><h5 class="bg-danger text-center">Error! Failed to remove dish ID: '.$dish_id.' (quantity: 1). Please contact restaurant for assistance!</h5>';
            }
        }

        //Add 1 item
        if(isset($_GET['add']))
        {
            $dish_id = $_GET['dish'];
            if($_GET['add']=='success')
            {
                echo '<br><h5 class="bg-success text-center">Dish ID: '.$dish_id.' (quantity: 1) is added successfully!</h5>';
            }
            else if($_GET['add']=='error')
            {
                echo '<br><h5 class="bg-danger text-center">Error! Failed to add dish ID: '.$dish_id.' (quantity: 1). Please contact restaurant for assistance!</h5>';
            }
        }

        //Add New Dish
        if(isset($_GET['addItems']))
        {
            if($_GET['addItems']=='success')
            {
                echo '<br><h5 class="bg-success text-center">Dishes are added successfully!</h5>';
            }
            else if($_GET['addItems']=='error')
            {
                echo '<br><h5 class="bg-danger text-center">Error! Failed to add dishes. Please contact restaurant for assistance!</h5>';
            }
        }
        //Retrieve Photo
        /*
        $sql2 = "SELECT DISTINCT dish.dish_name, dish.dish_photo FROM dish 
        INNER JOIN `preorder dish` AS po 
        ON dish.dish_name=po.dish_name AND reserv_id = $reserv_id";

        $result2 = $conn->query($sql2);
        */

        $dish_name_arr = array();

        if ($result->num_rows > 0) {

            //Button "Add new dishes"
            echo'
                <br>
                <button class="fa fa-plus btn btn-lg btn-success" data-toggle="modal" data-target="#myModal_addDish">
                    &nbsp; Add New Dish
                </button>
                <br>
            ';

            while($row = $result->fetch_assoc()) //Retriee Data
            {
                /*
                $PO_dish = array();
                $PO_dish['id'] = $row['PODish_id'];
                $PO_dish['name'] = $row['dish_name'];
                $PO_dish['photo'] = $row['dish_photo'];
                $props[] = $PO_dish;
                */
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
                            <th scope="col">Dish ID</th>
                            <th scope="col">Photo</th>
                            <th scope="col">Name</th>
                            <th scope="col">Price(RM)</th>
                            <th scope="col">Quantity</th>
                            <th scope="col" colspan="2">Action</th>
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
                $sql2 = "SELECT * FROM dish WHERE dish_id=$key";
                $result = $conn->query($sql2);
                while($row = $result->fetch_assoc()) //Dish Photo
                {
                     echo"
                     
                            <td>".$key."</td>
                            <td style='width: 200px'>
                                <img class='img-fluid' src=".$row["dish_photo"]." alt='' />
                            </td>
                            <td>".$row["dish_name"]."</td>
                            <input type='hidden' value='$key' name='dish_id'>
                            <input type='hidden' value='$reserv_id' name='rid'>
                            <td>".$row["price"]."</td>
                            <td>".$val."</td>

                            <td><button type='submit' name='add-submit' class='fa fa-plus btn btn-success btn-sm'
                            onclick=\"javascript: return confirm('Add ONE dish ".$row["dish_name"]." (ID: $key) more?');\"> 
                            Add</button></td>      
                            <td><button type='submit' name='remove-submit' class='fa fa-trash btn btn-danger btn-sm'
                            onclick=\"javascript: return confirm('Delete ONE dish ".$row["dish_name"]." (ID: $key)?');\">   
                            Remove</button></td>
                    ";
                }
                echo"
                            
                ";    
                echo"
                            </form>
                        </tr>
                    </tbody>
                ";
                }

                echo '</table>';
            }
        else //Display Pre-Order Button, if no food in PO lIst
        {    
            echo "<h5 class='text-center'>Pre-Order Food list in this reservation (ID: $reserv_id) is empty!<h5><br><br><br>"; 
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
?>

<!--New Dish Modal-->
<div class="container">
    <div class="modal fade" id="myModal_addDish">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <!-- Modal Header -->
                <div class="modal-header">
                  <h4 class="modal-title">Add New Dishes</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>      
            <!-- Modal body -->
                <div class="modal-body">   

                    <?php
                    if(isset($_GET['insert'])) { 
                        if($_GET['insert'] == "error") {   
                            echo '<br><h5 class="bg-danger text-center">Error! not Inserted</h5>';
                        }
                        else if($_GET['insert'] == "success"){ 
                            echo '<br><h5 class="bg-success text-center">New Dish is added successfully</h5>';
                        }
                    }
                    ?>
    
                    <!---Add DIsh form -->
                    <?php
                        require 'dbh.inc.php';
                        $sql = "SELECT * FROM dish";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            echo'
                                <p>Click <strong>"Add New Dishes" </strong>button at the bottom of modal to add the dishes chosen into your food pre-order list.<br></p>
                                <div class="table-main table-responsive">
                                    <div hidden class="form-group">
                                        <select class  ="form-control" name="state" id="maxRows">
                                            <option value="5000">Show ALL Rows</option>
                                            <option value="5">5</option>
                                        </select>
                                        
                                    </div>
                                    <table class="table table-striped table-class" id= "table-id">
                                        <thead>
                                            <tr>
                                                <th>Images</th>
                                                <th>Dish Name</th>
                                                <th>Price(RM)</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                            ';
                            while($row = $result->fetch_assoc()) {
                                $id = "'".$row['dish_id']."'";
                                $name = "'".$row['dish_name']."'";
                                $price = "'".$row['price']."'";

                                if($row['active']==1){
                                    echo'
                                        <tr>
                                        <td class="thumbnail-img">
                                            <img class="img-fluid" src='.$row['dish_photo'].' alt="" />
                                        </td>
                                        <td class="name-pr">
                                            <strong>'.$row['dish_name'].' </strong>
                                        </td>
                                        <td class="price-pr">
                                            <p>'.$row['price'].'</p>
                                        </td>
                                        <td class="quantity-box">
                                            <button class="btn btn-success " type="button" class="btn btn-default btn-sm"
                                                onclick="addItem('.$id.','.$name.','.$price.')">
                                                +
                                            </button>
                                            <input type="text" id="'.$row["dish_id"].'" value="0" disabled/>
                                            <button class="btn btn-danger " type="button" class="btn btn-default btn-sm"
                                                onclick="removeItem('.$id.','.$name.','.$price.')">
                                                -
                                            </button>
                                        </td>
                                    </tr>
                                    ';
                                }
                            }
                            echo'
                                                </tbody>
                                            </table>
                                            <!--Start Pagination -->
                                            <div class="pagination-container">
                                                <nav>
                                                    <ul class="pagination">
                                                        <li class="page-item" data-page="prev" >
                                                            <span class="page-link"> < <span class="sr-only page-link">(current)</span></span>
                                                        </li>
                                                        <!--	Here the JS Function Will Add the Rows -->
                                                        <li class="page-item" data-page="next" id="prev">
                                                            <span class="page-link"> > <span class="sr-only page-link">(current)</span></span>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div>
                                <hr>
                                <div class="container-order">
                                    <h3 class="text-center">Order summary</h3>
                                                <div class="d-flex gr-total">
                                                    <ol class="itemList" id="item_list">
                                                    </ol>
                                                <div class="ml-auto font-weight">
                                                    <ul id="price_list" style="list-style-type:none;">
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="d-flex gr-total">
                                                <h5>Grand Total</h5>
                                                <div class="ml-auto h5" id="grand_total"> RM0</div>
                                            </div>
                                            <hr>
                                </div>
                                <div class="row">
                                    <div class="col text-center">
                                        <button class="text-center btn btn-lg btn-success" onclick="confirm_add('.$reserv_id.')">Add New Dishes</button>
                                    </div>
                                </div>
                            ';
                        }

                        
                    ?>       
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

<script>
    function something(id)
    {
        window.location.href = "preorder_food.php?reserv_id="+id;
    }
    let dishes = [];
    let dish_name = [];
    let gTotal = 0.00;
    let datas = []; //dish_id

    const iList = document.getElementById('item_list');
    const pList = document.getElementById('price_list');

    function addItem(data, name, price)
    {
        //Increase 1
        var value = parseFloat(document.getElementById(data).value);
        value = isNaN(value) ? 0 : value;
        value++;
        document.getElementById(data).value = value;

        var dish_price = parseFloat(price * 100.00);
        dishes.push([data,dish_price/100.00]);
        dish_name.push(name);

        //push dish ID
        datas.push(data);

        //calculate grand total
        gTotal = gTotal + (dish_price /100.00);
        document.getElementById("grand_total").innerHTML = "RM" + gTotal.toFixed(2);

        //display items in list
        let li = document.createElement('li');
        li.setAttribute('class', 'dish');
        li.innerHTML += name;
        iList.appendChild(li);

        //display price in list
        let li2 = document.createElement('li');
        li2.setAttribute('class', 'price');
        li2.innerHTML += price; //price
        pList.appendChild(li2);
    }

    function removeItem(data, name, price)
    {
        var value = parseFloat(document.getElementById(data).value);
        if(!(value<= 0 )){

            value = isNaN(value) ? 0 : value;
            value--;
            document.getElementById(data).value = value;

            var del_count = 0;

            for (var j = 0; j < dishes.length; j++) {
                if (dishes[j][0] == data && del_count == 0) {
                    dishes.splice(j, 1);
                    datas.splice(j, 1);

                    del_count++;
                }
            }
            //calculate grand total
            var dish_price = parseFloat(price * 100.00);
            gTotal = gTotal - (dish_price / 100.00);

            //remove item from list
            elements = document.getElementsByClassName('dish'); 
            var myList = document.getElementById("item_list"); 
            var length = (document.getElementsByClassName('dish').length);

            for(var counter = 0; counter < length; counter ++)
            {
                if (elements[counter].textContent == name )
                {
                    myList.removeChild(myList.children[ (counter) ]);
                    break;
                }
            }

            //remove price from list
            elements = document.getElementsByClassName('price'); 
            var myList = document.getElementById("price_list"); 
            var length = (document.getElementsByClassName('price').length);

            for(var counter = 0; counter < length; counter ++)
            {
                if (elements[counter].textContent == price )
                {
                    myList.removeChild(myList.children[ (counter) ]);
                    break;
                }
            }
        }
        //calculate grand total
        document.getElementById("grand_total").innerHTML = "RM" + gTotal.toFixed(2);
    }

    function confirm_add(id)
    {
        info = JSON.stringify(dish_name);
        info2 = JSON.stringify(datas); //dish ID

        window.location.href = "includes/confirmAdd.php?info2="+info2+"&id="+id;
    }

    //window.location.href='modifyPODish.php?id=".$reserv_id ."&dish=".$key .

    //Pagination
    getPagination('#table-id');
					//getPagination('.table-class');
					//getPagination('table');

		  /*					PAGINATION 
		  - on change max rows select options fade out all rows gt option value mx = 5
		  - append pagination list as per numbers of rows / max rows option (20row/5= 4pages )
		  - each pagination li on click -> fade out all tr gt max rows * li num and (5*pagenum 2 = 10 rows)
		  - fade out all tr lt max rows * li num - max rows ((5*pagenum 2 = 10) - 5)
		  - fade in all tr between (maxRows*PageNum) and (maxRows*pageNum)- MaxRows 
		  */
		 

    function getPagination(table) {
    var lastPage = 1;

    $('#maxRows')
        .on('change', function(evt) {
        //$('.paginationprev').html('');						// reset pagination

        lastPage = 1;
        $('.pagination')
            .find('li')
            .slice(1, -1)
            .remove();
        var trnum = 0; // reset tr counter
        var maxRows = parseInt($(this).val()); // get Max Rows from select option

        if (maxRows == 5000) {
            $('.pagination').hide();
        } else {
            $('.pagination').show();
        }

        var totalRows = $(table + ' tbody tr').length; // numbers of rows
        $(table + ' tr:gt(0)').each(function() {
            // each TR in  table and not the header
            trnum++; // Start Counter
            if (trnum > maxRows) {
            // if tr number gt maxRows

            $(this).hide(); // fade it out
            }
            if (trnum <= maxRows) {
            $(this).show();
            } // else fade in Important in case if it ..
        }); //  was fade out to fade it in
        if (totalRows > maxRows) {
            // if tr total rows gt max rows option
            var pagenum = Math.ceil(totalRows / maxRows); // ceil total(rows/maxrows) to get ..
            //	numbers of pages
            for (var i = 1; i <= pagenum; ) {
            // for each page append pagination li
            $('.pagination #prev')
                .before(
                '<li class="page-item" data-page="' +
                    i +
                    '">\
                                    <span class="page-link">' +
                    i++ +
                    '<span class="page-link sr-only">(current)</span></span>\
                                    </li>'
                )
                .show();
            } // end for i
        } // end if row count > max rows
        $('.pagination [data-page="1"]').addClass('active'); // add active class to the first li
        $('.pagination li').on('click', function(evt) {
            // on click each page
            evt.stopImmediatePropagation();
            evt.preventDefault();
            var pageNum = $(this).attr('data-page'); // get it's number

            var maxRows = parseInt($('#maxRows').val()); // get Max Rows from select option

            if (pageNum == 'prev') {
            if (lastPage == 1) {
                return;
            }
            pageNum = --lastPage;
            }
            if (pageNum == 'next') {
            if (lastPage == $('.pagination li').length - 2) {
                return;
            }
            pageNum = ++lastPage;
            }

            lastPage = pageNum;
            var trIndex = 0; // reset tr counter
            $('.pagination li').removeClass('active'); // remove active class from all li
            $('.pagination [data-page="' + lastPage + '"]').addClass('active'); // add active class to the clicked
            // $(this).addClass('active');					// add active class to the clicked
            limitPagging();
            $(table + ' tr:gt(0)').each(function() {
            // each tr in table not the header
            trIndex++; // tr index counter
            // if tr index gt maxRows*pageNum or lt maxRows*pageNum-maxRows fade if out
            if (
                trIndex > maxRows * pageNum ||
                trIndex <= maxRows * pageNum - maxRows
            ) {
                $(this).hide();
            } else {
                $(this).show();
            } //else fade in
            }); // end of for each tr in table
        }); // end of on click pagination list
        limitPagging();
        })
        .val(5)
        .change();

    // end of on select change

    // END OF PAGINATION
    }

    function limitPagging(){
        // alert($('.pagination li').length)

        if($('.pagination li').length > 7 ){
                if( $('.pagination li.active').attr('data-page') <= 3 ){
                $('.pagination li:gt(5)').hide();
                $('.pagination li:lt(5)').show();
                $('.pagination [data-page="next"]').show();
            }if ($('.pagination li.active').attr('data-page') > 3){
                $('.pagination li:gt(0)').hide();
                $('.pagination [data-page="next"]').show();
                for( let i = ( parseInt($('.pagination li.active').attr('data-page'))  -2 )  ; i <= ( parseInt($('.pagination li.active').attr('data-page'))  + 2 ) ; i++ ){
                    $('.pagination [data-page="'+i+'"]').show();

                }

            }
        }
    }
    /*
    $(function() {
    // Just to append id number for each row
    $('table tr:eq(0)').prepend('<th> ID </th>');

    var id = 0;

    $('table tr:gt(0)').each(function() {
        id++;
        $(this).prepend('<td>' + id + '</td>');
    });
    });
    */
    //  Developed By Yasser Mas
    // yasser.mas2@gmail.com

</script>