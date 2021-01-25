<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Generate Page</title>
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
        background-color: rgba(255, 99, 71, 0.4);
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
	    <div class="row" >
			<form action="generate-data.php"  method="post" >
				<div class="form-group">
					<label for="Customer">Customer Quantity</label>
					<input type="number" class="form-control" name="customer" value="0">	    
				</div>
				<div class="form-group">
					<label for="Order">Order Quantity</label>
					<input type="number" class="form-control" name="order" value="0">		 
				</div>
				<div class="form-group">
					<div class="column-6">						  
						<button type="submit" class="btn btn-primary btn-block">Submit</button>
					</div>
				</div>
			</form>				
		
		</div>
	</div>

    
</body>
</html>
<?php
	include '../Dbh.php';
	refreshSQL(); //delete previous data
	function refreshSQL(){

		$db = new DBh();
		$conn = $db->getConnection();
		$db->begin_transaction();
		$dir = "../resources/";
		$fileName = "food_ordering_system_v2021.01.20.sql";
		$sqlFile = fopen($dir.$fileName, "r") or die("Unable to open file!");
		$sql = fread($sqlFile,filesize($dir.$fileName));

		if ($conn->multi_query($sql) === TRUE) {
			echo "Refresh food db successfully<br>";
		} else {
			echo $conn->error;
		}
		$db->commit();
	}
?>