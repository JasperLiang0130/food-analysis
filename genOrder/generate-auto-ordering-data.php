<?php
    include '../db_conn.php';
    include '../Model/Categories/CategoryDAO.php';
    include '../Model/Items/ItemDAO.php';
    // foreach (glob('../Model/*.php') as $file) {
    //     include $file;
    // }

    // for ($i=0; $i < 100; $i++) { 
    //     print_o(GenerateName());
    //     //print_o(GeneratePhoneNum());
    // }
    $categoryDao = new CategoryDAO();
    $itemDao = new ItemDAO();
    //print_r(($categoryDao->getAll())[0]->getName());
    foreach ($itemDao->getAll() as $key => $object) {
        printf($object->getName().'<br>');
    }
    //print_r($itemDao->getAll()[12]->getName());
    //print_r($categoryDao->findOnePK(3)->getName());
    //print_2D($categoryDao->search('b','Name'));

    function GenerateName(){
        $syllable = rand(2,4); //2~4 or mt_rand()
        //print_o($syllable);
        $consonants = array("b", "bl", "br", "c", "cl", "cr", "d", "dr", "f", "fl", "fr", "g", "gl" ,"gr", "gh", "h", "j", "k", "kr", "m", "l", "n", "p", "pl", "pr", "ph", "q", "r", "s", "sh", "st", "zh", "t", "tr", "v", "w", "wr", "x", "z");
        $vowels = array("a", "e", "i", "o", "u", "ie", "ou", "ae", "y");
        $curr_sy = 0;
        $name = '';
        $c_count = count($consonants);
        $v_count = count($vowels);
        while($curr_sy < $syllable && strlen($name) < 6) //small than 6 characters
        {
            $name .= $consonants[rand(0, $c_count-1)];
            $name .= $vowels[rand(0, $v_count-1)];
            $curr_sy++;
        }
        return ucwords($name);
    }

    function GenerateEmail($name){  
        $source = array('gmail','apple','yahoo','firefox','bbc','hello');
        $s_count = count($source);
        return $name.'@'.$source[rand(0,$s_count-1)].'.com.au';
    }

    function GeneratePhoneNum(){
        $str_num = strval(mt_rand(0, 99999999));
        //$str_num = strval(mt_rand(0, 10));
        while(strlen($str_num) < 8)
        {
            $str_num = '0'.$str_num;
        }
        return '04'.$str_num;
    }

?>