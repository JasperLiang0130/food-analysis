<?php
    include('Option.php');
    interface OptionDAO_interface{
        public function findOnePK($id);
        public function getAll();
        public function query($keyword,$attribute);
        public function getAllFromOptionSetId($optionSetId);
    }
?>