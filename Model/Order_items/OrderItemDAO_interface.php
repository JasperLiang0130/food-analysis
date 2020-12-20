<?php

    include("OrderItem.php");
    interface OrderItemDAO_interface {
        public function insert(OrderItem $orderItem);
        public function findOnePK($id);
        public function getAll();
        public function query($keyword,$attribute);
    }
?>