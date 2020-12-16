<?php

    class OptionSet {

        private $id;
        private $name;
        private $multiOption;
        private $itemId;

        function __construct($id, $name, $multiOption, $itemId){
            $this->id = $id;
            $this->name = $name;
            $this->multiOption = $multiOption;
            $this->itemId = $itemId;
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

        function setMultiOption($multiOption){
            $this->multiOption = $multiOption;
        }

        function getMultiOption(){
            return $this->multiOption;
        }

        function setItemId($itemId){
            $this->itemId = $itemId;
        }

        function getItemId(){
            return $this->itemId;
        }
    }

?>