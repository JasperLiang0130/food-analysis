<?php
    include '../db_conn.php';
    include '../Model/Orders/OrderDAO.php';
    include '../Model/Customer/CustomerDAO.php';

    $orderDao = new OrderDAO();
    $customerDao = new CustomerDAO();
    $start = date_format(date_create()->setTimestamp(strtotime("January 1 1970 00:00:00 GMT"))->setTimezone(new DateTimeZone('Australia/Sydney')), "Y-m-d");
    $end = date_format(date_create()->setTimestamp(strtotime("now"))->setTimezone(new DateTimeZone('Australia/Sydney')), "Y-m-d");
    $arr_res_count = $orderDao->getPopularDays($start, $end);
    $arr_res_count2 = $orderDao->getPopularHoursByDay($start, $end);
    $arr_res_count3 = $customerDao->getPeopleJoinDay($start, $end);
    $arr_res_count4 = $orderDao->getTotalOrders($start, $end);

?>  

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Most Popular display Page</title>
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
    <script src="../wwwroot/js/OwnChart.js"></script>
    <style type="text/css">
        html,
        body {
            max-height: 100%;
            background-color: rgb(103, 216, 163, 0.5);
        }
        .container {
            height: 90%;
        }
    </style>
</head>
<body>
    <?php include '../Shared/navbar.txt'?>
    <div class="container"> 
        <div class="row">
            <div class="col-md-11">
                <div class="card">
                    <div class="card-body">
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
                    </div>
                    <div class="card-body">
                        <h3>Most popular <b>Days</b> for orders </h3>
                        <canvas id="myChart"></canvas>
                    </div>
                    <div class="card-body">
                        <h3>Most popular <b>Hours</b> by days for orders </h3>
                        <canvas id="myChart2"></canvas>
                    </div>
                    <div class="card-body">
                        <h3>People <b>First Join</b> day </h3>
                        <canvas id="myChart3"></canvas>
                    </div>
                    <div class="card-body">
                        <h3>Total <b>Orders</b> graph </h3>
                        <canvas id="myChart4"></canvas>
                    </div>
            </div>
        </div>
    </div>
 
    <script type="text/javascript">

        var arr_res_count = <?php echo json_encode($arr_res_count, JSON_HEX_TAG); ?>;
        var arr_res_count2 = <?php echo json_encode($arr_res_count2, JSON_HEX_TAG); ?>;
        var arr_res_count3 = <?php echo json_encode($arr_res_count3, JSON_HEX_TAG); ?>;
        var arr_res_count4 = <?php echo json_encode($arr_res_count4, JSON_HEX_TAG); ?>;
        // console.log(JSON.stringify(arr_res_count4));
        // console.log(getOrderByFirstOrder((arr_res_count4)));
        
        let myChart = document.getElementById('myChart').getContext('2d');
        let chart = new OwnChart(myChart, 'bar', 1, false);
        let colors = [genColors(arr_res_count)];
        chart.setBorderColor(colors); 
        chart.setBackgroundColor(colors); 
        // chart.setXAxis(Object.keys(arr_res_count));
        chart.setXAxis(['Sun','Mon','Tues','Wed','Thur','Fri','Sat']); //1=Sunday, 2=Monday, 3=Tuesday, 4=Wednesday, 5=Thursday, 6=Friday, 7=Saturday.
        
        let myChart2 = document.getElementById('myChart2').getContext('2d');
        let chart2 = new OwnChart(myChart2, 'bar', 7, true);
        let colors2 = [randomColor(), randomColor(), randomColor(), randomColor(),randomColor(), randomColor(), randomColor()];
        chart2.setBorderColor(colors2); 
        chart2.setBackgroundColor(colors2); 
        let titles = ['Sun','Mon','Tues','Wed','Thur','Fri','Sat'];
        chart2.setTitles(titles);
        chart2.setXAxis(['0:00', '1:00', '2:00', '3:00', '4:00', '5:00', '6:00', '7:00', '8:00', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00']);

        let myChart3 = document.getElementById('myChart3').getContext('2d');
        let chart3 = new OwnChart(myChart3, 'bar');
        let colors3 = [genColors(arr_res_count3)];
        chart3.setBorderColor(colors3); 
        chart3.setBackgroundColor(colors3); 
        // chart.setXAxis(Object.keys(arr_res_count));
        chart3.setXAxis(['Sun','Mon','Tues','Wed','Thur','Fri','Sat']); //1=Sunday, 2=Monday, 3=Tuesday, 4=Wednesday, 5=Thursday, 6=Friday, 7=Saturday.

        let myChart4 = document.getElementById('myChart4').getContext('2d');
        let chart4 = new OwnChart(myChart4, 'bar', 2, true);
        let colors4 = [randomColor(), randomColor()];
        chart4.setBorderColor(colors4); 
        chart4.setBackgroundColor(colors4); 
        let titles4 = ['Existing Customers order', 'New Customers order'];
        chart4.setTitles(titles4);
        chart4.setXAxis(['Sun','Mon','Tues','Wed','Thur','Fri','Sat']);

        $(window).on("load", function(){
            
            //set date as today
            setToday();

            //update chart when the date is load
            chart.updatedChart([getDayValue(arr_res_count)]);
            chart2.updatedChart(getHourValue(arr_res_count2));
            chart3.updatedChart([getPeopleJoinCount(arr_res_count3)]);
            chart4.updatedChart(getOrderByFirstOrder(arr_res_count4));

        });

        function getPeopleJoinCount(arr){
            res = [0, 0, 0, 0, 0, 0, 0];
            for (const key in arr) {
                res[arr[key]['Day']-1] = parseInt(arr[key]['Count']);
            }
            return res;
        }

        function getDayValue(arr){ //arr already order by day
            res = [0, 0, 0, 0, 0, 0, 0];
            for (const key in arr) {
                res[arr[key]['Day']-1] = parseInt(arr[key]['sumItem']);
            }
            return res;
        }

        function getOrderByFirstOrder(arr){
            out = [];
            //initial out
            for(var i = 0; i < 2; i++){
                var week = [0, 0, 0, 0, 0, 0, 0];
                out.push(week);
            }
            //assign the value
            for (const key in arr) {
                out[arr[key]['FirstOrder']][arr[key]['Day']-1] = parseInt(arr[key]['sumItem']);
            }
            return out;
        }

        function getHourValue(arr){
            out = [];
            //initial out
            for(var i = 0; i < 7; i++){
                var hourOfday = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                out.push(hourOfday);
            }
            //console.log(out);
            //assign the value
            for (const key in arr) {
                out[arr[key]['Day']-1][arr[key]['Hour']] = parseInt(arr[key]['sumItem']);
            }
            return out;
        }

        function genColors(arr) {
            var colors = [];
            var num = Object.keys(arr).length;
            for (let index = 0; index < num; index++) {
                colors.push(randomColor());
            }
            return colors;
        }
        function randomColor() {
            var r = Math.floor(Math.random() * 255);
            var g = Math.floor(Math.random() * 255);
            var b = Math.floor(Math.random() * 255);
            return "rgb(" + r + "," + g + "," + b + ")";
        }

        
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
                url : 'http://<?php echo $_SERVER['HTTP_HOST'];?>/Controller/getPopularOrders.php',
                data : {
                            action : 'popularDay',
                            start :	date_start,
                            end :	date_end 
                        },
                        type : 'POST',
                        dataType:"json",
                        error : function(xhr) {
                            alert('Ajax request error');
                        },
                        success : function(result) {		  					     
                            console.log(result);
                            chart.updatedChart([getDayValue(result[0])]);
                            chart2.updatedChart(getHourValue(result[1]));
                            chart3.updatedChart([getPeopleJoinCount(result[2])]);
                            chart4.updatedChart(getOrderByFirstOrder(result[3]));
                        }
            });
		}


    </script>

    
</body>
</html>



