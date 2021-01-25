<?php
    //include '../db_conn.php';
    //include '../Model/Customer/CustomerDAO.php';
    // foreach (glob('../Model/*.php') as $file) {
    //     include $file;
    // }

    //generateCustomer(100);
    
    
        
    function generateCustomer($num){

        $customerDao = new CustomerDAO();
        for ($i=0; $i < $num; $i++) { 
            $name = generateName();
            $phone = generatePhoneNum();
            $email = generateEmail($name);
            $totalOrders = generateNum(10, 100); 
            $totalValues = $totalOrders * mt_rand(10000,16000) / 100;
            $firsttime = generateDateTime();
            do {
                $recenttime = generateDateTime();
            } while (strtotime($firsttime) > strtotime($recenttime));
            $customer = new Customer(null, $phone, $email, $name, $totalOrders, $totalValues, $firsttime, $recenttime);
            //print_r($customer);
            $customerDao->insert($customer);
        }

    }

    function generateNum($start, $end){
        return mt_rand($start, $end);
    }

    function generateDateTime(){
        $timestamp = mt_rand(strtotime("January 1 2000 00:00:00 GMT"), strtotime("now"));
        //echo($timestamp. "<br>");
        $dt = date_format(date_create()->setTimestamp($timestamp)->setTimezone(new DateTimeZone('Australia/Sydney')), "Y-m-d H:i:s");

        return strval($dt); //string type
    }

    function generateName(){
        $syllable = rand(2,4); //2~4 or mt_rand()
        //print_o($syllable);
        $consonants = array("b", "bl", "br", "c", "cl", "cr", "d", "dr", "f", "fl", "fr", "g", "gl" ,"gr", "gh", "h", "j", "k", "kr", "m", "l", "n", "p", "pl", "pr", "ph", "q", "r", "s", "sh", "st", "zh", "t", "tr", "v", "w", "wr", "x", "z");
        $vowels = array("a", "ae", "e", "ie", "i", "o", "ou", "u", "y");
        $curr_sy = 0;
        $name = '';
        $c_count = count($consonants);
        $v_count = count($vowels);
        while($curr_sy < $syllable && strlen($name) < 6) //small than 6 characters
        {
            $name .= $consonants[mt_rand(0, $c_count-1)];
            $name .= $vowels[mt_rand(0, $v_count-1)];
            $curr_sy++;
        }
        return ucwords($name);
    }

    function generateEmail($name){  
        $source = array('gmail','apple','yahoo','firefox','bbc','hello');
        $s_count = count($source);
        return $name.'@'.$source[rand(0,$s_count-1)].'.com.au';
    }

    function generatePhoneNum(){
        $str_num = strval(mt_rand(0, 99999999));
        //$str_num = strval(mt_rand(0, 10));
        while(strlen($str_num) < 8)
        {
            $str_num = '0'.$str_num;
        }
        return '04'.$str_num;
    }



?>