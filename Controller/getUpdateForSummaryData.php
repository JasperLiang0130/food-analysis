<?php

    include '../db_conn.php';
    include '../Model/Customer/CustomerDAO.php';
    include '../Model/Orders/OrderDAO.php';

    $start = $_POST["start"]; //start date
    $end = $_POST["end"]; //end date
    $summary = array();

    $customerDao = new CustomerDAO();
    $orderDAO = new OrderDAO();

    $countCustomers =  $customerDao->getTotalCountByDate($start, $end);
    $revenue = $orderDAO->getTotalRevenue($start, $end);
    $countOrders = $orderDAO->getTotalCountOrd($start, $end);
    $highestRevenue = $orderDAO->getHighestOrderValue($start, $end);
    $lowestRevenue = $orderDAO->getLowestOrderValue($start, $end);
    $avgTotalItem = $orderDAO->getAvgTotalItems($start, $end);
    $avgDistItem = $orderDAO->getAvgDistinctItems($start, $end);

    $summary[] = $countCustomers;
    $summary[] = $revenue;
    $summary[] = $countOrders;
    $summary[] = ($countCustomers==0)? 'N/A' : round($countOrders/$countCustomers,2);
    $summary[] = ($countOrders==0)? 'N/A' : round($revenue/$countOrders,2);
    $summary[] = ($countCustomers==0)? 'N/A' : round($revenue/$countCustomers,2);
    $summary[] = $highestRevenue;
    $summary[] = $lowestRevenue;
    $summary[] = round($avgTotalItem,2);
    $summary[] = round($avgDistItem,2);

    echo json_encode($summary);

?>