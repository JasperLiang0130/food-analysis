<?php
    include("ItemDAO_interface.php");
    class ItemDAO implements ItemDAO_interface{

        private $findpkSQL = 'SELECT * FROM items WHERE ID = ?';
        private $getAllSQL = 'SELECT * FROM items';

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
            return new Item($arr['ID'], $arr['Name'], $arr['Description'], $arr['BasePrice'], $arr['CategoryID']);

        }

        public function getAll(){
            global $conn;
			// $conn = $this->connect();
			$list = array();
			$stmt = $conn->prepare($this->getAllSQL);

			if($stmt->execute()){
				$result = $stmt->get_result();

				while($arr = $result->fetch_assoc()){
					$list[] = new Item($arr['ID'], $arr['Name'], $arr['Description'], $arr['BasePrice'], $arr['CategoryID']); 
				}
			}else{
				echo $stmt->error;
			}

			$stmt->close();
			// $conn->close();

			return $list; //return multi-object e.g. array(object,object......)
        }

        public function query($keyword, $attribute){
            global $conn;
            // $conn = $this->connect();
            $searchSQL ='SELECT * FROM items WHERE '.$attribute.' LIKE ?';
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
					$list[] = new Item($arr['ID'], $arr['Name'], $arr['Description'], $arr['BasePrice'], $arr['CategoryID']); 
				}
				
			}else{
				echo $stmt->error;
			}

            $stmt->close();
            // $conn->close();

            return $list; 
        }
    }

?>