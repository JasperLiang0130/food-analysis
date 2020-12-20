<?php

    include("OrderItemDAO_interface.php");
    class OrderItemDAO implements OrderItemDAO_interface{

        private $insertSQL = "INSERT INTO order_items (Quantity, SingleValue, TotalValue, ItemID, OrderID) VALUES (?,?,?,?,?)";
        private $findpkSQL = 'SELECT * FROM order_items WHERE ID = ?';
        private $getAllSQL = 'SELECT * FROM order_items';

        public function insert(OrderItem $orderItem){
            global $conn;
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
            //$conn->close();
            return $orderItem;
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
            return new OrderItem($arr['ID'], $arr['Quantity'], $arr['SingleValue'], $arr['TotalValue'], $arr['ItemID'], $arr['OrderID']);

        }

        public function getAll(){
            global $conn;
			
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
			//$conn->close();

			return $list; //return multi-object e.g. array(object,object......)
        }

        public function query($keyword, $attribute){
            global $conn;
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
            //$conn->close();

            return $list; 
        }

    }
?>