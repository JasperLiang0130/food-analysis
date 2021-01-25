<?php
    include '../Dbh.php';
    include '../Model/Order_items/OrderItemDAO.php';
    include '../Model/Items/ItemDAO.php';
    $db = new DBh();
    $conn = $db->getConnection();
    $arr_res_count = init_arr_order_item_count();
    $orderItemDao = new OrderItemDAO();
    $arr_query = $orderItemDao->getAllIncName();
    calTotalCount($arr_query, $arr_res_count);

    function calTotalCount($arr_query, &$arr_res_count){
        foreach ($arr_query as $key => $value) {
            $arr_res_count[$arr_query[$key]['Name']]++;
        }
    }

    function init_arr_order_item_count()
    {
        $itemDao = new ItemDAO();
        $itemGetAll = $itemDao->getAll(); //get all items's distinct name
        $arr = array(); //declare order item so that storing calculate num each item
        foreach ($itemGetAll as $obj) {
            $arr[] = $obj->getName();
        }
        $arr = array_flip($arr); //value and key are exchanged.
        //initial value to 0 each order item
        foreach ($arr as &$value) {
            $value = 0;
        }
        return $arr;
    }

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
                        <h3 >Most frequent bought items <b>Overall</b> </h3>
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
                        <canvas id="myChart"></canvas>
                    </div>
            </div>
        </div>
    </div>
 
    <script type="text/javascript">

        //Global options
        Chart.defaults.global.defaultFontFamily = 'Lato';
        Chart.defaults.global.defaultFontSize = 16;
        Chart.defaults.global.defaultFontColor ='#777';
        var arr_query =  <?php echo json_encode($arr_query, JSON_HEX_TAG); ?>; //total
        var arr_res_count = <?php echo json_encode($arr_res_count, JSON_HEX_TAG); ?>;
        //console.log(JSON.stringify(arr_query));
        console.log(JSON.stringify(arr_res_count));
        //console.log(arr_query[0]['Name']);
        
        let genCOLORs = genColors(arr_res_count);
        let myChart = document.getElementById('myChart').getContext('2d');
        var chart = new Chart(myChart, {
            // The type of chart we want to create
            type: 'bar',
            // The data for our dataset
            data: {
                labels: [],
                datasets: [{
                    label: 'Most frequently bought items overall',
                    borderColor: genCOLORs,
                    backgroundColor: genCOLORs,
                    data: []
                }]
            },
            // Configuration options go here
            options: {
                    scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });


        $(window).on("load", function(){
            
            //set date as today
            setToday();

            //update chart when the date is load
            updateChart(arr_res_count);

        });

        function genColors(arr_res_count){
            var colors = [];
            var num = Object.keys(arr_res_count).length-1;
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
        
        
        function updateChart(arr_res_count)
		{
            //console.log(arr_res_count);
            var labels = Object.keys(arr_res_count);
            var info = Object.values(arr_res_count);
            //console.log(info);

			chart.data.datasets[0].data = info;
			chart.data.labels = labels;
            chart.update();
            
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

            arr_res_count = filterOfDateTime(date_start, date_end);
            console.log(JSON.stringify(arr_res_count));
            updateChart(arr_res_count); 
        }

        function setDateFormat(date){
            var day = ("0" + date.getDate()).slice(-2); //less than 9, add 0
            var month = ("0" + (date.getMonth() + 1)).slice(-2);
            return date.getFullYear() + "-" + (month) + "-" + (day);
        }

        function initCount(arr_res_count){
            //console.log(JSON.stringify(arr_res_count));
            for (const key in arr_res_count) {
                arr_res_count[key] = 0;
            }
            return arr_res_count;
        }

		function filterOfDateTime(date_start, date_end)
		{
            //initial count of items
            arr_res_count = initCount(arr_res_count);
            //calculate total count to item
            for (const key in arr_query) {
                date_order = new Date(arr_query[key]['DateTime']);
                if(date_order < date_end && date_order >= date_start){
                    //console.log(date_order);
                    //console.log(arr_query[key]['Name']);
                    arr_res_count[arr_query[key]['Name']]++;
                }
            }
			return arr_res_count;
		}


    </script>

    
</body>
</html>



