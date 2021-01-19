<?php

    include("OrderDAO_interface.php");
    class OrderDAO implements OrderDAO_interface{

        private $insertSQL = "INSERT INTO orders (TotalValue, TotalItems, DistinctItems, JSON, DateTime, FirstOrder, CustomerID) VALUES (?,?,?,?,?,?,?)";
        private $updateSQL ='UPDATE orders SET TotalValue = ?, TotalItems = ?, DistinctItems = ?, JSON = ?, DateTime = ?, FirstOrder = ?, CustomerID = ? WHERE ID = ?';
        private $findpkSQL = 'SELECT * FROM orders WHERE ID = ?';
        private $getAllSQL = 'SELECT * FROM orders';
        private $getTotalRevenueSQL = 'SELECT SUM(TotalValue) as revenue FROM orders WHERE orders.DateTime > ? AND orders.DateTime <= ?';
        private $getTotalCountOrdSQL = 'SELECT COUNT(ID) as orders FROM orders WHERE orders.DateTime > ? AND orders.DateTime <= ?';
        private $getMaxOrdValueSQL = 'SELECT MAX(TotalValue) as max FROM orders WHERE orders.DateTime > ? AND orders.DateTime <= ?';
        private $getMinOrdValueSQL = 'SELECT MIN(TotalValue) as min FROM orders WHERE orders.DateTime > ? AND orders.DateTime <= ?';
        private $getAvgTotalItemSQL = 'SELECT AVG(TotalItems) as avgItem FROM orders WHERE orders.DateTime > ? AND orders.DateTime <= ?';
        private $getAvgDistinctItemSQL = 'SELECT AVG(DistinctItems) as avgDItem FROM orders WHERE orders.DateTime > ? AND orders.DateTime <= ?';
        private $getPopDaysSQL = 'SELECT SUM(orders.TotalItems) as sumItem, DAYOFWEEK(orders.DateTime) as Day FROM food.orders where orders.DateTime > ? AND orders.DateTime <= ? group by Day order by Day';
        private $getPopHoursSQL = 'SELECT SUM(orders.TotalItems) as sumItem, DAYOFWEEK(orders.DateTime) as Day , HOUR(orders.DateTime) as Hour FROM food.orders where orders.DateTime > ? AND orders.DateTime <= ? group by Day, Hour order by Day, Hour';
        private $getTotalOrdersSQL = 'SELECT SUM(orders.TotalItems) as sumItem, DAYOFWEEK(orders.DateTime) as Day, orders.FirstOrder FROM food.orders where orders.DateTime > ? AND orders.DateTime <= ? group by Day, orders.FirstOrder order by orders.FirstOrder, Day';


        public function insert(Order $order){
            global $conn;
            $stmt = $conn->prepare($this->insertSQL);
            $values = $order->getTotalValue();
            $items = $order->getTotalItems();
            $ditems = $order->getDistinctItems();
            $json = $order->getJson();
            $dt =$order->getDateTime();
            $fo =$order->getFirstOrder();
            $customerId = $order->getCustomerId();
			$stmt->bind_param('diissii', $values, $items, $ditems, $json, $dt, $fo, $customerId);
			
            if($stmt->execute()){
                $last_id = $conn->insert_id;
                echo "New order created successfully. Last inserted ID is: " . $last_id."<br>";
            }

            $order->setId($last_id);
			$stmt->close();
            //$conn->close();
            return $order; // return complete order include id
        }

        public function update(Order $order){
            global $conn;
            $stmt = $conn->prepare($this->updateSQL);
            $values = $order->getTotalValue();
            $items = $order->getTotalItems();
            $ditems = $order->getDistinctItems();
            $json = $order->getJson();
            $dt =$order->getDateTime();
            $fo =$order->getFirstOrder();
            $customerId = $order->getCustomerId();
            $id = $order->getId();
			$stmt->bind_param('diissiii', $values, $items, $ditems, $json, $dt, $fo, $customerId, $id);
			
            if($stmt->execute()){
                //echo "OrderID: ". $id ." has been updated successfully. <br>";
            }

			$stmt->close();
            //$conn->close();
            return $order; // return complete order include id
        }

        public function findOnePK($id){
            global $conn;
            
            $stmt = $conn->prepare($this->findpkSQL);
            $stmt->bind_param('i', $id);
            if($stmt->execute()){
                $result = $stmt->get_result();
                $arr = $result->fetch_assoc();
            }else{
                echo $stmt->error;
            }
            $stmt->close();
            //$conn->close();
            return new Order($arr['ID'], $arr['TotalValue'], $arr['TotalItems'], $arr['DistinctItems'], $arr['JSON'], $arr['DateTime'], $arr['FirstOrder'], $arr['CustomerID']);

        }

        public function getAll(){
            global $conn;
			
			$list = array();
			$stmt = $conn->prepare($this->getAllSQL);

			if($stmt->execute()){
				$result = $stmt->get_result();

				while($arr = $result->fetch_assoc()){
					$list[] = new Order($arr['ID'], $arr['TotalValue'], $arr['TotalItems'], $arr['DistinctItems'], $arr['JSON'], $arr['DateTime'], $arr['FirstOrder'], $arr['CustomerID']); 
				}
			}else{
				echo $stmt->error;
			}

			$stmt->close();
			//$conn->close();

			return $list; //return multi-object e.g. array(object,object......)
        }

        public function query($keyword, $attribute){
            global $conn;
            $searchSQL ='SELECT * FROM orders WHERE '.$attribute.' LIKE ?';
            //echo $searchSQL;
            $keyword = htmlspecialchars($keyword); //change characters in html. e.g. < is changed to &lt;
			$keyword = $conn->real_escape_string($keyword); //make sure no SQL injection
            $keyword = "%$keyword%"; // The ? must be the entire string or integer literal value 
            
            $list = array();
			$stmt = $conn->prepare($searchSQL);
            $stmt->bind_param('s', $keyword);
            
            if($stmt->execute()){
				$result = $stmt->get_result();

				while($arr = $result->fetch_assoc()){
					$list[] = new Order($arr['ID'], $arr['TotalValue'], $arr['TotalItems'], $arr['DistinctItems'], $arr['JSON'], $arr['DateTime'], $arr['FirstOrder'], $arr['CustomerID']); 
				}
				
			}else{
				echo $stmt->error;
			}

            $stmt->close();
            //$conn->close();

            return $list; 
        }

        public function getTotalRevenue($start, $end){
            
            global $conn;
            $totalRev = null;
            $stmt = $conn->prepare($this->getTotalRevenueSQL);
            $stmt->bind_param('ss', $start,$end);
            if($stmt->execute()){
                $result = $stmt->get_result();
                $totalRev = $result->fetch_assoc();
            }else{
                echo $stmt->error;
            }
            $stmt->close();
            //$conn->close();
            return $totalRev['revenue'];

        }

        public function getTotalCountOrd($start, $end){

            global $conn;
            $totalCount = null;
            $stmt = $conn->prepare($this->getTotalCountOrdSQL);
            $stmt->bind_param('ss', $start,$end);
            if($stmt->execute()){
                $result = $stmt->get_result();
                $totalCount = $result->fetch_assoc();
            }else{
                echo $stmt->error;
            }
            $stmt->close();
            //$conn->close();
            return $totalCount['orders'];

        }

        public function getHighestOrderValue($start, $end){

            global $conn;
            $max = null;
            $stmt = $conn->prepare($this->getMaxOrdValueSQL);
            $stmt->bind_param('ss', $start,$end);
            if($stmt->execute()){
                $result = $stmt->get_result();
                $max = $result->fetch_assoc();
            }else{
                echo $stmt->error;
            }
            $stmt->close();
            //$conn->close();
            return $max['max'];

        }
        public function getLowestOrderValue($start, $end){

            global $conn;
            $min = null;
            $stmt = $conn->prepare($this->getMinOrdValueSQL);
            $stmt->bind_param('ss', $start,$end);
            if($stmt->execute()){
                $result = $stmt->get_result();
                $min = $result->fetch_assoc();
            }else{
                echo $stmt->error;
            }
            $stmt->close();
            //$conn->close();
            return $min['min'];

        }

        public function getAvgTotalItems($start, $end){
            global $conn;
            $avg = null;
            $stmt = $conn->prepare($this->getAvgTotalItemSQL);
            $stmt->bind_param('ss', $start,$end);
            if($stmt->execute()){
                $result = $stmt->get_result();
                $avg = $result->fetch_assoc();
            }else{
                echo $stmt->error;
            }
            $stmt->close();
            //$conn->close();
            return $avg['avgItem'];
        }
        public function getAvgDistinctItems($start, $end){
            global $conn;
            $avg = null;
            $stmt = $conn->prepare($this->getAvgDistinctItemSQL);
            $stmt->bind_param('ss', $start,$end);
            if($stmt->execute()){
                $result = $stmt->get_result();
                $avg = $result->fetch_assoc();
            }else{
                echo $stmt->error;
            }
            $stmt->close();
            //$conn->close();
            return $avg['avgDItem'];
        }

        public function getPopularDays($start, $end){
            global $conn;
            $orders = array();
			$stmt = $conn->prepare($this->getPopDaysSQL);
            $stmt->bind_param('ss', $start, $end);
            if($stmt->execute()){
				$result = $stmt->get_result();

				while($arr = $result->fetch_assoc()){
					$orders[] = $arr;
				}
				
			}else{
				echo $stmt->error;
			}

            $stmt->close();
            //$conn->close();

            return $orders; 
        }

        public function getPopularHoursByDay($start, $end){
            global $conn;
            $orders = array();
			$stmt = $conn->prepare($this->getPopHoursSQL);
            $stmt->bind_param('ss', $start, $end);
            if($stmt->execute()){
				$result = $stmt->get_result();

				while($arr = $result->fetch_assoc()){
					$orders[] = $arr;
				}
				
			}else{
				echo $stmt->error;
			}

            $stmt->close();
            //$conn->close();

            return $orders; 
        }

        public function getTotalOrders($start, $end){
            global $conn;
            $orders = array();
			$stmt = $conn->prepare($this->getTotalOrdersSQL);
            $stmt->bind_param('ss', $start, $end);
            if($stmt->execute()){
				$result = $stmt->get_result();

				while($arr = $result->fetch_assoc()){
					$orders[] = $arr;
				}
				
			}else{
				echo $stmt->error;
			}

            $stmt->close();
            //$conn->close();

            return $orders; 
        }

    }
?>