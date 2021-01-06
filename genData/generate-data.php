<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Generate result</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>
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
        include '../Shared/navbar.txt';
        $customerQt = intval($_POST["customer"]);
        $orderQt = intval($_POST["order"]);

        // echo 'customer: '.$customerQt.'<br>';
        // echo 'order: '.$orderQt.'<br>';
        
        generateCustomer($customerQt);
        genOrder($orderQt);

    ?>
</body>
</html>