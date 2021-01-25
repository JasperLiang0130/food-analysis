<?php
    include '../Dbh.php';
    include '../Model/Customer/CustomerDAO.php';
    include '../Model/Orders/OrderDAO.php';
    $db = new DBh();
    $conn = $db->getConnection();
    $db->begin_transaction();
    $start = date_format(date_create()->setTimestamp(strtotime("January 1 2018 00:00:00 GMT"))->setTimezone(new DateTimeZone('Australia/Sydney')), "Y-m-d");
    $end = date_format(date_create()->setTimestamp(strtotime("now"))->setTimezone(new DateTimeZone('Australia/Sydney')), "Y-m-d");

    $customerDao = new CustomerDAO();
    $countCustomers =  $customerDao->getTotalCountByDate($start, $end);
    //echo $countCustomers;
    $orderDAO = new OrderDAO();
    $revenue = $orderDAO->getTotalRevenue($start, $end);
    $countOrders = $orderDAO->getTotalCountOrd($start, $end);
    $highestRevenue = $orderDAO->getHighestOrderValue($start, $end);
    $lowestRevenue = $orderDAO->getLowestOrderValue($start, $end);
    $avgTotalItem = $orderDAO->getAvgTotalItems($start, $end);
    $avgDistItem = $orderDAO->getAvgDistinctItems($start, $end);
    $db->commit();

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Simple number display Page</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <!--flatpickr for calander  -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <style type="text/css">
        html,
        body {
            /* max-height: 100%; */
            background-color: rgb(103, 216, 163, 0.5);
        }
        .myNav {
            margin-bottom: 5vh;
        }
        .fluid-container {
            height: 90%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        table, td, th {
            border: 1px solid black;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            text-align: left;
            padding: 5px;
        }
    </style>
</head>
<body>
    <?php include '../Shared/navbar.txt'?>
    <div class="fluid-container"> 
        <div class="row">
            <div class="col-md-12 card">
                <div class="card-body">
                    <h3 >Summary statistic <b>Overall</b> </h3>
                    <h2>
                        <input id='myDate' class="btn btn-info" onChange="updateTotalCountByDate()">
                        <select id='myRange' class="btn btn-success" onChange="updateTotalCountByDate()">
                            <option value="" selected>Past to specific date</option>
                            <option value="7">Last 7 days</option>
                            <option value="30">Last 30 days</option>
                            <option value="6">Last 6 months</option><option value="12">Last 12 months</option>
                        </select>
                    </h2>
                    <div id='showDate' class='text-right'></div>
                    <table id='t'>
                        <tr>
                            <th>Name</th>
                            <th>Value</th>
                        </tr>
                        <tr>
                            <td>Total Customers</td>
                            <td><?php echo $countCustomers;?></td>
                        </tr>
                        <tr>
                            <td>Total Revenue</td>
                            <td><?php echo $revenue;?></td>
                        </tr>
                        <tr>
                            <td>Total Orders</td>
                            <td><?php echo $countOrders;?></td>
                        </tr>
                        <tr>
                            <td>Average Orders/Customers</td>
                            <td><?php echo round($countOrders/$countCustomers,2);?></td>
                        </tr>
                        <tr>
                            <td>Average Revenue/Orders</td>
                            <td><?php echo round($revenue/$countOrders,2);?></td>
                        </tr>
                        <tr>
                            <td>Average Revenue/Customers</td>
                            <td><?php echo round($revenue/$countCustomers,2);?></td>
                        </tr>
                        <tr>
                            <td>Highest order value</td>
                            <td><?php echo $highestRevenue;?></td>
                        </tr>
                        <tr>
                            <td>Lowest order value</td>
                            <td><?php echo $lowestRevenue;?></td>
                        </tr>
                        <tr>
                            <td>Average item per order</td>
                            <td><?php echo round($avgTotalItem,2);?></td>
                        </tr>
                        <tr>
                            <td>Average distinct item per order</td>
                            <td><?php echo round($avgDistItem,2);?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <script  type="text/javascript">
        
        $(window).on("load", function(){
            
            //set date as today
            setToday();

            //update table when the date is load
            //updateTable();

        });

        function setToday(){
            flatpickr("#myDate", {dateFormat: "Y-m-d"});
            var now = new Date();
            var day = ("0" + now.getDate()).slice(-2); //less than 9, add 0
            var month = ("0" + (now.getMonth() + 1)).slice(-2);
            var today = now.getFullYear() + "-" + (month) + "-" + (day);
            $("#myDate").val(today);
            //console.log(today);
        }

        function updateTotalCountByDate(){
            var date_end = new Date($("#myDate").val());
            var period = parseInt($("#myRange").val());
            var date_start = null;
			//console.log(period);
			switch(period){
				case 7:
                    date_start = new Date(date_end.getFullYear(),date_end.getMonth(), date_end.getDate()-period);
					break;
				case 30:
					date_start = new Date(date_end.getFullYear(),date_end.getMonth(), date_end.getDate()-period);
					break;
				case 12:
                    date_start = new Date(date_end.getFullYear(),date_end.getMonth()-period, date_end.getDate());
					break;
				case 6:
					date_start = new Date(date_end.getFullYear(),date_end.getMonth()-period, date_end.getDate());
					break;
				default:
                    date_start = new Date(0); //past January 01, 1970
                    
            }
            //showing period to user
            $('#showDate').empty().text(setDateFormat(date_start)+' ~ '+setDateFormat(date_end));

            filterOfDateTime(setDateFormat(date_start), setDateFormat(date_end));
            //console.log(JSON.stringify(arr_res_count));
        }

        function setDateFormat(date){
            var day = ("0" + date.getDate()).slice(-2); //less than 9, add 0
            var month = ("0" + (date.getMonth() + 1)).slice(-2);
            return date.getFullYear() + "-" + (month) + "-" + (day);
        }

        function filterOfDateTime(date_start, date_end)
		{
             $.ajax({
                url : 'http://<?php echo $_SERVER['HTTP_HOST'];?>/Controller/getUpdateForSummaryData.php',
                data : {
                            start :	date_start,
                            end :	date_end 
                        },
                        type : 'POST',
                        dataType:"json",
                        error : function(xhr) {
                            console.log(xhr.status);
                            console.log(xhr.responseText);
                            alert('Ajax request error');
                        },
                        success : function(result) {		  					
                            updateTable(result);
                        }
            });
		}

        function updateTable(summary){
            $("#t td:odd").empty().each(function(index, value){
                $(this).append(summary[index]);
            });
        }

    </script>
</body>
</html>
