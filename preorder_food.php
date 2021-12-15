<?php
require "header.php";
?>
    
<br><br>
<div class="container">
<h3 class="text-center"><br>Food Pre-Order<br></h3> 

<?php
if(isset($_SESSION['user_id'])){
    require 'includes/dbh.inc.php';

    if(isset($_GET['preorder']))
    {
        if($_GET['preorder']=='empty')
        {
            echo '<h5 class="bg-danger text-center">No dishes is selected. Please select at least one dish to complete pre-order!</h5>';
        }
    }

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

    if($uid == $_SESSION['user_id'] || $_SESSION['role']== 2){
        if(isset($_GET['status'])){
            if($_GET['status'] == "ordered") { 
                echo "<h4 class='text-center'><br> This reservation already has order. </h4>";
                echo "
                <div class='container'>
                    <div class='row'>
                        <div class='col text-center'>
                            <button class='fa fa-eye btn btn-success btn-default' onclick='redirect_to_view(".$reserv_id.")'>  View Order</button>
                        </div>
                    </div>
                </div>
                <div class='container'>
                    <div class='row'>
                        <div class='col text-center'>
                            <button class='fa fa-edit btn btn-info btn-default' onclick='redirect_to_edit(".$reserv_id.")'>  Edit Order</button>
                        </div>
                    </div>
                </div>
                ";

            }
        }
        else {  
            $sql = "SELECT * FROM dish";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                    echo'
                        <div class="cart-box-main">
                            <div class="container">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-main table-responsive">
                                            <table class="table">
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
                                        </div>
                                    </div>
                                </div>

                                <div class="row my-5">
                                    <div class="col-lg-8 col-sm-12"></div>
                                    <div class="col-lg-4 col-sm-12">
                                        <div class="order-box">
                                            <h3>Order summary</h3>
                                            <div class="d-flex">
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
                                            <hr> </div>
                                    </div>
                                    <div class="col-12 d-flex shopping-box">
                                        <button class="ml-auto btn btn-success" onclick="something('.$reserv_id.')">Checkout</button>
                                    </div>
                                </div>

                                </div>
                                </div>
                                </div>
                                <br><br>                    
                            ';
                    }
            
        }
    }
    else
    {
        echo "<br><br><p class='text-center text-danger'>You have no authorization to perform action for other user's pre-ordered food.</p>";
    }
}
else
{
    echo '	
    <br><br>
    <p class="text-center text-danger"><br>You are currently not logged in!<br></p>
    <p class="text-center">You need to 
    <a class="text-primary" data-toggle="modal" data-target="#myModal_reg">create account</a> or 
        <a class="text-primary" data-toggle="modal" data-target="#myModal_login">login</a>
    to pre-order food!<br><br><p>'; 
}
?>
 
<script>

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

        /*
        for (var i = 0; i < dishes.length; i++) {
            if (dishes[i][0] == name) {
                dishes.splice(i, 1);
                dish_name.splice(i, 1);
            }
        }
        */

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

function something(id)
{
    info = JSON.stringify(dish_name);
    info2 = JSON.stringify(datas); //dish ID

    window.location.href = "checkout.php?info2="+info2+"&id="+id;
}

function redirect_to_view(rid)
{
    window.location.href = "preorder_list.php?reserv_id="+rid;
}

function redirect_to_edit(rid)
{
    window.location.href = "edit_food_preorder.php?reserv_id="+rid;
}

</script>
