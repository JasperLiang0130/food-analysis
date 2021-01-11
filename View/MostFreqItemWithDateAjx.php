<?php
    include '../db_conn.php';
    include '../Model/Order_items/OrderItemDAO.php';
    include '../Model/Items/ItemDAO.php';

    $arr_res_count = init_arr_order_item_count();

    function countSort($x, $y)
    {
        if ($x==$y) return 0;
            return ($x > $y) ? -1 : 1;
    } 

    function calTotalCount($arr_query, &$arr_res_count, $itemName){
        foreach ($arr_query as $key => $value) {
            if($value['Name'] != $itemName){
                $arr_res_count[$value['Name']]++;
            }
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

    function getOrderCount($arr_query)
    {
        $res = array();
        foreach ($arr_query as $key => $value) {
            $res[] = $value['OrderID'];
        }
        //print_r(array_unique($res));
        return count(array_unique($res));
    }

    if(isset($_POST["item"])){
        $itemName = $_POST["item"];
        $orderItemDao = new OrderItemDAO();
    
        $arr_query = $orderItemDao->query($itemName);
        
        calTotalCount($arr_query, $arr_res_count, $itemName);
        uasort($arr_res_count,"countSort"); //sorting number from max to min
    }

?>  

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Most Frequent With Date Page</title>
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
            height: 100%;
            background-color: rgb(103, 216, 163, 0.5);
        }
        .fluid-container {
            height: 90%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #chartArea, #tableArea{
            visibility: hidden;
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
    <!-- This nav is for sign in, sign up and some of links of cafe menu -->   
    <?php include '../Shared/navbar.txt'?>
    <div class="fluid-container">
    
        <div class="col-md-2" >
			<form method="post" id='itemForm' class="">
				<div class="input-group mb-3">
					<div class="input-group-prepend">
                        <span class="input-group-text">Item</span>
                    </div>
					<select class="form-control" name="item">
                        <?php
                            foreach ($arr_res_count as $key => $value) {
                                if(isset($_POST["item"]) && $_POST["item"] == $key){
                                    echo '<option value="'.$key.'" selected>'.$key.'</option>';
                                }else{
                                    echo '<option value="'.$key.'">'.$key.'</option>';
                                }
                            }
                        ?>
                    </select>   
				</div>
				<div class="form-group">
					<div class="column-6">						  
						<button type="submit" class="btn btn-primary btn-block">Submit</button>
					</div>
				</div>
			</form>				
        </div>  
        <div id="chartArea" class="col-md-7 card">
            <div class="card-body">
                <h3 >Most frequent items bought with <b><?php if(isset($_POST["item"])){echo $_POST["item"];} ?></b> </h3>
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
        <div class="col-md-2" id="tableArea">
            
        </div>
    
    </div>
    <script type="text/javascript">

        var removeItem = <?php if(isset($_POST["item"])){echo json_encode($_POST["item"]);} ?>;

        //if remove item is not null
        if(removeItem){
            document.getElementById("chartArea").style.visibility = "visible";
            document.getElementById("tableArea").style.visibility = "visible";
        }
        

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
                    label: 'My food analysis',
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
            updateTable(arr_res_count);

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
        
        function updateTable(arr_res_count){
            $("#tableArea").empty().append("<table id='t'><tr><th>Name</th><th>Count</th></></table>");
            $("#t").each(function(){
                for (const key in arr_res_count) {
                    if(key != removeItem){
                        $(this).append("<tr><td>"+key+"</td><td>"+arr_res_count[key]+"</td></tr>");
                    }
                }
            })
        }
        
        function updateChart(arr_res_count)
		{
            //console.log(arr_res_count);
            var labels = Object.keys(arr_res_count);
            var info = Object.values(arr_res_count);
            //console.log(labels);
            //console.log(info);
            //remove last item because all orders have this item
            for(var i=0; i< labels.length; i++)
            {
                if(labels[i].localeCompare(removeItem) == 0)
                {
                    labels.splice(i, 1);
                    info.splice(i, 1);
                }
            }

            //console.log(info);
            //console.log(labels);

			chart.data.datasets[0].label = 'Most freq bought items with '+removeItem;
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

            filterOfDateTime(setDateFormat(date_start), setDateFormat(date_end));
        }

        function setDateFormat(date){
            var day = ("0" + date.getDate()).slice(-2); //less than 9, add 0
            var month = ("0" + (date.getMonth() + 1)).slice(-2);
            return date.getFullYear() + "-" + (month) + "-" + (day);
        }

		function filterOfDateTime(date_start, date_end)
		{
            // console.log(JSON.stringify(arr_res_count));
            // console.log(JSON.stringify(date_start));
            // console.log(JSON.stringify(date_end));
            // console.log(JSON.stringify(removeItem));
            $.ajax({
                url : 'http://<?php echo $_SERVER['HTTP_HOST'];?>/Controller/getDataOutputForMostFreqItems.php',
                data : {
                            action : 'freqItem',
                            arr_res_count :	JSON.stringify(arr_res_count),
                            start :	date_start,
                            end :	date_end,
                            itemName : removeItem  
                        },
                        type : 'POST',
                        dataType:"json",
                        error : function(xhr) {
                            alert('Ajax request error');
                        },
                        success : function(result) {		  					     
                            updateChart(result); 
                            updateTable(result);
                        }
            });
		}


    </script>

    
</body>
</html>



