<?php
    include('Customer.php');
    interface CustomerDAO_interface{
        public function insert(Customer $customer);
        public function findOnePK($id);
        public function getAll();
        public function query($keyword,$attribute);
        public function getTotalCountByDate($start,$end);
        public function getPeopleJoinDay($start,$end);
    }
?>