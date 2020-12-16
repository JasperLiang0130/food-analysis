<?php  

    include('OptionSetDAO_interface.php');
    include('OptionSet.php');
    class OptionSetDAO implements OptionSetDAO_interface{

        private $findpkSQL = 'SELECT * FROM option_sets WHERE ID = ?';
        private $getAllSQL = 'SELECT * FROM option_sets';

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
            $conn->close();
            return new OptionSet($arr['ID'], $arr['Name'], $arr['MultipleOptions'], $arr['ItemID']);

        }

        public function getAll(){
            global $conn;
			
			$list = array();
			$stmt = $conn->prepare($this->getAllSQL);

			if($stmt->execute()){
				$result = $stmt->get_result();

				while($arr = $result->fetch_assoc()){
					$list[] = new OptionSet($arr['ID'], $arr['Name'], $arr['MultipleOptions'], $arr['ItemID']); 
				}
			}else{
				echo $stmt->error;
			}

			$stmt->close();
			$conn->close();

			return $list; //return multi-object e.g. array(object,object......)
        }

        public function query($keyword, $attribute){
            global $conn;
            $searchSQL ='SELECT * FROM option_sets WHERE '.$attribute.' LIKE ?';
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
					$list[] = new OptionSet($arr['ID'], $arr['Name'], $arr['MultipleOptions'], $arr['ItemID']); 
				}
				
			}else{
				echo $stmt->error;
			}

            $stmt->close();
            $conn->close();

            return $list; 
        }

    }
?>