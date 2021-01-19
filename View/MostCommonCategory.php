<?php
    include '../db_conn.php';
    include '../Model/Order_items/OrderItemDAO.php';

    
    $orderItemDao = new OrderItemDAO();
    $start = date_format(date_create()->setTimestamp(strtotime("January 1 1970 00:00:00 GMT"))->setTimezone(new DateTimeZone('Australia/Sydney')), "Y-m-d");
    $end = date_format(date_create()->setTimestamp(strtotime("now"))->setTimezone(new DateTimeZone('Australia/Sydney')), "Y-m-d");
    $arr_res_count = $orderItemDao->getAllCategoryCount($start, $end);

?>  

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Most Freq items Overall Page</title>
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
            /* max-height: 100%; */
            background-color: rgb(103, 216, 163, 0.5);
        }
        .myNav {
            margin-bottom: 5vh;
        }
        .fluid-container {
            height: 90%;
            /* display: flex;
            justify-content: center;
            align-items: center; */
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
    <div class="fluid-container"> 
    <?php include '../Shared/navbar.txt'?>
        <div class="row">
            <div class="col-md-9 card" >
                <div class="card-body" >
                    <h3 >Most common <b>category</b> counts </h3>
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
                    <canvas id="myChart" ></canvas>
                </div>
            </div>
            <div class="col-md-3" id="tableArea">
            </div>
        </div>
    </div>
 
    <script type="text/javascript">

        var arr_res_count = <?php echo json_encode($arr_res_count, JSON_HEX_TAG); ?>;

        // console.log(JSON.stringify(arr_res_count));
        // console.log(getCategoryRankName((arr_res_count)));
        // console.log(getCategoryRankCount((arr_res_count)));

        let myChart = document.getElementById('myChart').getContext('2d');
        let chart = new OwnChart(myChart, 'pie', 1, false);
        let colors = [[randomColor(), randomColor(), randomColor(), randomColor()]];
        //chart.setBorderColor(colors); 
        chart.setBackgroundColor(colors); 
        chart.setXAxis(getCategoryRankName((arr_res_count))); 

        $(window).on("load", function(){
            
            //set date as today
            setToday();

            //update chart when the date is load
            chart.updatedChart([getCategoryRankCount(arr_res_count)]);
            updatedTable(arr_res_count);

        });

        function getCategoryRankName(arr){
            out = [];
            arr.forEach(element => {
                out.push(element['Name']);
            });
            return out;
        }

        function getCategoryRankCount(arr){
            out = [];
            arr.forEach(element => {
                out.push(element['Count']);
            });
            return out;
        }

        function updatedTable(arr_res_count){
            $("#tableArea").empty().append("<table id='t'><tr><th>Name</th><th>Count</th></></table>");
            $("#t").each(function(){
                arr_res_count.forEach(element => {
                    $(this).append("<tr><td>"+element['Name']+"</td><td>"+element['Count']+"</td></tr>");
                });
            })
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
                url : 'http://<?php echo $_SERVER['HTTP_HOST'];?>/Controller/getCategoriesData.php',
                data : {
                            action : 'categoriesUpdate',
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
                            chart.updatedChart([getCategoryRankCount(result)]);
                            updatedTable(result);
                        }
            });
		}

    </script>

    
</body>
</html>



