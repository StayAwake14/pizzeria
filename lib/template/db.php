<?php
require_once('account.php');

class DB{

    private $host = "localhost";
    private $username = "u_pizzeria";
    private $pass = "zaq1@WSX";
    private $db_name = "pizzeria";
    private $pdo;

    public function __construct(){
        $this->pdo = new PDO('mysql:dbname='.$this->db_name.';host='.$this->host, $this->username, $this->pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
    }

    public function getPizzaCakes(){
        $get_cakes = "SELECT * FROM pizza_cakes";
        $pizzaCakes = array();
        if($exec = $this->pdo->query($get_cakes))
        {
            while($dl = $exec->fetch()){
                $cake_id = $dl['id'];
                $cake_size = $dl['size'];
                $cake_price = $dl['price'];
                $cake_cm = $dl['cm'];

                array_push($pizzaCakes, 
                array(
                    "id" => $cake_id,
                    "size" => $cake_size,
                    "price" => $cake_price,
                    "cm" => $cake_cm
                ));
            }
            return $pizzaCakes;
        }          
    }

    public function getPizzaCakeDetails($id){
        $get_cake_details = "SELECT size, price FROM pizza_cakes WHERE id=$id";
        $cakeDetails = array();
        if($exec = $this->pdo->query($get_cake_details))
        {
            while($dl = $exec->fetch())
            {
                $cake_price = $dl['price'];
                $cake_size = $dl['size'];

                array_push($cakeDetails, 
                array(
                    "size" => $cake_size,
                    "price" => $cake_price,
                ));
            }
            return $cakeDetails;
        }
    }

    public function getAllProducts(){
        $get_pizza_products = "SELECT `id`, `name` FROM pizza_products;";
        $pizza_products = array();
        if($exec = $this->pdo->query($get_pizza_products))
        {
            while($dl = $exec->fetch())
            {
                $pizza_product_id = $dl['id'];
                $pizza_product_name = $dl['name'];
                array_push($pizza_products, 
                array(
                    "id" => $pizza_product_id,
                    "name" => $pizza_product_name,
                ));
            }
            return $pizza_products;
        }

    }

    public function getAllPizza(){
        $get_pizza_names = "SELECT `id`, `name` FROM pizza_names";
        $pizza_names= array();
        if($exec = $this->pdo->query($get_pizza_names))
        {
            while($dl = $exec->fetch()){
                $pizza_name_id = $dl['id'];
                $pizza_name = $dl['name'];
                array_push($pizza_names, 
                array(
                    "id" => $pizza_name_id,
                    "name" => $pizza_name,
                ));
            }
            return $pizza_names;
        }

    }

    public function getPizzaProducts($id){
        $get_pizza_products = "SELECT pizza_products.name, pizza_products.price FROM pizza_products INNER JOIN pizza_recipes ON id_product=pizza_products.id INNER JOIN pizza_names ON id_pizza_name=pizza_names.id WHERE id_pizza_name=$id GROUP BY id_pizza_name, id_product";
        $pizza_products = array();
        if($exec = $this->pdo->query($get_pizza_products))
        {
            while($dl = $exec->fetch()){
                $pizza_product_name = $dl['name'];
                $pizza_product_price = $dl['price'];
                array_push($pizza_products, 
                array(
                    "name" => $pizza_product_name,
                    "price" => $pizza_product_price,
                ));
            }
            return $pizza_products;
        }

    }

    public function getPizzaProductsPrice($id){

        $get_pizzza_products = "SELECT sum(pizza_products.price) as suma FROM pizza_products INNER JOIN pizza_recipes ON id_product=pizza_products.id INNER JOIN pizza_names ON id_pizza_name=pizza_names.id WHERE id_pizza_name=$id;";

        if($exec = $this->pdo->query($get_pizzza_products))
        {
            while($dl = $exec->fetch())
            {
                $pizza_products_sum = $dl['suma'];
            }
            return $pizza_products_sum;
        }

    }

    public function getPizzaNames(){
        $get_pizza_name  = "SELECT pizza_names.id, pizza_names.name, pizza_names.id_category, SUM(pizza_products.price) as sum FROM pizza_products INNER JOIN pizza_recipes ON id_product=pizza_products.id INNER JOIN pizza_names ON id_pizza_name=pizza_names.id GROUP BY pizza_names.name";
        $pizza_name_details = array();

        if($exec = $this->pdo->query($get_pizza_name))
        {
            while($dl = $exec->fetch())
            {
                $pizza_name_id = $dl['id'];
                $pizza_name = $dl['name'];
                $pizza_name_sum = $dl['sum'];
                $pizza_categoryid = $dl['id_category'];
                array_push($pizza_name_details, 
                array(
                    "id" => $pizza_name_id,
                    "name" => $pizza_name,
                    "sum" => $pizza_name_sum,
                    "id_category" => $pizza_categoryid
                ));
            }

            return $pizza_name_details;
        }
    }

    public function getPromotionCode($code){
        $get_promotion_codes ="SELECT * FROM pizza_promotions WHERE code='$code'";
        if($exec = $this->pdo->query($get_promotion_codes))
        {
            if($code = $exec->fetch())
            {
                return $code['percentage'];
            }
        }
    }

    public function getPromotionCodes(){
        $get_pizza_promotions = "SELECT * FROM pizza_promotions";
        $pizza_promotions = array();
        if($exec = $this->pdo->query($get_pizza_promotions))
        {
            while($dl = $exec->fetch()){
                $pizza_promotions_id = $dl['id'];
                $pizza_promotions_code = $dl['code'];
                $pizza_promotions_percentage = $dl['percentage'];
                
                array_push($pizza_promotions, 
                array(
                    "id" => $pizza_promotions_id,
                    "code" => $pizza_promotions_code,
                    "percentage" => $pizza_promotions_percentage
                ));
            }
            return $pizza_promotions;
        }
    }

    public function getCategories(){
        $get_pizza_categories = "SELECT `id`,`name` FROM pizza_categories";
        $pizza_categories = array();
        if($exec = $this->pdo->query($get_pizza_categories))
        {
            while($dl = $exec->fetch())
            {
                $pizza_categories_id = $dl['id'];
                $pizza_categories_name = $dl['name'];
                array_push($pizza_categories, 
                array(
                    "id" => $pizza_categories_id,
                    "name" => $pizza_categories_name
                ));
            }

            return $pizza_categories;
        }
    }

    public function checkIfAlreadyRegistered($email){
        $get_account = "SELECT * FROM pizza_accounts WHERE email='$email'";
        
        if($exec = $this->pdo->query($get_account))
        {
            $count = $exec->rowCount();
        }

        return $count;
    }

    public function login($email, $password){
        $login = "SELECT `password` FROM pizza_accounts WHERE email='$email'";
        if($exec = $this->pdo->query($login))
        {
            $dbHash = $exec->fetch();
            if(password_verify("$password", $dbHash['password']))
            {
                $get_user_role = "SELECT `role` FROM pizza_accounts WHERE email='$email'";

                if($exec3 = $this->pdo->query($get_user_role))
                {
                    while($dl = $exec3->fetch())
                    {
                        $role = $dl['role'];
                    }
                }

                $get_user_data = "SELECT * FROM pizza_users INNER JOIN pizza_accounts ON pizza_users.id_account = pizza_accounts.id WHERE pizza_accounts.email = '$email'";
                if($exec2 = $this->pdo->query($get_user_data))
                {
                    while($dl = $exec2->fetch())
                    {
                        $_SESSION['logged'] = true;
                        $mail = $email;
                        $fname = $dl['fname'];
                        $sname = $dl['sname'];
                        $phone = $dl['phone'];
                        $city = $dl['city'];
                        $street = $dl['street'];
                        $id_account = $dl['id_account'];
                        $postalcode = $dl['postalcode'];
                    }
                  
                    $account = new Account();
                    $account->loadAccount($fname, $sname, $phone, $city, $street, $id_account, $email, $role, $postalcode);
                    $_SESSION['account'] = $account;
                    header("Location: $_SERVER[HTTP_REFERER]?success=1");
                }
                
            }
            else
            {
                header("Location: $_SERVER[HTTP_REFERER]?error=5");
            }

        }
        else
        {
            header("Location: $_SERVER[HTTP_REFERER]?error=8");
        }
    }

    public function logout(){
        $account = null;
        session_destroy();
        header("Location: https://$_SERVER[HTTP_HOST]/pizzeria/index.php?success=2");
    }

    private function createProfile($fname, $sname, $phone, $city, $street, $postalcode){
        $get_account_id = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'pizzeria' AND TABLE_NAME='pizza_accounts'";
        $exec2 = $this->pdo->query($get_account_id);
        $getId = $exec2->fetch();
        $newId = $getId['AUTO_INCREMENT']-1;
        $create_profile = "INSERT INTO pizza_users (`fname`,`sname`,`phone`,`city`,`street`,`id_account`, `postalcode`) VALUES('$fname', '$sname', '$phone', '$city', '$street', $newId, '$postalcode')";
        if($exec3 = $this->pdo->query($create_profile))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function register($email, $hash, $fname, $sname, $phone, $city, $street, $postalcode){
        $register_account = "INSERT INTO pizza_accounts (`password`, `email`, `role`) VALUES('$hash', '$email', 'Klient')";
        if($exec = $this->pdo->query($register_account))
        { 
            if($this->createProfile($fname, $sname, $phone, $city, $street, $postalcode) === true)
            {
                header("Location: $_SERVER[HTTP_REFERER]?success=3");
            }
            else
            {
                $delete_account = "DELETE FROM pizza_accounts WHERE email='$email'";
                $this->pdo->query($delete_account);
                header("Location: $_SERVER[HTTP_REFERER]?error=4");
            }
        }
    }

    public function getPizzaCakesId($name){
        $get_pizza_cake = "SELECT id FROM pizza_cakes WHERE size='$name'";
        if($exec = $this->pdo->query($get_pizza_cake))
        {
            $dl = $exec->fetch();
            return $dl['id'];
        }
    }

    private function addOrderProduct($delivery_id, $quantity, $product_id, $cake_name){
        $cake_id = $this->getPizzaCakesId($cake_name);
        $add_order_product = "INSERT INTO pizza_delivery_products(`delivery_id`,`count`, `product_id`, `cake_id`) VALUES('$delivery_id','$quantity','$product_id', '$cake_id')";
        if($exec = $this->pdo->query($add_order_product))
        return true;
        else
        return false;
    }

    public function addOrder($delivery_code, $account_id, $comment, $status, $date, $street, $postalcode, $city, $transaction_type, $company, $sum){
        $get_last_order_id = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'pizzeria' AND TABLE_NAME='pizza_delivery'";
        $exec = $this->pdo->query($get_last_order_id);
        $getId = $exec->fetch();
        $nextId = $getId['AUTO_INCREMENT'];
        $cookie_data = stripslashes($_COOKIE['cart']);
        $cart = json_decode($cookie_data, true);
        $add_order = "INSERT INTO pizza_delivery 
        (`delivery_code`,`account_id`,`comment`,`status`,`date`,`street`,`postalcode`,`city`,`transaction_type`,`company`, `sum`)
        VALUES('$delivery_code',$account_id, '$comment', '$status', '$date', '$street', '$postalcode', '$city', '$transaction_type', '$company', $sum);";
        
        if($exec2 = $this->pdo->query($add_order))
        {
            foreach($cart as $keys => $values)
            {
                if($this->addOrderProduct($nextId, $values['pizza_quantity'], $values['pizza_id'], $values['pizza_size']) === true)
                {

                }
                else 
                {
                    header("Location: $_SERVER[HTTP_REFERER]?error=9");
                    return false;
                }
            }

            header("Location: $_SERVER[HTTP_REFERER]?success=4&code=".$delivery_code."");
            return true;

        }
        
    }

    public function getUserOrders($uid){
        
        $get_user_order = "SELECT * FROM pizza_delivery INNER JOIN pizza_delivery_products ON pizza_delivery.id = pizza_delivery_products.delivery_id WHERE account_id='$uid' GROUP BY delivery_id ORDER BY `date` DESC;";

        if($exec = $this->pdo->query($get_user_order))
        {   
            while($dl = $exec->fetch())
            {
                $delivery_id = $dl['delivery_id'];
                $dbDate = $dl['date'];
                $date = new DateTime($dbDate);
                $date = $date->format('Y-m-d H:i');
                $status = $dl['status'];
                $delivery_code = $dl['delivery_code'];
                $sum = $dl['sum'];
                $postalcode = $dl['postalcode'];
                $street = $dl['street'];
                $city = $dl['city'];
                $dateYMD = new DateTime($dbDate);
                $dateYMD = $dateYMD->format('Y-m-d');
                $comment = $dl['comment'];

                $order_array[$delivery_id] = array
                (
                    "delivery_id" => $delivery_id,
                    "delivery_code" => $delivery_code,
                    "status" => $status,
                    "date_ymd" => $dateYMD,
                    "date_time" => $date,
                    "postalcode" => $postalcode,
                    "city" => $city,
                    "street" => $street,
                    "sum" => $sum,
                    "comment" => $comment,
                    "items_order" => array()
                );

                $get_user_orders = "SELECT * FROM pizza_delivery INNER JOIN pizza_delivery_products ON pizza_delivery.id = pizza_delivery_products.delivery_id WHERE account_id='$uid' AND delivery_id='$delivery_id'";
  
                if($exec4  = $this->pdo->query($get_user_orders))
                {
                    while($dl4 = $exec4->fetch())
                    {
                        $count = $dl4['count'];
                        $pizza_id = $dl4['product_id'];
                        $get_orders_info = "SELECT * FROM pizza_delivery INNER JOIN pizza_delivery_products ON pizza_delivery.id = pizza_delivery_products.delivery_id WHERE account_id='$uid' AND delivery_id='$delivery_id' GROUP BY delivery_id";
                        $getPizzaProductsPrice = $this->getPizzaProductsPrice($pizza_id);
                        $getPizzaCakes = $this->getPizzaCakeDetails($dl4['cake_id']);
               
                        foreach($getPizzaCakes as $keys => $values)
                        {
                            $pizzaSize = $values['size'];
                            $pizzaPiece = $getPizzaProductsPrice+$values['price'];
                        }
                              
                        if($exec2 = $this->pdo->query($get_orders_info))
                        {
                            while($dl2 = $exec2->fetch())
                            {
                                $get_pizza_names = "SELECT * FROM pizza_names WHERE id='$pizza_id';";
                                if($exec3 = $this->pdo->query($get_pizza_names))
                                {
                                    while($dl3 = $exec3->fetch())
                                    {
                                        $pizza_name = $dl3['name'];
                                        array_push($order_array[$delivery_id]["items_order"], array
                                        (
                                            "pizza_id" => $pizza_id,
                                            "pizza_size" => $pizzaSize,
                                            "pizza_piece" => $pizzaPiece,
                                            "pizza_name" => $pizza_name,
                                            "pizza_count" => $count
                                        ));
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $order_array;
    }

    
    public function getAllUsersOrders(){
        
        $get_user_order = "SELECT * FROM pizza_delivery INNER JOIN pizza_delivery_products ON pizza_delivery.id = pizza_delivery_products.delivery_id GROUP BY delivery_id ORDER BY `date` DESC;";

        if($exec = $this->pdo->query($get_user_order))
        {   
            
            while($dl = $exec->fetch())
            {
                $delivery_id = $dl['delivery_id'];
                $delivery_code = $dl['delivery_code'];
                $status = $dl['status'];
                $dbDate = $dl['date'];
                $date = new DateTime($dbDate);
                $date = $date->format('Y-m-d H:i');
                $dateYMD = new DateTime($dbDate);
                $dateYMD = $dateYMD->format('Y-m-d');
                $postalcode = $dl['postalcode'];
                $street = $dl['street'];
                $city = $dl['city'];
                $sum = $dl['sum'];
                $comment = $dl['comment'];

                $order_array[$delivery_id] = array
                (
                    "delivery_id" => $delivery_id,
                    "delivery_code" => $delivery_code,
                    "status" => $status,
                    "date_ymd" => $dateYMD,
                    "date_time" => $date,
                    "postalcode" => $postalcode,
                    "city" => $city,
                    "street" => $street,
                    "sum" => $sum,
                    "comment" => $comment,
                    "items_order" => array()
                );

                $get_user_orders = "SELECT * FROM pizza_delivery INNER JOIN pizza_delivery_products ON pizza_delivery.id = pizza_delivery_products.delivery_id WHERE delivery_id='$delivery_id'";

                if($exec4  = $this->pdo->query($get_user_orders))
                {                            
                    while($dl4 = $exec4->fetch())
                    {
                        $count = $dl4['count'];
                        $pizza_id = $dl4['product_id'];
                        $get_orders_info = "SELECT * FROM pizza_delivery INNER JOIN pizza_delivery_products ON pizza_delivery.id = pizza_delivery_products.delivery_id WHERE delivery_id='$delivery_id' GROUP BY delivery_id";
                        $getPizzaProductsPrice = $this->getPizzaProductsPrice($pizza_id);
                        $getPizzaCakes = $this->getPizzaCakeDetails($dl4['cake_id']);
                        
                        foreach($getPizzaCakes as $keys => $values)
                        {
                            $pizzaSize = $values['size'];
                            $pizzaPiece = $getPizzaProductsPrice+$values['price'];
                        }

                        if($exec2 = $this->pdo->query($get_orders_info))
                        {
                            while($dl2 = $exec2->fetch())
                            {
                                $get_pizza_names = "SELECT * FROM pizza_names WHERE id='$pizza_id';";
                                if($exec3 = $this->pdo->query($get_pizza_names))
                                {
                                    while($dl3 = $exec3->fetch())
                                    {
                                        $pizza_name = $dl3['name'];
                                        array_push($order_array[$delivery_id]["items_order"], array
                                        (
                                            "pizza_id" => $pizza_id,
                                            "pizza_size" => $pizzaSize,
                                            "pizza_piece" => $pizzaPiece,
                                            "pizza_name" => $pizza_name,
                                            "pizza_count" => $count
                                        ));
                                    }
                                }
                            }
                        }
                    }
                }
            }

            return $order_array;
        }
    }

    public function editOrderStatus($id, $status){
        $update_order_status = 'UPDATE pizza_delivery SET status="'.$status.'" WHERE id='.$id.'';
        $this->pdo->query($update_order_status);
    }

    public function getLocationList(){
        $find_location = 'SELECT * FROM pizza_stations';
        $locationList = array();
        if($exec = $this->pdo->query($find_location))
        {
            while($dl = $exec->fetch())
            {
                $location_list_id = $dl['id'];
                $location_list_city = $dl['city'];
                $location_list_street = $dl['street'];
                $location_list_street_number = $dl['street_number'];
                $location_list_phone = $dl['phone'];
                $location_list_open = $dl['open_time'];
                $location_list_close = $dl['close_time'];
                array_push($locationList, 
                array(
                    "id" => $location_list_id,
                    "city" => $location_list_city,
                    "street" => $location_list_street,
                    "street_number" => $location_list_street_number,
                    "phone" => $location_list_phone,
                    "open_time" => $location_list_open,
                    "close_time" => $location_list_close
                ));
            }
            return $locationList;
        }
    }

    public function addCake($size, $price, $cm){
        $check_cake = "SELECT * FROM pizza_cakes WHERE `size`='$size'";
        $add_cake = "INSERT INTO pizza_cakes(`size`,`price`,`cm`) VALUES('$size',$price,$cm)";
        if($this->pdo->query($check_cake)->rowCount() < 1)
        {
            $this->pdo->query($add_cake);
        }
        else
        {
            die(header("HTTP/1.0 404 Not Found"));
        }
    }

    public function deleteCake($id){
        $delete_cake = "DELETE FROM pizza_cakes WHERE id=$id";
        $this->pdo->query($delete_cake);
    }

    public function addCategory($name){
        $check_category = "SELECT * FROM pizza_categories WHERE `name`='$name'";
        $add_category = "INSERT INTO pizza_categories(`name`) VALUES('$name')";
        if($this->pdo->query($check_category)->rowCount() < 1)
        {
            $this->pdo->query($add_category);
        }
        else
        {
            die(header("HTTP/1.0 404 Not Found"));
        }
    }

    public function deleteCategory($id){
        $check_delete_product = "SELECT * FROM pizza_names WHERE id_category=$id";
        $delete_category= "DELETE FROM pizza_categories WHERE id=$id";
        if(!$this->pdo->query($check_delete_product)->rowCount() > 0)
        {
            $this->pdo->query($delete_category);
        }
        else
        {
            die(header("HTTP/1.0 404 Not Found"));
        }
    }

    public function addProduct($name, $price){
        $check_product = "SELECT * FROM pizza_products WHERE `name`='$name'";
        $add_product = "INSERT INTO pizza_products(`name`,`price`) VALUES('$name', $price)";
        if($this->pdo->query($check_product)->rowCount() < 1)
        {
            $this->pdo->query($add_product);
        }
        else
        {
            die(header("HTTP/1.0 404 Not Found"));
        }
        
    }

    public function deleteProduct($id){
        $check_delete_product = "SELECT * FROM pizza_recipes WHERE id_product=$id";
        $delete_product = "DELETE FROM pizza_products WHERE id=$id";
        if(!$this->pdo->query($check_delete_product)->rowCount() > 0)
        {
            $this->pdo->query($delete_product);
        }
        else
        {
            die(header("HTTP/1.0 404 Not Found"));
        }
    }

    public function addPromotionCode($code, $percentage){
        $check_code = "SELECT * FROM pizza_promotions WHERE `code`='$code'";
        $add_promotion = "INSERT INTO pizza_promotions(`code`,`percentage`) VALUES('$code', $percentage)";
        if($this->pdo->query($check_code)->rowCount() < 1)
        {
            $this->pdo->query($add_promotion);
        }
        else
        {
            die(header("HTTP/1.0 404 Not Found"));
        }
    }

    public function deletePromotionCode($id){
        $delete_promotion = "DELETE FROM pizza_promotions WHERE id=$id";
        $this->pdo->query($delete_promotion);
    }

    public function getUserOrder($code){
        $get_user_order = "SELECT * FROM pizza_delivery WHERE delivery_code=$code AND `status`!='Zakończone' AND `status`!='Odrzucone'";
        $user_order_data = array();
        if($exec = $this->pdo->query($get_user_order))
        {
            while($dl = $exec->fetch())
            {
                $user_order_data['status'] = $dl['status'];
                $user_order_data['sum'] = $dl['sum'];
            }

            return $user_order_data;
        }
    }

    public function addRecipe($pizza_name, $product_list, $category, $image){
        $check_pizza_name = "SELECT * FROM pizza_names WHERE `name`='$pizza_name'";
        if($this->pdo->query($check_pizza_name)->rowCount() < 1)
        {
            $get_pizza_id = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'pizzeria' AND TABLE_NAME='pizza_names'";
            $exec = $this->pdo->query($get_pizza_id);
            $getId = $exec->fetch();
            $newPizzaId = $getId['AUTO_INCREMENT'];

            $add_pizza_name = "INSERT INTO pizza_names(`name`,`id_category`) VALUES('$pizza_name', $category)";
            $this->pdo->query($add_pizza_name);

            foreach($product_list as $product)
            {
                echo $product."HERE";
                $add_pizza_recipe = "INSERT INTO pizza_recipes(`id_pizza_name`,`id_product`) VALUES($newPizzaId, $product)";
                $this->pdo->query($add_pizza_recipe);
            }
            
            if(isset($image))
            {
                $ext = pathinfo($image['pizzaPicture']['name'], PATHINFO_EXTENSION);
                $image['pizzaPicture']['name'] = $newPizzaId.".".$ext;

                if(is_array($image)) 
                {
                    if(is_uploaded_file($image['pizzaPicture']['tmp_name'])) 
                    {
                        $sourcePath = $image['pizzaPicture']['tmp_name'];
                        $targetPath = "./../../assets/images/catalog/".$image['pizzaPicture']['name'];
                        if(move_uploaded_file($sourcePath,$targetPath)) 
                        {
                        }
                    }
                }
            }
        }
        else
        {
            die(header("HTTP/1.0 404 Not Found"));
        }
    }

    public function deleteRecipe($id){
        $delete_recipe = "DELETE FROM pizza_recipes WHERE id_pizza_name=$id";
        $this->pdo->query($delete_recipe);
        $delete_name = "DELETE FROM pizza_names WHERE id=$id";
        $this->pdo->query($delete_name);
        $targetPath = "./../../assets/images/catalog/".$id.".jpg";
        unlink($targetPath);
                    
    }

    public function checkId(){
        $get_pizza_id = "SELECT AUTO_INCREMENT FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'pizzeria' AND TABLE_NAME='pizza_names'";
        $exec2 = $this->pdo->query($get_pizza_id);
        $getId = $exec2->fetch();
        $newId = $getId['AUTO_INCREMENT'];
        echo $newId;
    }
}



?>