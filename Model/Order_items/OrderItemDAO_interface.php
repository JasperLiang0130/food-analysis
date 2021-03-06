<?php

    include("OrderItem.php");
    interface OrderItemDAO_interface {
        public function insert(OrderItem $orderItem);
        public function findOnePK($id);
        public function getAll();
        public function search($keyword,$attribute);
        public function query($keyword);
        public function queryByDate($itemName, $start, $end);
        public function queryByDate2($itemName, $start, $end);
        public function getAllIncName($start, $end);
        public function getAllIncName2($start, $end);
        public function getAllCategoryCount($start, $end);


    }
?>