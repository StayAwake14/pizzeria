<?php 

  require_once("./../template/template.php");
  require_once("./../template/db.php");
  require_once("./../template/account.php");
  require_once("./../template/response.php");
  
  session_start();
  ob_start();
  $template = new Template();
  $connect = new DB();
  $response = new Response();
  $path = array("../../lib", "../../assets", "../../");
 // unset($_COOKIE['cart']);

  if(isset($_POST['loginButton']))
  {
    $account_login = new Account();
    $account_login->accountLogin($connect);
  }

  if(isset($_POST['logout']))
  {
    $account = $connect->logout();
  }

  if(isset($_POST['registerButton']))
  {
    $account_register = new Account();
    $account_register->createAccount($connect);
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
      $template->generateHeader($path);
    ?>

  <body>
    <div class="container-fluid m-0 p-0">
        <?php 
            $template->generateNavbar($path);
        ?>
    </div>
    <div class="container min-vh-100">
        <h1 class="pt-5 pb-3 text-center">Kontakt</h1>
        <div class="row">
            <?php
                $locationList = $connect->getLocationList();
                foreach($locationList as $location){

                    $open_time = date('G:i', strtotime($location['open_time']));
                    $close_time = date('G:i', strtotime($location['close_time']));
                    
                    echo '
                    <div class="col-md-3 text-center">
                        <div class="card text-black bg-light mb-3 mx-auto" style="max-width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title">'.$location['city'].'</h5>
                                <p class="card-text">Adres: '.$location['street'].'&nbsp'.$location['street_number'].'</p>
                                <p class="card-text">Telefon: '.$location['phone'].'</p>
                                <p class="card-text">Godziny otwarcia:<br> '.$open_time.'-'.$close_time.'</p>
                            </div>
                        </div>
                    </div>
                    ';
                }
            ?>
        </div>
    </div>
    
    <?php 
        $template->generateModal("login", "catalog");
        $template->generateModal("register", "catalog");
        $template->generateModal("order", "profile");
        $template->generateFooter();
    ?>
    <script src="./../js/catalog.js"></script>
  </body>
</html>