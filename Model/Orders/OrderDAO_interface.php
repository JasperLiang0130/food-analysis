<?php
    include("Order.php");
    interface OrderDAO_interface {
        public function insert(Order $order);
        public function update(Order $order);
        public function findOnePK($id);
        public function getAll();
        public function query($keyword,$attribute);
        public function getTotalRevenue($start, $end);
        public function getTotalCountOrd($start, $end);
        public function getHighestOrderValue($start, $end);
        public function getLowestOrderValue($start, $end);
        public function getAvgTotalItems($start, $end);
        public function getAvgDistinctItems($start, $end);
        public function getPopularDays($start, $end);
        public function getPopularHoursByDay($start, $end);
        public function getTotalOrders($df, $start, $end);
    }
?>