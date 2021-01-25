<?php

    include '../Dbh.php';
    include '../Model/Orders/OrderDAO.php';
    include '../Model/Customer/CustomerDAO.php';

    $action = $_POST["action"]; //check whether it comes from overall or most freq item
    $start = $_POST["start"]; //start date
    $end = $_POST["end"]; //end date
    
    $db = new DBh();
    $conn = $db->getConnection();
    $orderDao = new OrderDAO();
    $customerDao = new CustomerDAO();
    $arr_res_count = null;
    $arr_res_count2 = null;
    $arr_res_count3 = null;
    $arr_res_count4 = null;

    if($action == 'popularDay')
    {
        $db->begin_transaction();
        $arr_res_count = $orderDao->getPopularDays($start, $end); //query result arr
        $arr_res_count2 = $orderDao->getPopularHoursByDay($start, $end); 
        $arr_res_count3 = $customerDao->getPeopleJoinDay(getDateFormat($start, $end), $start, $end); 
        $arr_res_count4 = $orderDao->getTotalOrders(getDateFormat($start, $end), $start, $end); 
        $db->commit();
        
    }
    
    echo json_encode([$arr_res_count, $arr_res_count2, $arr_res_count3, $arr_res_count4]);//return result (json)

    function getDateFormat($start, $end){
        $diff = intval(date_diff(date_create($start), date_create($end))->format("%a"));
        if($diff <= 28)
        {
            return "%Y-%m-%d";
        }
        else if($diff > 730) //over than 2y
        {
            return "%Y";
        }
        else{ //month
            return "%Y-%m";
        }
    }

?>