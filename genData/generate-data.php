<?php

    include '../db_conn.php';
    include '../Model/Customer/CustomerDAO.php';
    include '../Model/Items/ItemDAO.php';
    include '../Model/Option_Sets/OptionSetDAO.php';
    include '../Model/Options/OptionDAO.php';
    include '../Model/Orders/OrderDAO.php';
    include '../Model/Order_items/OrderItemDAO.php';
    include '../Model/Order_options/OrderOptionDAO.php';
    include 'generate-customer.php';
    include 'generate-order.php';

    $customerQt = intval($_POST["customer"]);
    $orderQt = intval($_POST["order"]);

    // echo 'customer: '.$customerQt.'<br>';
    // echo 'order: '.$orderQt.'<br>';
    
    generateCustomer($customerQt);
    genOrder($orderQt);


?>