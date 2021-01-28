<!-- Source from: https://github.com/startbootstrap/startbootstrap-simple-sidebar -->
<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>Generate dummy data</title>

  <!-- Bootstrap core CSS -->
  <link href="../wwwroot/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../wwwroot/css/simple-sidebar.css" rel="stylesheet">
  <link href="../wwwroot/css/index.css" rel="stylesheet">


</head>

<body>

  <div class="d-flex">

    <!-- Sidebar -->
    <?php 
        include '../Shared/sidebar.txt';
        // include '../db_conn.php';
        include '../Dbh.php';
        include '../Model/Customer/CustomerDAO.php';
        include '../Model/Items/ItemDAO.php';
        include '../Model/Option_Sets/OptionSetDAO.php';
        include '../Model/Options/OptionDAO.php';
        include '../Model/Orders/OrderDAO.php';
        include '../Model/Order_items/OrderItemDAO.php';
        include '../Model/Order_options/OrderOptionDAO.php';
        include 'generate-customer.php';
        include 'generate-order.php';
    ?>

      <div class="container-fluid">
        <?php   
            $customerQt = intval($_POST["customer"]);
            $orderQt = intval($_POST["order"]);
            
            $db = new DBh();
            $conn = $db->getConnection();
            
            $db->begin_transaction();
            generateCustomer($customerQt);
            
            $start_time = microtime(true); 
            generateOrder($orderQt);
            $end_time = microtime(true); 
            $execution_time = ($end_time - $start_time); 
            echo "<code> Execution time of script = ".$execution_time." sec </code>"; 
            $db->commit();

        ?>
      </div>

  <!-- Bootstrap core JavaScript -->
  <script src="../wwwroot/jquery/jquery.min.js"></script>
  <script src="../wwwroot/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>
