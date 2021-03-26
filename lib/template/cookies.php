<?php
     require_once("db.php");

    if(isset($_POST["pizza_id"]))
    {
        if(isset($_COOKIE["cart"]))
        {
            $cookie_data = stripslashes($_COOKIE['cart']);

            $cart = json_decode($cookie_data, true);
        }
        else
        {
            $cart = array();
        }

        
        $pizza_cart_id = array_column($cart, 'pizza_id');
        $pizza_cart_size = array_column($cart, 'pizza_size');

        $found = false;

        foreach($cart as $keys => $values)
        {
           if($cart[$keys]["pizza_id"] == $_POST['pizza_id'] && $cart[$keys]["pizza_size"] == $_POST["pizza_size"]){
                $found = true;
           }
        }

        if(in_array($_POST["pizza_id"], $pizza_cart_id) && $found == true)
        {
            foreach($cart as $keys => $values)
            {
                if($cart[$keys]["pizza_id"] == $_POST["pizza_id"] && $cart[$keys]["pizza_size"] == $_POST["pizza_size"])
                {
                    $cart[$keys]["pizza_quantity"] = $cart[$keys]["pizza_quantity"] + $_POST['pizza_quantity'];
                    $cart[$keys]["pizza_price"] = $cart[$keys]["pizza_quantity"] * $_POST['pizza_price'];
                }
            }
        }
        else
        {
            $item_array = array(
                'pizza_id'			=>	$_POST["pizza_id"],
                'pizza_name'        =>  $_POST["pizza_name"],
                'pizza_quantity'		=>	$_POST['pizza_quantity'],
                'pizza_size'            => $_POST['pizza_size'],
                'pizza_price'            => $_POST['pizza_price'] * $_POST['pizza_quantity'],
            );
            $cart[] = $item_array;
        }

            
        $item_data = json_encode($cart, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        setcookie('cart', $item_data, time() + 3600, '/', NULL, 0);
    }

    if(isset($_POST["action"]))
    {
        if($_POST["action"] === "delete")
        {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
            $cookie_data = stripslashes($_COOKIE['cart']);
            $cart_data = json_decode($cookie_data, true);
            foreach($cart_data as $keys => $values)
            {
                if($cart_data[$keys]['pizza_id'] == $_POST["pizza_id_delete"] && $cart_data[$keys]['pizza_size'] == $_POST["pizza_size_delete"])
                {
                    unset($cart_data[$keys]);
                    $item_data = json_encode($cart_data);
                    setcookie("cart", $item_data, time() + 3600, '/', NULL, 0);
                }
            }
        }
        if($_POST["action"] === "clear")
        {
            setcookie("cart", "", time() - 3600, '/', NULL, 0);
        }

        if($_POST["action"] === "pcode")
        {
            $connect = new DB();
            $code = $connect->getPromotionCode($_POST['code']);
            if(!empty($code))
            {
                echo "Kod znaleziony - ".$code."%";
            } 
            else 
            {
                echo "Kod nie istnieje";    
            }
            $connect= null;
        }

        if($_POST['action'] === "order_info")
        {
            $connect = new DB();
            $order_data = $connect->getUserOrder($_POST['order_number']);
            if(!empty($order_data))
            {
                echo "<p class='p-0 m-0'><b>Status:</b> ".$order_data["status"]."<br><b>Kwota:</b> ".$order_data["sum"]." PLN</p>";
            }
            else
            {
                echo "Nie znaleziono zamówienia";
            }
        }
    }
?>