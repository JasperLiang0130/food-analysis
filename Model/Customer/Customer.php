<?php

    class Customer 
    {
        private $id;
        private $name;
        private $phoneNum;
        private $email;
        private $totalOrders;
        private $totalValue;
        private $firstOrderDateTime;
        private $mostRecentOrderDateTime;

        function __construct($id, $name, $phoneNum, $email, $totalOrders, $totalValue, $firstOrderDateTime, $mostRecentOrderDateTime){
            $this->id = $id;
            $this->name = $name;
            $this->phoneNum = $phoneNum;
            $this->email = $email;
            $this->totalOrders = $totalOrders;
            $this->totalValue = $totalValue;
            $this->firstOrderDateTime = $firstOrderDateTime;
            $this->mostRecentOrderDateTime = $mostRecentOrderDateTime;
        }
        
        function setCustomerId($id){
            $this->id = $id;
        }

        function getCustomerId(){
            return $this->id;
        }

        function setName($name){
            $this->name = $name;
        }

        function getName(){
            return $this->name;
        }
        
        function setPhoneNum($phoneNum){
            $this->phoneNum = $phoneNum;
        }

        function getPhoneNum(){
            return $this->phoneNum; 
        }

        function setEmail($email){
            $this->email = $email;
        }

        function getEmail(){
            return $this->email;
        }

        function setTotalOrders($totalOrders){
            $this->totalOrders = $totalOrders;
        }

        function getTotalOrders(){
            return $this->totalOrders;
        }

        function setTotalValue($totalValue){
            $this->totalValue = $totalValue;
        }

        function getTotalValue(){
            return $this->totalValue;
        }

        function setFirstOrderDateTime($firstOrderDateTime){
            $this->firstOrderDateTime = $firstOrderDateTime;
        }

        function getFirstOrderDateTime(){
            return $this->firstOrderDateTime;
        }

        function setMostRecentOrderDateTime($mostRecentOrderDateTime){
            $this->mostRecentOrderDateTime = $mostRecentOrderDateTime;
        }

        function getMostRecentOrderDateTime(){
            return $this->mostRecentOrderDateTime;
        }
    }

    ?>
    