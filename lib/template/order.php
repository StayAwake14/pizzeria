<?php

class Order{

    public function createOrder($connect){
        $fname = trim($_POST['fname']);
        $sname = trim($_POST['sname']);
        $phone = trim($_POST['phone']);
        $email = htmlentities(trim($_POST['email']));
        $city = trim($_POST['city']);
        $street = trim($_POST['street']);
        $postalcode = trim($_POST['postalcode']);
        $comment = trim($_POST['comment']);
        $company = trim($_POST['companyname']);
        $payment =  trim($_POST['pmethod']);
        $code = trim($_POST['pcode']);
        $account_id = 0;
        $date = new DateTime();
        $date = $date->format('Y-m-d H:i:s');
        $delivery_code = $six_digit_random_number = mt_rand(100000, 999999);
        $sum = 0;
        $cookie_data = stripslashes($_COOKIE['cart']);
        $cart = json_decode($cookie_data, true);
        

        if(isset($_SESSION['logged']))
        {
          $account_id = $_SESSION['account']->getIdAccount();
        }


        foreach($cart as $keys => $values)
        {
          $sum += $values['pizza_price'];
        }

        if(!empty($code))
        {
          $percentage = $connect->getPromotionCode($code);
          $minus = round(($percentage/100)*$sum);
          $sum -= $minus;
        }

        if($connect->addOrder($delivery_code, $account_id, $comment, "Oczekuje na akceptacje", $date, $street, $postalcode, $city, $payment, $company, $sum) === true)
        {
          unset($_COOKIE['cart']);
          setcookie("cart", "", time() - 3600, '/', NULL, 0);
          
        }
    }

}



?> 