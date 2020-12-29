<?php
    include '../db_conn.php';
    include '../Model/Order_items/OrderItemDAO.php';
    include '../Model/Items/ItemDAO.php';

    $arr_res_count = init_arr_order_item_count();
    
    function display($arr_res_count, $order_count, $itemName)
    {
        echo 'What are the most common items purchased with a <b>'.$itemName.'</b>?<br><br>';
        foreach ($arr_res_count as $key => $value) 
        {
            if($key != $itemName){
                $data = round(($value / $order_count)*100, 1);
                echo $key.' <b>'.$data ."%</b>\t". '(signifies that a total of '.$value.' out of '.$order_count.' of the time people buy a '.$itemName.' they also buy '.$key.')<br>';
            }  
        }
    }

    function countSort($x, $y)
    {
        if ($x==$y) return 0;
            return ($x > $y) ? -1 : 1;
    }

    function calTotalCount($arr_query, &$arr_res_count, $itemName){
        foreach ($arr_query as $key => $value) {
            if($value['Name'] != $itemName){
                $arr_res_count[$value['Name']]++;
            }
        }   
    }

    function init_arr_order_item_count()
    {
        $itemDao = new ItemDAO();
        $itemGetAll = $itemDao->getAll(); //get all items's distinct name
        $arr = array(); //declare order item so that storing calculate num each item
        foreach ($itemGetAll as $obj) {
            $arr[] = $obj->getName();
        }
        $arr = array_flip($arr); //value and key are exchanged.
        //initial value to 0 each order item
        foreach ($arr as &$value) {
            $value = 0;
        }
        return $arr;
    }

    function getOrderCount($arr_query)
    {
        $res = array();
        foreach ($arr_query as $key => $value) {
            $res[] = $value['OrderID'];
        }
        //print_r(array_unique($res));
        return count(array_unique($res));
    }

?>  

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Most Frequent Page</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <style type="text/css">
    html,
    body {
        height: 100%;
        background-color: rgb(103, 216, 163, 0.5);
    }

    .container {
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    </style>
</head>
<body >
	<!-- This nav is for sign in, sign up and some of links of cafe menu -->   
    <div class="container">
	    <div class="text-center" >
			<form action="#"  method="post" class="">
				<div class="input-group mb-3">
					<div class="input-group-prepend">
                        <span class="input-group-text">Item</span>
                    </div>
					<select class="form-control" name="item">
                        <?php
                            foreach ($arr_res_count as $key => $value) {
                                if(isset($_POST["item"]) && $_POST["item"] == $key){
                                    echo '<option value="'.$key.'" selected>'.$key.'</option>';
                                }else{
                                    echo '<option value="'.$key.'">'.$key.'</option>';
                                }
                            }
                        ?>
                    </select>	    
				</div>
				<div class="form-group">
					<div class="column-6">						  
						<button type="submit" class="btn btn-primary btn-block">Submit</button>
					</div>
				</div>
			</form>				
        </div>  
        <div class="text-center">
            <?php
                if(isset($_POST["item"])){

                    $itemName = $_POST["item"];
                    $orderItemDao = new OrderItemDAO();
                
                    $arr_query = $orderItemDao->query($itemName);
                    $order_count = getOrderCount($arr_query); //for calculate % 
                    
                    calTotalCount($arr_query, $arr_res_count, $itemName);
                    uasort($arr_res_count,"countSort"); //sorting number from max to min
                    
                    // print_r($arr_res_count);
                    // echo '<br>';
                    
                    display($arr_res_count, $order_count, $itemName);
                }
            ?>
        </div>
	</div>

    
</body>
</html>



