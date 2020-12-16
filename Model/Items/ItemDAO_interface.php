<?php

    interface ItemDAO_interface{
        public function findOnePK($id);
        public function getAll();
        public function query($keyword,$attribute);
    }

?>