<?php
    include('CustomerDAO_interface.php');
    class CustomerDAO implements CustomerDAO_interface{

        private $insertSQL = "INSERT INTO customers (PhoneNumber, Email, Name, TotalOrders, TotalValue, FirstOrderDateTime, MostRecentOrderDateTime) VALUES (?,?,?,?,?,?,?)";
        private $findpkSQL = 'SELECT * FROM customers WHERE ID = ?';
        private $getAllSQL = 'SELECT * FROM customers';

        public function insert(Customer $customer){
            global $conn;
            $stmt = $conn->prepare($this->insertSQL);
            $phone = $customer->getPhoneNum();
            $email = $customer->getEmail();
            $name = $customer->getName();
            $orders =$customer->getTotalOrders();
            $values = $customer->getTotalValue();
            $first = $customer->getFirstOrderDateTime();
            $recent = $customer->getMostRecentOrderDateTime();
			$stmt->bind_param('sssidss', $phone, $email, $name, $orders, $values, $first, $recent);
			
            if($stmt->execute()){
                $last_id = $conn->insert_id;
                echo "New customer created successfully. Last inserted ID is: " . $last_id."<br>";
            }
            
			$stmt->close();
			//$conn->close();
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
            return new Customer($arr['ID'], $arr['PhoneNumber'], $arr['Email'], $arr['Name'], $arr['TotalOrders'], $arr['TotalValue'], $arr['FirstOrderDateTime'], $arr['MostRecentOrderDateTime']);

        }

        public function getAll(){
            global $conn;
			
			$list = array();
			$stmt = $conn->prepare($this->getAllSQL);

			if($stmt->execute()){
				$result = $stmt->get_result();

				while($arr = $result->fetch_assoc()){
					$list[] = new Customer($arr['ID'], $arr['PhoneNumber'], $arr['Email'], $arr['Name'], $arr['TotalOrders'], $arr['TotalValue'], $arr['FirstOrderDateTime'], $arr['MostRecentOrderDateTime']); 
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
            $searchSQL ='SELECT * FROM customers WHERE '.$attribute.' LIKE ?';
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
					$list[] = new Customer($arr['ID'], $arr['PhoneNumber'], $arr['Email'], $arr['Name'], $arr['TotalOrders'], $arr['TotalValue'], $arr['FirstOrderDateTime'], $arr['MostRecentOrderDateTime']); 
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