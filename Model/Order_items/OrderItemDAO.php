<?php

    include("OrderItemDAO_interface.php");
    class OrderItemDAO implements OrderItemDAO_interface{

        private $insertSQL = "INSERT INTO order_items (Quantity, SingleValue, TotalValue, ItemID, OrderID) VALUES (?,?,?,?,?)";
        private $findpkSQL = 'SELECT * FROM order_items WHERE ID = ?';
        private $getAllSQL = 'SELECT * FROM order_items';
        private $querySQL = 'SELECT food.items.Name, food.items.ID, food.order_items.OrderID, food.orders.DateTime FROM food.order_items INNER JOIN food.items ON food.order_items.ItemID = food.items.ID INNER JOIN food.orders ON food.order_items.OrderID = food.orders.ID WHERE (food.order_items.OrderID in (SELECT food.order_items.OrderID FROM food.order_items INNER JOIN food.items ON food.order_items.ItemID = food.items.ID WHERE food.items.Name = ? )) Order by DateTime ASC';
        private $getAllIncNameSQL ='SELECT food.items.Name, food.items.ID, food.order_items.OrderID, food.orders.DateTime FROM food.order_items INNER JOIN food.items ON food.order_items.ItemID = food.items.ID INNER JOIN food.orders ON food.order_items.OrderID = food.orders.ID WHERE food.orders.DateTime > ? AND food.orders.DateTime <= ?';
        private $getAllIncNameSQL2 ='SELECT food.items.Name, Count(food.items.ID) as Count FROM food.order_items INNER JOIN food.items ON food.order_items.ItemID = food.items.ID INNER JOIN food.orders ON food.order_items.OrderID = food.orders.ID WHERE food.orders.DateTime > ? AND food.orders.DateTime <= ? group by food.items.Name order by Count desc';
        private $getAllFilterDateSQL ='SELECT food.items.Name, food.items.ID, food.order_items.OrderID, food.orders.DateTime FROM food.order_items INNER JOIN food.items ON food.order_items.ItemID = food.items.ID INNER JOIN food.orders ON food.order_items.OrderID = food.orders.ID WHERE (food.order_items.OrderID in (SELECT food.order_items.OrderID FROM food.order_items INNER JOIN food.items ON food.order_items.ItemID = food.items.ID WHERE food.items.Name = ? )) AND food.orders.DateTime > ? AND food.orders.DateTime <= ? Order by DateTime ASC';
        private $getAllFilterDateSQL2 = 'SELECT food.items.Name, Count(food.items.Name) as Count FROM food.order_items INNER JOIN food.items ON food.order_items.ItemID = food.items.ID INNER JOIN food.orders ON food.order_items.OrderID = food.orders.ID WHERE (food.order_items.OrderID in (SELECT food.order_items.OrderID FROM food.order_items INNER JOIN food.items ON food.order_items.ItemID = food.items.ID WHERE food.items.Name = ? )) AND food.items.Name != ? AND orders.DateTime > ? AND orders.DateTime <= ? group by items.Name Order by count desc';
        private $getCategoriesCount = 'SELECT categories.Name, COUNT(order_items.ID) as Count FROM food.order_items INNER JOIN food.items ON food.order_items.ItemID = food.items.ID INNER JOIN food.orders ON food.order_items.OrderID = food.orders.ID INNER JOIN food.categories ON food.items.CategoryID = food.categories.ID WHERE orders.DateTime > ? AND orders.DateTime <= ? group by categories.Name order by Count DESC';

        public function insert(OrderItem $orderItem){
            global $conn;
            // $conn = $this->connect();
            $stmt = $conn->prepare($this->insertSQL);
            $quantity = $orderItem->getQuantity();
            $singlevalue = $orderItem->getSingleValue();
            $totalvalue = $orderItem->getTotalValue();
            $itemid =$orderItem->getItemId();
            $orderid = $orderItem->getOrderId();
			$stmt->bind_param('iddii', $quantity, $singlevalue, $totalvalue, $itemid, $orderid);
			
            if($stmt->execute()){
                $last_id = $conn->insert_id;
                echo "New order_item created successfully. Last inserted ID is: " . $last_id."<br>";
            }
            $orderItem->setId($last_id);
			$stmt->close();
            // $conn->close();
            return $orderItem;
        }

        public function findOnePK($id){
            global $conn;
            // $conn = $this->connect();
            $stmt = $conn->prepare($this->findpkSQL);
            $stmt->bind_param('i', $id);
            if($stmt->execute()){
                $result = $stmt->get_result();
                $arr = $result->fetch_assoc();
            }else{
                echo $stmt->error;
            }
            $stmt->close();
            // $conn->close();
            return new OrderItem($arr['ID'], $arr['Quantity'], $arr['SingleValue'], $arr['TotalValue'], $arr['ItemID'], $arr['OrderID']);

        }

        public function getAll(){
            global $conn;
			// $conn = $this->connect();
			$list = array();
			$stmt = $conn->prepare($this->getAllSQL);

			if($stmt->execute()){
				$result = $stmt->get_result();

				while($arr = $result->fetch_assoc()){
					$list[] = new OrderItem($arr['ID'], $arr['Quantity'], $arr['SingleValue'], $arr['TotalValue'], $arr['ItemID'], $arr['OrderID']); 
				}
			}else{
				echo $stmt->error;
			}

			$stmt->close();
			// $conn->close();

			return $list; //return multi-object e.g. array(object,object......)
        }

        public function search($keyword, $attribute){
            global $conn;
            // $conn = $this->connect();
            $searchSQL ='SELECT * FROM order_items WHERE '.$attribute.' LIKE ?';
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
					$list[] = new OrderItem($arr['ID'], $arr['Quantity'], $arr['SingleValue'], $arr['TotalValue'], $arr['ItemID'], $arr['OrderID']); 
				}
				
			}else{
				echo $stmt->error;
			}

            $stmt->close();
            // $conn->close();

            return $list; 
        }

        public function query($keyword){
            global $conn;
            // $conn = $this->connect();
            $keyword = htmlspecialchars($keyword); //change characters in html. e.g. < is changed to &lt;
			$keyword = $conn->real_escape_string($keyword); //make sure no SQL injection
            
            $items = array();
			$stmt = $conn->prepare($this->querySQL);
            $stmt->bind_param('s', $keyword);
            
            if($stmt->execute()){
				$result = $stmt->get_result();

				while($arr = $result->fetch_assoc()){
					$items[] = $arr;
				}
				
			}else{
				echo $stmt->error;
			}

            $stmt->close();
            // $conn->close();

            return $items; 
        }

        public function getAllIncName($start, $end){
            global $conn;
            // $conn = $this->connect();
            $items = array();
			$stmt = $conn->prepare($this->getAllIncNameSQL);
            $stmt->bind_param('ss', $start, $end);
            if($stmt->execute()){
				$result = $stmt->get_result();

				while($arr = $result->fetch_assoc()){
					$items[] = $arr;
				}
				
			}else{
				echo $stmt->error;
			}

            $stmt->close();
            // $conn->close();

            return $items; 
        }

        public function getAllIncName2($start, $end){
            global $conn;
            // $conn = $this->connect();
            $items = array();
			$stmt = $conn->prepare($this->getAllIncNameSQL2);
            $stmt->bind_param('ss', $start, $end);
            if($stmt->execute()){
				$result = $stmt->get_result();

				while($arr = $result->fetch_assoc()){
					$items[] = $arr;
				}
				
			}else{
				echo $stmt->error;
			}

            $stmt->close();
            // $conn->close();

            return $items; 
        }

        public function queryByDate($itemName, $start, $end){
            global $conn;
            // $conn = $this->connect();
            $items = array();
			$stmt = $conn->prepare($this->getAllFilterDateSQL);
            $stmt->bind_param('sss', $itemName, $start, $end);
            
            if($stmt->execute()){
				$result = $stmt->get_result();

				while($arr = $result->fetch_assoc()){
					$items[] = $arr;
				}
				
			}else{
				echo $stmt->error;
			}

            $stmt->close();
            // $conn->close();

            return $items; 
        }

        public function queryByDate2($itemName, $start, $end){
            global $conn;
            // $conn = $this->connect();
            $items = array();
			$stmt = $conn->prepare($this->getAllFilterDateSQL2);
            $stmt->bind_param('ssss', $itemName, $itemName, $start, $end);
            
            if($stmt->execute()){
				$result = $stmt->get_result();

				while($arr = $result->fetch_assoc()){
					$items[] = $arr;
				}
				
			}else{
				echo $stmt->error;
			}

            $stmt->close();
            // $conn->close();

            return $items; 
        }

        public function getAllCategoryCount($start, $end){
            global $conn;
            // $conn = $this->connect();
            $categories = array();
			$stmt = $conn->prepare($this->getCategoriesCount);
            $stmt->bind_param('ss', $start, $end);
            if($stmt->execute()){
				$result = $stmt->get_result();

				while($arr = $result->fetch_assoc()){
					$categories[] = $arr;
				}
				
			}else{
				echo $stmt->error;
			}

            $stmt->close();
            // $conn->close();

            return $categories; 
        }

    }
?>