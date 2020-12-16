<?php

    interface OptionDAO_interface{
        public function findOnePK($id);
        public function getAll();
        public function query($keyword,$attribute);
    }
?>