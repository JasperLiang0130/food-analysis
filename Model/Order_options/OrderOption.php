<?php

    class OrderOption {

        private $id;
        private $value;
        private $orderItemId;
        private $optionId;
        private $optionSetId;

        function __construct($id, $value, $orderItemId, $optionId, $optionSetId){
            $this->id = $id;
            $this->value = $value;
            $this->orderItemId = $orderItemId;
            $this->optionId = $optionId;
            $this->optionSetId = $optionSetId;
        }

        public function setId($id){
            $this->id = $id;
        }

        public function getId(){
            return $this->id;
        }

        public function setValue($value){
            $this->value = $value;
        }

        public function getValue(){
            return $this->value;
        }

        public function setOrderItemId($orderItemId){
            $this->orderItemId = $orderItemId;
        }

        public function getOrderItemId(){
            return $this->orderItemId;
        }

        public function setOptionId($optionId){
            $this->optionId = $optionId;
        }

        public function getOptionId(){
            return $this->optionId;
        }

        public function setOptionSetId($optionSetId){
            $this->optionSetId = $optionSetId;
        }

        public function getOptionSetId(){
            return $this->optionSetId;
        }
    }
?>