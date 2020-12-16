<?php

    class Option {

        private $id;
        private $name;
        private $price;
        private $optionSetId;

        function __construct($id, $name, $price, $optionSetId){
            $this->id = $id;
            $this->name = $name;
            $this->price = $price;
            $this->optionSetId = $optionSetId;
        }

        function setId($id){
            $this->id = $id;
        }

        function getId(){
            return $this->id;
        }

        function setName($name){
            $this->name = $name;
        }

        function getName(){
            return $this->name;
        }

        function setPrice($price){
            $this->price = $price;
        }

        function getPrice(){
            return $this->price;
        }

        function setOptionSetId($optionSetId){
            $this->optionSetId = $optionSetId;
        }

        function getOptionSetId(){
            return $this->optionSetId;
        }
    }

?>