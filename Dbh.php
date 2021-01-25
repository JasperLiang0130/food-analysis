<?php

    class Dbh{
        //$conn = new mysqli('localhost', 'root', 'Strawberry13579', 'food');
        private $host = 'localhost';
        private $user = 'root';
        private $pwd = 'Strawberry13579';
        private $dbName = 'food';
        private $conn;

        public function getConnection(){
            $this->conn = new mysqli($this->host, $this->user, $this->pwd, $this->dbName);

            if (mysqli_connect_errno()) {
                printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
            }
            // else {
            // 	printf("Connect successful!<br>");
            // }

            return $this->conn;
        }

        public function begin_transaction(){
            $this->conn->begin_transaction();
        }

        public function commit(){
            $this->conn->commit();
        }

        public function close(){
            $this->conn->close();
        }


    }

?>