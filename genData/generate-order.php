<?php

    //include '../db_conn.php';
    // include '../Model/Items/ItemDAO.php';
    // include '../Model/Option_Sets/OptionSetDAO.php';
    // include '../Model/Options/OptionDAO.php';
    // include '../Model/Customer/CustomerDAO.php';
    // include '../Model/Orders/OrderDAO.php';
    // include '../Model/Order_items/OrderItemDAO.php';
    // include '../Model/Order_options/OrderOptionDAO.php';

    //genOrder(1);

    function genOrder($orderNum){
        global $conn;
        $conn->begin_transaction();

        $customerDao = new CustomerDAO();
        $itemDao = new ItemDAO();
        $optSetDao = new OptionSetDAO();
        $optDao = new OptionDAO();
        $orderDao = new OrderDAO();
        $orderItemDao = new OrderItemDAO();
        $orderOptionDao = new OrderOptionDAO();

        for ($i=0; $i < $orderNum; $i++) { 
            $items = $itemDao->getAll();
            $sets_distinct_item = $optSetDao->getAllDistinctItemId();
            $qt_unique_item = mt_rand(1, 3); //ordering 1 to 3 distinct items each order
            echo 'Total unique items: '.$qt_unique_item.'<br>';
            $i_choose_items = genRandomNum(0, count($items)-1, $qt_unique_item);
            //$i_item = array_rand($items, $qt_unique_item); //alternative way
            
            //table value for db
            $totalValue = 0; //will be updated after options inserted
            $totalItems = 0; //will be updated after options inserted
            $distinctItems = count($i_choose_items);
            $json_arr = array(); //will be updated after options inserted
            $orderDateTime = generateOrderDateTime();
            $customerId = mt_rand(0, count($customerDao->getAll())-1);

            $orderId = ($orderDao->insert(new Order(null, $totalValue, $totalItems, $distinctItems, '', $orderDateTime, $customerId)))->getId();

            //echo 'orderID: '.$orderId.'<br>';

            //selected items and insert each items to order_items 
            foreach ($i_choose_items as $index) {
                //echo $index.'<br>';
                $currentItem = $items[$index]; 
                
                //order_items's each columns
                $order_items_qt = mt_rand(1, 6); //how many quantity of each item
                $order_items_singleValue = floatval($currentItem->getBasePrice());
                $order_items_totalValue = $order_items_qt * $order_items_singleValue;
                
                //Insert order_items
                $orderItem = new OrderItem(null, $order_items_qt, $order_items_singleValue, $order_items_totalValue, $currentItem->getId(), $orderId);
                $orderItem_arr = orderItemsToArray($orderItem, $currentItem);
                $orderItemId = ($orderItemDao->insert($orderItem)->getId());

                //update total value and total item for each order
                $totalValue += $order_items_totalValue;
                $totalItems += $order_items_qt;

                //checking whether item is a sets option or not, and insert to order_options
                if (in_array($currentItem->getId(), $sets_distinct_item)) 
                { 
                    //echo $currentItem->getId()." found<br>"; 
                    $sets = $optSetDao->getAllFromItemId($currentItem->getId());
                    foreach ($sets as $set) {
                        //check multiple-options
                        $options = $optDao->getAllFromOptionSetId($set->getId());
                        if ($set->getMultiOption() == 0) {
                            //choose one
                            $i_select_opt = mt_rand(0, count($options)-1);
                            //insert not multi-options for order_options
                            $totalValue += ($order_items_qt * $options[$i_select_opt]->getPrice());
                            $orderOption = new OrderOption(null, $order_items_qt * $options[$i_select_opt]->getPrice(), $orderItemId, $options[$i_select_opt]->getId(), $options[$i_select_opt]->getOptionSetId());
                            $orderOptionDao->insert($orderOption);
                            $orderItem_arr["OrderOptions"][] = orderOptionsToArray($orderOption, $options[$i_select_opt]);

                        }else {
                            //choose zero or more
                            $multi_opt = mt_rand(0, count($options));
                            if($multi_opt != 0){
                                $i_opt_arr = genRandomNum(0, count($options)-1, $multi_opt);
                                foreach ($i_opt_arr as $idx) {
                                    //echo $options[$idx]->getName().'<br>';
                                    //insert multi-option for order_options 
                                    $totalValue += ($order_items_qt * $options[$idx]->getPrice());
                                    $orderOption = new OrderOption(null, $order_items_qt * $options[$idx]->getPrice(), $orderItemId, $options[$idx]->getId(), $options[$idx]->getOptionSetId());
                                    $orderOptionDao->insert($orderOption);
                                    $orderItem_arr["OrderOptions"][] = orderOptionsToArray($orderOption, $options[$idx]);
                                }
                            }
                            
                        }
                    }
                } 
                $json_arr[] = $orderItem_arr;
            }

            //update order detail
            $json = convertJSON($json_arr);
            $orderDao->update(new Order($orderId, $totalValue, $totalItems, $distinctItems, $json, $orderDateTime, $customerId));
            echo $json.'<br>';    
        }

        $conn->commit();

    }
    
    function orderOptionsToArray(OrderOption $orderOption, Option $option){
        return array("Name"=>$option->getName(), "Value"=>$orderOption->getValue(), "OrderItemID"=>$orderOption->getOrderItemId(), "OptionID"=>$orderOption->getOptionId(), "OptionSetID"=>$orderOption->getOptionSetId());
    }

    function orderItemsToArray(OrderItem $orderItem, Item $item){
        return array("Name"=>$item->getName(),"Quantity"=>$orderItem->getQuantity(),"SingleValue"=>$orderItem->getSingleValue(), "TotalValue"=>$orderItem->getTotalValue(), "ItemID"=>$orderItem->getItemId(), "OrderID"=>$orderItem->getOrderId(), "OrderOptions"=> array());
    }

    //range from min to max
    function genRandomNum($min, $max, $quantity) {
        $nums = range($min, $max);
        shuffle($nums); //re-order the nums
        return array_slice($nums, 0, $quantity);
    }
    

    function convertJSON($arr){
        return json_encode($arr);
    }

    function generateOrderDateTime(){
        $timestamp = mt_rand(strtotime("January 1 2000 00:00:00 GMT"), strtotime("now"));
        //echo($timestamp. "<br>");
        $dt = date_format(date_create()->setTimestamp($timestamp)->setTimezone(new DateTimeZone('Australia/Sydney')), "Y-m-d H:i:s");

        return strval($dt); //string type
    }

?>