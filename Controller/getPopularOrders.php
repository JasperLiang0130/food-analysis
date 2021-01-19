<?php

    include '../db_conn.php';
    include '../Model/Orders/OrderDAO.php';
    include '../Model/Customer/CustomerDAO.php';

    $action = $_POST["action"]; //check whether it comes from overall or most freq item
    $start = $_POST["start"]; //start date
    $end = $_POST["end"]; //end date
    
    $orderDao = new OrderDAO();
    $customerDao = new CustomerDAO();
    $arr_res_count = null;
    $arr_res_count2 = null;
    $arr_res_count3 = null;
    $arr_res_count4 = null;

    if($action == 'popularDay')
    {
        $arr_res_count = $orderDao->getPopularDays($start, $end); //query result arr
        $arr_res_count2 = $orderDao->getPopularHoursByDay($start, $end); 
        $arr_res_count3 = $customerDao->getPeopleJoinDay($start, $end); 
        $arr_res_count4 = $orderDao->getTotalOrders($start, $end); 
        
    }
    
    echo json_encode([$arr_res_count, $arr_res_count2, $arr_res_count3, $arr_res_count4]);//return result (json)


?>