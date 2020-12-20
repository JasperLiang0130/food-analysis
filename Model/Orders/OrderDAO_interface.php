<?php
    include("Order.php");
    interface OrderDAO_interface {
        public function insert(Order $order);
        public function update(Order $order);
        public function findOnePK($id);
        public function getAll();
        public function query($keyword,$attribute);
    }
?>