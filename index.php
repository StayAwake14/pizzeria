<?php 
  ob_start();
  session_start();
  include_once("./lib/template/template.php");
  include_once("./lib/template/db.php");
  include_once("./lib/template/account.php");
  include_once("./lib/template/response.php");
  $template = new Template();
  $connect = new DB();
  $response = new Response();
  $path = array("lib", "assets", "");
  
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
            $template->generateSlider($path);
        ?>
    </div>

    <?php
      if(isset($_GET['error']))
      {
      $response->getErrorResponse($_GET['error'], $template);
      }
      if(isset($_GET['success']))
      {
        $response->getSuccesResponse($_GET['success'], $template);
      }
    ?>
    <div class="container-fluid text-center">
      <div class="row align-items-center pt-5 pb-5 border-bottom">
        <div class="col-md-6 p-5 mx-auto align-items-center">
            <h1>NASZA OFERTA</h1>
            <p class="text-content text-justify pt-2">
              Najlepsza Margherita, duża Capriciosa czy Wegetariańska na cienkim cieście? Wszystkie przygotowywane przez nas potrawy są skomponowane z najlepszej jakości składników. Nasza oferta doskonale nadaje się na lunch w pracy, rodzinny obiad czy kolację z przyjaciółmi. Oprócz pysznej pizzy w menu znajdziesz m.in. sałatki ze świeżych warzyw, oryginalne startery i desery, że palce lizać!
              <br>
              <div class="row justify-content-center mt-5">
                <div class="col-md-12 wow bounce text-content">
                  <i class="fas fa-pizza-slice fa-3x d-inline-block mr-5 text-warning"></i>
                  <i class="fas fa-ice-cream fa-3x d-inline-block mr-5 text-primary"></i>
                  <i class="fas fa-mug-hot fa-3x d-inline-block mr-5 text-info"></i>
                  <i class="fas fa-carrot fa-3x text-green d-inline-block text-success"></i>
                  <br>
                  <span class="d-inline-block mr-5 mt-2">Pizza</span>
                  <span class="d-inline-block mr-5">Desery</span>
                  <span class="d-inline-block mr-5">Napoje</span>
                  <span class="d-inline-block">Sałatki</span>
                </div>
              </div>
            </p>
        </div>
      </div>
      <div id="selectRestaurant" class="row p-5 align-items-center parallax">
        <div class="col-md-6 text-white">
          <h1 class="pb-5 text-shadow wow slideInLeft">Wybierz restaurację</h1>
            <form class="wow slideInLeft" method="GET" action="./lib/sites/catalog.php">
              <div class="form-group">
                <div class="col-md-6 mx-auto">
                  <select class="form-control" list="cities" name="city" id="city">
                    <?php

                        $locationList = $connect->getLocationList();
                        $now = date("H:i");    
                        $time = strtotime($now);
                        
                        foreach($locationList as $location)
                        {
                          $open_time = strtotime(date('G:i', strtotime($location['open_time'])));
                          $close_time = strtotime(date('G:i', strtotime($location['close_time'])));
                          if($time >= $open_time && $time <= $close_time)
                          {
                            echo '<option value="'.$location['city'].'" data-street="'.$location['street'].'" data-snumber="'.$location['street_number'].'"data-open="'.$open_time.'"data-close="'.$close_time.'">'.$location['city'].'</option>';
                          }
                        }
                      ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-6 mx-auto">
                  <input type="text" class="form-control" name="street" id="street" placeholder="Nazwa Ulicy" readonly>
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-6 mx-auto">
                  <input type="text" class="form-control" name="street_number" id="street_number" placeholder="Numer domu" readonly>
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-6 mx-auto">
                  <input type="hidden" class="form-control" name="open_time" id="open_time" readonly>
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-6 mx-auto">
                  <input type="hidden" class="form-control" name="close_time" id="close_time" readonly>
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-6 mx-auto">
                  <button type="submit" name="searchLocation" class="btn-lg btn-success">Szukaj</button>
                </div>
              </div>
            </form>
        </div>
        <div class="col-md-6 align-items-center text-white">
            <h1 class="p-0 m-0 wow fadeIn">ZNAJDŹ NASZĄ RESTARTUACJĘ W POBLIŻU</h1>
        </div>
      </div>
    </div>

    <div class="container-fluid pb-5 pt-5 text-center ">
      <div class="row align-items-center">
        <div class="col-md-6 mx-auto p-5 align-items-center text-dark">
          <h1> O NAS </h1>
          <p class="text-content">Dobra pizzeria to nie tylko wysokiej jakości dania. To także wyjątkowa więź między obsługą a Klientami. Wkładamy serce w przygotowywane przez nas potrawy, dzięki czemu nasi Klienci ciągle do nas wracają. Jesteśmy Przyjaciółmi od pizzy, ponieważ nasze restauracje tworzone są przez przyjaciół dla przyjaciół.</p>
        </div>
      </div>
    </div>

    <?php 
      $template->generateModal("login", "index");
      $template->generateModal("register", "index");
      $template->generateModal("order", "index");
      $template->generateFooter();

      if(isset($_POST['registerButton']))
      {
        $account_register = new Account();
        $account_register->createAccount($connect);
      }

      if(isset($_POST['loginButton']))
      {
        $account_login = new Account();
        $account_login->accountLogin($connect);
      }
      ob_end_flush();
    ?>
  <script src="./lib/js/catalog.js"></script>
  <script>
    $('#city').on('change', function(){
      $("#street").val($(this).parent().find('option:selected').data('street'));
      $("#street_number").val($(this).parent().find('option:selected').data('snumber'));
      $("#open_time").val($(this).parent().find('option:selected').data('open'));
      $("#close_time").val($(this).parent().find('option:selected').data('close'));
    });
    $('#city').change();
  </script>
  </body>
</html>