<?php

    include("OrderOptionDAO_interface.php");
    class OrderOptionDAO implements OrderOptionDAO_interface {

        private $insertSQL = "INSERT INTO order_options (Value, OrderItemID, OptionID, OptionSetID) VALUES (?,?,?,?)";
        private $findpkSQL = 'SELECT * FROM order_options WHERE ID = ?';
        private $getAllSQL = 'SELECT * FROM order_options';

        public function insert(OrderOption $orderOption){
            global $conn;
            $stmt = $conn->prepare($this->insertSQL);
            $value = $orderOption->getValue();
            $orderItemId = $orderOption->getOrderItemId();
            $optionId = $orderOption->getOptionId();
            $optionSetId =$orderOption->getOptionSetId();
			$stmt->bind_param('diii', $value, $orderItemId, $optionId, $optionSetId);
			
            if($stmt->execute()){
                $last_id = $conn->insert_id;
                echo "New order_option created successfully. Last inserted ID is: " . $last_id."<br>";
            }
            $orderOption->setId($last_id);
			$stmt->close();
            //$conn->close();
            return $orderOption;
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
            return new OrderOption($arr['ID'], $arr['Value'], $arr['OrderItemID'], $arr['OptionID'], $arr['OptionSetID']);

        }

        public function getAll(){
            global $conn;
			
			$list = array();
			$stmt = $conn->prepare($this->getAllSQL);

			if($stmt->execute()){
				$result = $stmt->get_result();

				while($arr = $result->fetch_assoc()){
					$list[] = new OrderOption($arr['ID'], $arr['Value'], $arr['OrderItemID'], $arr['OptionID'], $arr['OptionSetID']); 
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
            $searchSQL ='SELECT * FROM order_options WHERE '.$attribute.' LIKE ?';
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
					$list[] = new OrderOption($arr['ID'], $arr['Value'], $arr['OrderItemID'], $arr['OptionID'], $arr['OptionSetID']); 
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