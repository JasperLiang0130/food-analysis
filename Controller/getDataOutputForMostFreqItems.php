<?php

    include '../db_conn.php';
    include '../Model/Order_items/OrderItemDAO.php';

    $action = $_POST["action"]; //check whether it comes from overall or most freq item
    $start = $_POST["start"]; //start date
    $end = $_POST["end"]; //end date
    $arr_res_count = (array)json_decode($_POST["arr_res_count"]); //last query result

    $orderItemDao = new OrderItemDAO();
    init_res_order_item_count($arr_res_count); //initial result arr
    
    if($action == 'overall')
    {
        $arr_query = $orderItemDao->getAllIncName($start, $end); //query result arr
        calTotalCountWithoutName($arr_query, $arr_res_count); //calculate count for each item
    }else if($action == 'freqItem')
    {
        $itemName = $_POST["itemName"];
        $arr_query = $orderItemDao->queryByDate($itemName, $start, $end); //query result arr
        calTotalCountWithName($arr_query, $arr_res_count, $itemName); //calculate count for each item
    }
    
    
    uasort($arr_res_count,"countSort"); //sorting number of item from max to min
    echo json_encode($arr_res_count);//return result (json)


    function init_res_order_item_count(&$arr_res_count)
    {
        foreach ($arr_res_count as $key => $value) {
            $arr_res_count[$key] = 0;
        }
    }

    function countSort($x, $y)
    {
        if ($x==$y) return 0;
            return ($x > $y) ? -1 : 1;
    } 

    function calTotalCountWithName($arr_query, &$arr_res_count, $itemName){
        foreach ($arr_query as $key => $value) {
            if($value['Name'] != $itemName){
                $arr_res_count[$value['Name']]++;
            }
        }   
    }

    function calTotalCountWithoutName($arr_query, &$arr_res_count){
        foreach ($arr_query as $key => $value) {
            $arr_res_count[$value['Name']]++;
        }   
    }

?>