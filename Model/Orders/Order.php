<?php   

    class Order {

        private $id;
        private $totalValue;
        private $totalItems;
        private $distinctItems;
        private $json;
        private $datetime;
        private $customerId;

        function __construct($id, $totalValue, $totalItems, $distinctItems, $json, $datetime, $customerId){
            $this->id = $id;
            $this->totalValue = $totalValue;
            $this->totalItems = $totalItems;
            $this->distinctItems = $distinctItems;
            $this->json = $json;
            $this->datetime = $datetime;
            $this->customerId = $customerId;
        }

        public function setId($id){
            $this->id = $id;
        }

        public function getId(){
            return $this->id;
        }

        public function setTotalValue($totalValue){
            $this->totalValue = $totalValue;
        }

        public function getTotalValue(){
            return $this->totalValue;
        }

        public function setTotalItems($totalItems){
            $this->totalItems = $totalItems;
        }

        public function getTotalItems(){
            return $this->totalItems;
        }

        public function setDistinctItems($distinctItems){
            $this->distinctItems = $distinctItems;
        }

        public function getDistinctItems(){
            return $this->distinctItems;
        }

        public function setJson($json){
            $this->json = $json;
        }

        public function getJson(){
            return $this->json;
        }

        public function setDateTime($datetime){
            $this->datetime = $datetime;
        }

        public function getDateTime(){
            return $this->datetime;
        }

        public function setCustomerId($customerId){
            $this->customerId = $customerId;
        }

        public function getCustomerId(){
            return $this->customerId;
        }
    }
?>