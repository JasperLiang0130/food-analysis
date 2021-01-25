<?php
    include("CategoryDAO_interface.php");
    class CategoryDAO implements CategoryDAO_interface{

        private $findpkSQL = 'SELECT * FROM categories WHERE ID = ?';
        private $getAllSQL = 'SELECT * FROM categories';
    
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
            return new Category($arr['ID'], $arr['Name']);

        }

        public function getAll(){
            global $conn;
			// $conn = $this->connect();
			$list_categories = array();
			$stmt = $conn->prepare($this->getAllSQL);

			if($stmt->execute()){
				$result = $stmt->get_result();

				while($arr = $result->fetch_assoc()){
					$list_categories[] = new Category($arr['ID'], $arr['Name']); 
				}
			}else{
				echo $stmt->error;
			}

			$stmt->close();
			// $conn->close();

			return $list_categories; //return multi-object e.g. array(object,object......)
        }

        public function query($keyword, $attribute){
            global $conn;
            // $conn = $this->connect();
            $searchSQL ='SELECT * FROM categories WHERE '.$attribute.' LIKE ?';
            //echo $searchSQL;
            $keyword = htmlspecialchars($keyword); //change characters in html. e.g. < is changed to &lt;
			$keyword = $conn->real_escape_string($keyword); //make sure no SQL injection
            $keyword = "%$keyword%"; // The ? must be the entire string or integer literal value 
            
            $list_categories = array();
			$stmt = $conn->prepare($searchSQL);
            $stmt->bind_param('s', $keyword);
            
            if($stmt->execute()){
				$result = $stmt->get_result();

				while($arr = $result->fetch_assoc()){
					$list_categories[] = new Category($arr['ID'], $arr['Name']);
				}
				
			}else{
				echo $stmt->error;
			}

            $stmt->close();
            // $conn->close();

            return $list_categories;  
        }
    }


?>