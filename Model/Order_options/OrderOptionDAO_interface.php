<?php

    include("OrderOption.php");
    interface OrderOptionDAO_interface {
        public function insert(OrderOption $orderOption);
        public function findOnePK($id);
        public function getAll();
        public function query($keyword,$attribute);
    }
?>