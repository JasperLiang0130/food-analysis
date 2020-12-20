<?php

    include("OrderDAO_interface.php");
    class OrderDAO implements OrderDAO_interface{

        private $insertSQL = "INSERT INTO orders (TotalValue, TotalItems, DistinctItems, JSON, DateTime, CustomerID) VALUES (?,?,?,?,?,?)";
        private $updateSQL ='UPDATE orders SET TotalValue = ?, TotalItems = ?, DistinctItems = ?, JSON = ?, DateTime = ?, CustomerID = ? WHERE ID = ?';
        private $findpkSQL = 'SELECT * FROM orders WHERE ID = ?';
        private $getAllSQL = 'SELECT * FROM orders';

        public function insert(Order $order){
            global $conn;
            $stmt = $conn->prepare($this->insertSQL);
            $values = $order->getTotalValue();
            $items = $order->getTotalItems();
            $ditems = $order->getDistinctItems();
            $json = $order->getJson();
            $dt =$order->getDateTime();
            $customerId = $order->getCustomerId();
			$stmt->bind_param('diissi', $values, $items, $ditems, $json, $dt, $customerId);
			
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
            $customerId = $order->getCustomerId();
            $id = $order->getId();
			$stmt->bind_param('diissii', $values, $items, $ditems, $json, $dt, $customerId, $id);
			
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
            return new Order($arr['ID'], $arr['TotalValue'], $arr['TotalItems'], $arr['DistinctItems'], $arr['JSON'], $arr['DateTime'], $arr['CustomerID']);

        }

        public function getAll(){
            global $conn;
			
			$list = array();
			$stmt = $conn->prepare($this->getAllSQL);

			if($stmt->execute()){
				$result = $stmt->get_result();

				while($arr = $result->fetch_assoc()){
					$list[] = new Order($arr['ID'], $arr['TotalValue'], $arr['TotalItems'], $arr['DistinctItems'], $arr['JSON'], $arr['DateTime'], $arr['CustomerID']); 
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
					$list[] = new Order($arr['ID'], $arr['TotalValue'], $arr['TotalItems'], $arr['DistinctItems'], $arr['JSON'], $arr['DateTime'], $arr['CustomerID']); 
				}
				
			}else{
				echo $stmt->error;
			}

            $stmt->close();
            //$conn->close();

            return $list; 
        }

    }
?>