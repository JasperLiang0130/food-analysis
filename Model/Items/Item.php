<?php

    class Item{

        private $id;
        private $name;
        private $description;
        private $basePrice;
        private $categoryId;

        function __construct($id, $name, $description, $basePrice, $categoryId){
            $this->id = $id;
            $this->name = $name;
            $this->description = $description;
            $this->basePrice = $basePrice;
            $this->categoryId = $categoryId;
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

        function setDescription($description){
            $this->description = $description;
        }

        function getDescription(){
            return $this->description;
        }

        function setBasePrice($basePrice){
            $this->basePrice = $basePrice;
        }

        function getBasePrice(){
            return $this->basePrice;
        }

        function setCategoryId($categoryId){
            $this->categoryId = $categoryId;
        }

        function getCategoryId(){
            return $this->categoryId;
        }

    }
?>