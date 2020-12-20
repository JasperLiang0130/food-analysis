<?php

    class OrderItem {

        private $id;
        private $quantity;
        private $singleValue;
        private $totalValue;
        private $itemId;
        private $orderId;

        function __construct($id, $quantity, $singleValue, $totalValue, $itemId, $orderId){
            $this->id = $id;
            $this->quantity = $quantity;
            $this->singleValue = $singleValue;
            $this->totalValue = $totalValue;
            $this->itemId = $itemId;
            $this->orderId = $orderId;
        }

        public function setId($id){
             $this->id = $id;
        }

        public function getId(){
            return  $this->id;
        }

        public function setQuantity($quantity){
            $this->quantity = $quantity;
        }

        public function getQuantity(){
            return $this->quantity;
        }

        public function setSingleValue($singleValue){
            $this->singleValue = $singleValue;
        }

        public function getSingleValue(){
            return $this->singleValue;
        }

        public function setTotalValue($totalValue){
            $this->totalValue = $totalValue;
        }

        public function getTotalValue(){
            return $this->totalValue;
        }

        public function setItemId($itemId){
            $this->itemId = $itemId;
        }

        public function getItemId(){
            return $this->itemId;
        }

        public function setOrderId($orderId){
            $this->orderId = $orderId;
        }

        public function getOrderId(){
            return $this->orderId;
        }
    }
?>
