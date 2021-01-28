<?php

    include '../Dbh.php';
    include '../Model/Order_items/OrderItemDAO.php';

    $action = $_POST["action"]; //check whether it comes from overall or most freq item
    $start = $_POST["start"]; //start date
    $end = $_POST["end"]; //end date

    $db = new DBh();
    $conn = $db->getConnection();
    $orderItemDao = new OrderItemDAO();
    
    if($action == 'overall')
    {
        $arr_res_count = $orderItemDao->getAllIncName2($start, $end); //query result arr
    }
    else if($action == 'freqItem')
    {
        $itemName = $_POST["itemName"];
        $arr_res_count = $orderItemDao->queryByDate2($itemName, $start, $end); //query result arr
    }
    
    echo json_encode($arr_res_count);//return result (json)


?>