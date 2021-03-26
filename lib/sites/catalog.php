<?php 

  require_once("./../template/template.php");
  require_once("./../template/db.php");
  require_once("./../template/account.php");
  require_once("./../template/response.php");
  require_once("./../template/order.php");
  
  session_start();
  ob_start();
  $template = new Template();
  $connect = new DB();
  $response = new Response();

  $size_list = $connect->getPizzaCakes();
  $pizza_list = $connect->getPizzaNames();
  $pizza_categories = $connect->getCategories();

  $path = array("../../lib", "../../assets", "../../");
 // unset($_COOKIE['cart']);

  if(isset($_POST['loginButton']))
  {
    $email = htmlentities(trim($_POST['email']));
    $password = htmlentities(trim($_POST['password']));
    $account = $connect->login($email, $password);
  }

  if(isset($_POST['logout']))
  {
    $account = $connect->logout();
  }

  if(isset($_GET['error']))
  {
  $response->getErrorResponse($_GET['error'], $template);
  }

  if(isset($_GET['success']))
  {
    $response->getSuccesResponse($_GET['success'], $template);
  }

?>

<!doctype html>
<html lang="PL">
    <?php 
      if(!isset($_POST['filter']))
        $template->generateHeader($path);
    ?>

  <body>
    <div class="container-fluid m-0 p-0">
        <?php 
          if(!isset($_POST['filter']))
            $template->generateNavbar($path);
        ?>
    </div>
    <div class="container min-vh-100">
        <?php
          if(isset($_GET['code']))
          {
            $response->getOrderCode($_GET['code']);
          }
        ?>
      <div class="row">
        <div class="col-md-12">
          <h1 class="pt-5 pb-3 text-center">KATALOG</h1>
          <?php
            if(isset($_GET['searchLocation'])){
              $open = $_GET['open_time'];
              $close = $_GET['close_time'];
              $now = date("H:i");    
              $time = strtotime($now);

              if(($time <= $open || $time >= $close) && $account->getRole() == "Administrator")
              {
                setcookie("Location", null, time()-3600, '/', NULL, 0);
                header("Location: https://$_SERVER[HTTP_HOST]/pizzeria/index.php?error=11");
              }
              else
              {
                if(isset($_COOKIE["Location"])){
                  setcookie("cart", "", time() - 3600, '/', NULL, 0);
                  Header("Location: catalog.php");
                }

                $locationArray = array(
                  "city" => $_GET['city'],
                  "street" => $_GET['street'],
                  "street_number" => $_GET['street_number']
                );

                $location [] = $locationArray;
                $locationArray = json_encode($location, JSON_UNESCAPED_UNICODE );
                setcookie("Location", $locationArray, time()+3600,'/', NULL, 0);
                Header("Location: catalog.php");
              }
            }
            else
            {
              if(isset($_COOKIE["Location"])){
                $locations = json_decode($_COOKIE['Location'], true);
                foreach($locations as $keys => $location){
                  echo '
                    <h3 class="text-center">Restauracja</h3>
                    <h4 class="text-center">'.$location['city'].' '.$location['street'].' '.$location['street_number'].'</h4>
                  ';
                }
              }
              else 
              {
                header("Location: https://$_SERVER[HTTP_HOST]/pizzeria/index.php?error=1");
              } 
            }

          ?>
          
          <div class="row justify-content-center">
          <?php
            $template->generateFilter($pizza_categories);
          ?>
          </div>
          <div class="row justify-content-center pizza_container">
            <?php
              if(!isset($_POST['filter']))
              {
                $template->generateCatalog($pizza_list, $size_list, $connect, "");
              }
              else
              {
                if($_POST['filter'] === ""){
                  $template->generateCatalog($pizza_list, $size_list, $connect, "");
                  exit();
                }
                else
                {
                  $template->generateCatalog($pizza_list, $size_list, $connect, $_POST['category_array']);
                  exit();
                }
              }
            ?>
          </div>
        </div>
      </div>
    </div>
    <?php 

      if(!isset($_POST['filter']))
      {
        $template->generateModal("cart", "catalog");
        $template->generateModal("login", "catalog");
        $template->generateModal("register", "catalog");
        $template->generateModal("order", "catalog");
        $template->generateFooter();
      }
      
      if(isset($_POST['registerButton']))
      {
        $account_register = new Account();

        $account_register->createAccount($connect);
      }

      if(isset($_POST['createOrderButton']))
      {
        if(!empty($_COOKIE['cart']))
        {
          $new_order = new Order();
          $new_order->createOrder($connect);
        }
        else
        {
          header("Location: $_SERVER[PHP_SELF]?error=10");
        }
      }
    
      ob_end_flush();
    ?>
    <div class="cart" data-target="#cartModal" data-toggle="modal">
      <span>🛒</span>
    </div>
    <script src="./../js/catalog.js"></script>
  </body>
</html>