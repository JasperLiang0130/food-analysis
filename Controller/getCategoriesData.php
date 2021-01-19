<?php

    include '../db_conn.php';
    include '../Model/Order_items/OrderItemDAO.php';

    $action = $_POST["action"]; //check 
    $start = $_POST["start"]; //start date
    $end = $_POST["end"]; //end date
    
    $orderItemDao = new OrderItemDAO();
    $arr_res_count = null;
    

    if($action == 'categoriesUpdate')
    {
        $arr_res_count = $orderItemDao->getAllCategoryCount($start, $end);
        
    }
    
    echo json_encode($arr_res_count);//return result (json)


?>