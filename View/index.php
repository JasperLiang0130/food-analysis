<!-- Source from: https://github.com/startbootstrap/startbootstrap-simple-sidebar -->
<?php
    include '../Dbh.php';
    include '../Model/Customer/CustomerDAO.php';
    include '../Model/Orders/OrderDAO.php';
    include '../Model/Order_items/OrderItemDAO.php';
    include '../Model/Items/ItemDAO.php';
    $db = new DBh();
    $conn = $db->getConnection();
    $db->begin_transaction();
    //initial start date and end date
    $start = date_format(date_create()->setTimestamp(strtotime("January 1 1970 00:00:00 GMT"))->setTimezone(new DateTimeZone('Australia/Sydney')), "Y-m-d");
    $end = date_format(date_create()->setTimestamp(strtotime("now"))->setTimezone(new DateTimeZone('Australia/Sydney')), "Y-m-d");

    $customerDao = new CustomerDAO();
    $countCustomers =  $customerDao->getTotalCountByDate($start, $end); //get existing customers order
    //echo $countCustomers;
    //get summary data
    $orderDao = new OrderDAO();
    $revenue = $orderDao->getTotalRevenue($start, $end);
    $countOrders = $orderDao->getTotalCountOrd($start, $end);
    $highestRevenue = $orderDao->getHighestOrderValue($start, $end);
    $lowestRevenue = $orderDao->getLowestOrderValue($start, $end);
    $avgTotalItem = $orderDao->getAvgTotalItems($start, $end);
    $avgDistItem = $orderDao->getAvgDistinctItems($start, $end);
    
    $orderItemDao = new OrderItemDAO();
    $overall_arr = $orderItemDao->getAllIncName2($start, $end);  //get most freq item overall data
    $category_arr = $orderItemDao->getAllCategoryCount($start, $end); //get category data
    
    $popday_arr = $orderDao->getPopularDays($start, $end); //get popular days for order
    $pophour_arr = $orderDao->getPopularHoursByDay($start, $end); //get popular hours by days
    $joinday_arr = $customerDao->getPeopleJoinDay(getDateFormat($start, $end), $start, $end);
    $totalord_arr = $orderDao->getTotalOrders(getDateFormat($start, $end), $start, $end);

    $itemDao = new ItemDAO();
    $itemGetAll = $itemDao->getAll(); //get all items for setting chart
    
    $db->commit();
    //get date format according to different days defination
    function getDateFormat($start, $end){
        $diff = intval(date_diff(date_create($start), date_create($end))->format("%a"));
        if($diff <= 28)
        {
            return "%Y-%m-%d";
        }
        else if($diff > 730) //over than 2y
        {
            return "%Y";
        }
        else{ //month
            return "%Y-%m";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>Food-Data-Analysis</title>

  <!-- Bootstrap core CSS -->
  <link href="../wwwroot/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="../wwwroot/css/simple-sidebar.css" rel="stylesheet">
  <link href="../wwwroot/css/index.css" rel="stylesheet">

  <!--flatpickr for calander  -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <!-- chart.js sdn -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>

</head>

<body>

  <div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <?php include '../Shared/sidebar.txt'?>
    <!-- /#sidebar-wrapper -->
    
    <!-- Page Content: Make sure to keep all page content within the #page-content-wrapper -->
    <div id="page-content-wrapper">

      <!-- place fin nav bar -->
      <?php include '../Shared/fin-navbar.txt'?>

      <div class="container-fluid">
        <!-- Title -->
        <div class='mt-4 row'>
          <h1 class="col">Analytic Overview</h1>
          <div class="col text-right align-self-center">
              <input id='myDate' class="btn btn-info mr-2" onChange="updateTotalCountByDate()">
              <select id='myRange' class="btn btn-success " onChange="updateTotalCountByDate()">
                <option value="" selected>Past to specific date</option>
                <option value="7">Last 7 days</option>
                <option value="28">Last 28 days</option>
                <option value="6">Last 6 months</option><option value="12">Last 12 months</option>
              </select>
          </div>
        </div>
        
        <!-- subtitle -->
        <div class='row'>
          <p class="col-md-8">The overview is showing the <code>basic food data statistic</code> by applying data from the database. </p>
          <div id='showDate' class='col-md-4 text-right'></div>
        </div>
        <!-- statistic Content  -->

        <!-- summary data -->
        <div class='summary row row-cols-1 row-cols-md-5 g-4'>
          <div class="col">
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title">Total Customers</h5>
                <p class="card-text">
                  <?php echo $countCustomers;?>
                </p>
              </div>
            </div>
          </div>
          <div class='col'> 
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title">Total Revenue</h5>
                <p class="card-text">
                  <?php echo $revenue;?>
                </p>
              </div>
            </div>
          </div>
          <div class='col'>
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title">Total Orders</h5>
                <p class="card-text">
                  <?php echo $countOrders;?>
                </p>
              </div>
            </div>
          </div>
          <div class='col'>
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title">Avg Orders per cust</h5>
                <p class="card-text">
                  <?php echo round($countOrders/$countCustomers,2);?>
                </p>
              </div>
            </div>
          </div>
          <div class='col'>
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title">Avg Revenue per order</h5>
                <p class="card-text">
                  <?php echo round($revenue/$countOrders,2);?>
                </p>
              </div>
            </div>
          </div>
          <div class='col'>
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title">Avg Revenue per cust</h5>
                <p class="card-text">
                  <?php echo round($revenue/$countCustomers,2);?>
                </p>
              </div>
            </div>
          </div>
          <div class='col'>
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title">Highest order value</h5>
                <p class="card-text">
                  <?php echo $highestRevenue;?>
                </p>
              </div>
            </div>
          </div>
          <div class='col'>
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title">Lowest order value</h5>
                <p class="card-text">
                  <?php echo $lowestRevenue;?>
                </p>
              </div>
            </div>
          </div>
          <div class='col'>
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title">Avg item per order</h5>
                <p class="card-text">
                  <?php echo round($avgTotalItem,2);?>
                </p>
              </div>
            </div>
          </div>
          <div class='col'>
            <div class="card h-100">
              <div class="card-body">
                <h5 class="card-title">Avg uni-item per order</h5>
                <p class="card-text">
                  <?php echo round($avgDistItem,2);?>
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- overall graph -->
        <div class='row'>
          <div class="col-md-8">
            <div class="card h-100">
              <div class="card-body">
                <canvas id="overallChart"></canvas>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card h-100">
              <div class="card-body">
                <canvas id="categoryChart"></canvas>
              </div>
            </div>
          </div>
        </div>
        <!-- popular day and hour -->
        <div class='row'>
          <div class="col-md-5 popDay">
            <div class="card ">
              <div class="card-body">
                <canvas id="popDayChart"></canvas>
              </div>
            </div>
          </div>
          <div class="col-md-7">
            <div class="card h-100">
              <div class="card-body">
                <canvas id="popHourChart"></canvas>
              </div>
            </div>
          </div>
        </div>
        <!-- join day and total orders -->
        <div class='row'>
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-body">
                <canvas id="joinChart"></canvas>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-body">
                <canvas id="totalordChart"></canvas>
              </div>
            </div>
          </div>
        </div>

        <!-- most freq item with date -->
        <div class='row'>
          <div class='col-md-12'>
            <div class=" card h-100">
              <div class='row'>
                <div class="card-body col-md-2">
                  <div class="input-group mb-3 input-freq">
                    <div class="input-group-prepend">
                      <span class="input-group-text">Item</span>
                    </div>
                    <select class="form-control" id="itemName">
                      <?php
                        foreach ($itemGetAll as $key => $obj) {   
                          echo '<option value="'.$obj->getName().'">'.$obj->getName().'</option>';
                        }
                      ?>
                    </select>   
                  </div>
                  <div class="form-group">	
                    <!-- show most freq chart -->
                    <button type="button" class="btn btn-primary btn-block" onClick="updateFreqResultByItem()">Select</button>
                  </div>
                  </div>
                  <div class="card-body col-md-7">
                    <canvas id="mostFreqChart"></canvas>
                  </div>
                  <div class="card-body col-md-3" id="tableArea">
                    <!-- show most freq table -->

                  </div>
                </div>
              
            </div>
          </div>
          
        </div>

      
      </div>



    </div>
    <!-- /#page-content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Showing value in chartjs -->
  <script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script>

  <!-- Bootstrap core JavaScript -->
  <script src="../wwwroot/jquery/jquery.min.js"></script>
  <script src="../wwwroot/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Custom script for js and chart -->
  <script src="../wwwroot/js/OwnChart.js"></script>
  <script src="../wwwroot/js/index.js"></script>
  <script src="../wwwroot/js/summary.js"></script>

  <script>

    var overall_arr =  <?php echo json_encode($overall_arr, JSON_HEX_TAG); ?>;
    var category_arr =  <?php echo json_encode($category_arr, JSON_HEX_TAG); ?>;
    var popday_arr =  <?php echo json_encode($popday_arr, JSON_HEX_TAG); ?>;
    var pophour_arr =  <?php echo json_encode($pophour_arr, JSON_HEX_TAG); ?>;
    var joinday_arr =  <?php echo json_encode($joinday_arr, JSON_HEX_TAG); ?>;
    var totalord_arr =  <?php echo json_encode($totalord_arr, JSON_HEX_TAG); ?>;
    var items_arr =  <?php echo json_encode($itemGetAll, JSON_HEX_TAG); ?>;
    var item_Name = ""; //global variable
    console.log(JSON.stringify(totalord_arr));
     
    //declare overall chart
    let overallChart = document.getElementById('overallChart').getContext('2d');
    let oChart = new OwnChart(overallChart, 'bar', 1, false);
    let ocolors = [genColors(overall_arr)];
    oChart.setBorderColor(ocolors); 
    oChart.setBackgroundColor(ocolors); 
    oChart.setDatasetLabels(['item amounts']);
    oChart.setTitle('Most frequent bought items overall');
    oChart.setXAxis(getKeys(overall_arr, 'Name')); 
    oChart.setDisplayData(true);

    //declare category chart
    let categoryChart = document.getElementById('categoryChart').getContext('2d');
    let cChart = new OwnChart(categoryChart, 'pie', 1, false);
    let ccolors = [genColors(category_arr)];
    cChart.setBorderColor(ccolors); 
    cChart.setBackgroundColor(ccolors); 
    cChart.setDatasetLabels(['Category amounts']);
    cChart.setTitle('Most common categories statistic');
    cChart.setDisplayLegend(true);
    cChart.setLegendPos('right');
    cChart.setXAxis(getKeys(category_arr, 'Name'));

    //declare popular day chart
    let popDayChart = document.getElementById('popDayChart').getContext('2d');
    let pdchart = new OwnChart(popDayChart, 'line', 1, false);
    pdchart.setUnderneathFill(false);
    let pdcolors = [genColors(popday_arr)];
    pdchart.setBorderColor([randomColor()]); 
    pdchart.setBackgroundColor(pdcolors); 
    pdchart.setDatasetLabels(['Order amounts']);
    pdchart.setTitle('Most popular Days for orders');
    //1=Sunday, 2=Monday, 3=Tuesday, 4=Wednesday, 5=Thursday, 6=Friday, 7=Saturday.
    pdchart.setXAxis(['Sun','Mon','Tues','Wed','Thur','Fri','Sat']); 

    //declare popular hour chart
    let popHourChart = document.getElementById('popHourChart').getContext('2d');
    let phchart = new OwnChart(popHourChart, 'bar', 7, true);
    phchart.setLegendPos('right');
    let phcolors = [randomColor(), randomColor(), randomColor(), randomColor(),randomColor(), randomColor(), randomColor()];
    phchart.setBorderColor(phcolors); 
    phchart.setBackgroundColor(phcolors); 
    phchart.setDisplayLegend(true);
    phchart.setTitle('Most popular Hours by days for orders');
    let dsLabels2 = ['Sun orders','Mon orders','Tues orders','Wed orders','Thur orders','Fri orders','Sat orders'];
    phchart.setDatasetLabels(dsLabels2);
    phchart.setXAxis(['0:00', '1:00', '2:00', '3:00', '4:00', '5:00', '6:00', '7:00', '8:00', '9:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00']);

    //declare people first join day
    let joinChart = document.getElementById('joinChart').getContext('2d');
    let jChart = new OwnChart(joinChart, 'line');
    let jcolors = [genColors(joinday_arr)];
    jChart.setBorderColor([randomColor()]); 
    jChart.setBackgroundColor(jcolors); 
    jChart.setDatasetLabels(['Order amounts']);
    jChart.setTitle('People first day join');
    jChart.setXAxis(getKeys(joinday_arr, 'Df'));
    jChart.setUnderneathFill(false);

    //declare total orders
    let totalordChart = document.getElementById('totalordChart').getContext('2d');
    let ttChart = new OwnChart(totalordChart, 'bar', 2, true);
    let ttcolors = [randomColor(), randomColor()];
    ttChart.setBorderColor(ttcolors); 
    ttChart.setBackgroundColor(ttcolors); 
    ttChart.setTitle('Total Orders chart');
    let dsLabels4 = ['Existing Customers order', 'New Customers order'];
    ttChart.setDatasetLabels(dsLabels4);
    ttChart.setXAxis(getTotalOrderXaxis(totalord_arr));
    ttChart.setDisplayLegend(true);

    //declare most freq chart
    let mostFreqChart = document.getElementById('mostFreqChart').getContext('2d');
    let mfChart = new OwnChart(mostFreqChart, 'bar', 1, false);
    let mfcolors = [genColors(items_arr)];
    mfChart.setBorderColor(mfcolors); 
    mfChart.setBackgroundColor(mfcolors); 
    mfChart.setDatasetLabels(['item amounts']);
    mfChart.setDisplayData(true);
    

    $(window).on("load", function () {
        //set date as today
        setToday();
        //update chart when the date is load
        oChart.updatedChart([getKeys(overall_arr, 'Count')]);
        cChart.updatedChart([getKeys(category_arr, 'Count')]);
        pdchart.updatedChart([getKeys(popday_arr, 'sumItem')]);
        phchart.updatedChart(getHourValue(pophour_arr));
        jChart.updatedChart([getKeys(joinday_arr, 'Count')]);
        ttChart.updatedChart(getTotalOrder(totalord_arr));
    });

    //This function is for most freq items bought with 'this'
    function updateFreqResultByItem(){
      item_Name = $("#itemName").val();
      updateTotalCountByDate();
    }

    function updateMostFreqTable(arr){
      var sum = 0;
      arr.forEach(element => {
        sum += parseInt(element['Count']);
      });
      $("#tableArea").empty().append("<table id='t'><tr><th>Name</th><th>Percentage(%)</th></></table>");
      $("#t").each(function(){
          arr.forEach(element => {
            $(this).append("<tr><td>"+element['Name']+"</td><td>"+(element['Count']/sum * 100).toFixed(1)+"%</td></tr>");
          });
      });
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
        case 28:
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

      function filterOfDateTime(date_start, date_end){
        $.ajax({
          url : '../Controller/getUpdateForSummaryData.php',
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
            console.log(result);  					
            updateSummary(result); //update summary statistics
          }
        });

        $.ajax({
          url : '../Controller/getDataOutputForMostFreqItems2.php',
          data : {
              action : 'overall',
              start :	date_start,
              end :	date_end 
          },
          type : 'POST',
          dataType:"json",
          error : function(xhr) {
              alert('Ajax request error');
          },
          success : function(result) {		  		
            oChart.setXAxis(getKeys(result, 'Name')); 			     
            oChart.updatedChart([getKeys(result, 'Count')]);
          }
        });

        $.ajax({
          url : '../Controller/getCategoriesData.php',
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
              // console.log(result);
              cChart.updatedChart([getKeys(result, 'Count')]);
          }
        });
        $.ajax({
          url : '../Controller/getPopularOrders.php',
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
              pdchart.updatedChart([getKeys(result[0], 'sumItem')]);
              phchart.updatedChart(getHourValue(result[1]));
              jChart.updatedChart([getKeys(result[2], 'Count')]);
              jChart.setXAxis(getKeys(result[2], 'Df'));
              ttChart.setXAxis(getTotalOrderXaxis(result[3])); 
              ttChart.updatedChart(getTotalOrder(result[3]));
          }
        });
        
        $.ajax({
          url : '../Controller/getDataOutputForMostFreqItems2.php',
          data : {
              action : 'freqItem',
              itemName: item_Name,
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
            mfChart.setTitle('Most frequent items bought with '+item_Name);
            mfChart.setXAxis(getKeys(result, 'Name'));
            mfChart.updatedChart([getKeys(result, 'Count')]);
            updateMostFreqTable(result);
          }
        });
    }

    //For popular hour orders
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

  function getTotalOrderXaxis(arr){
            axis = [];
            arr.forEach(element => {
                if(element['FirstOrder'] == 0){
                    axis.push(element['Df']);
                }
            });
            return axis;
        }
        
        function getTotalOrder(arr){
            first = {};
            exist = {};
            arr.forEach(element => {
                if(element['FirstOrder'] == 0){
                    exist[element['Df']] = element['Count'];
                    first[element['Df']] = 0; //initial 0, some of df have in the exist but not in the first. 
                }else{
                    first[element['Df']] = element['Count'];
                }
            });
            //console.log([exist, first]);
            return [exist, first];
        }
      
  </script>

</body>

</html>
