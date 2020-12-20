<?php
    include('OptionSet.php');
    interface OptionSetDAO_interface{
        public function findOnePK($id);
        public function getAll();
        public function query($keyword,$attribute);
        public function getAllFromItemId($itemId);
        public function getAllDistinctItemId();
    }

?>