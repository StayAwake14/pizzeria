<?php 

  ob_start();
  require_once("./../template/template.php");
  require_once("./../template/db.php");
  require_once("./../template/account.php");
  require_once("./../template/response.php");

  $template = new Template();
  $connect = new DB();
  $response = new Response();
  $order_array = null;
  $personal_orders = null;

  session_start();

  $path = array("../../lib", "../../assets", "../../");
 // unset($_COOKIE['cart']);

 if(!isset($_SESSION['account']))
 {
    header("Location: https://$_SERVER[HTTP_HOST]/pizzeria/index.php?error=9");
 }

  if(isset($_POST['logout']))
  {
      $connect->logout();
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
    <div class="container-fluid d-block min-vh-100">
      <div class="row">
        <div class="col-md-12">
          <h1 class="pt-5 pb-3 text-center">Profil</h1>
          <div class="row justify-content-center pb-5">
            <?php 
              $account = $_SESSION['account'];
            ?>
            <div class="col-md-2 p-0">
              <div class="list-group" id="list-tab" role="tablist">
                <a class="list-group-item list-group-item-action active" id="list-profile-list" data-toggle="list" href="#list-profile" role="tab" aria-controls="profile">Informacje</a>
                <a class="list-group-item list-group-item-action" id="list-orders-list" data-toggle="list" href="#list-orders" role="tab" aria-controls="orders">Zamówienia</a>
                <?php
                  if(($account->getRole()) == "Administrator")
                  {
                    $order_array = $connect->getAllUsersOrders();
                    echo '<a class="list-group-item list-group-item-action" id="list-administrator-list" data-toggle="list" href="#list-administrator" role="tab" aria-controls="administrator">Panel Administratora</a>';
                  }
                ?>
              </div>
            </div>
            <div class="col-md-6 m-0 p-0">
              <div class="tab-content m-0 p-0" id="nav-tabContent">
                <?php
                  $template->generateProfilePersonal("personal", $account, $personal_orders);
                ?>
                
                  <?php 
                    $personal_orders = $connect->getUserOrders($account->getIdAccount());
                    $template->generateProfilePersonal("orders", $account, $personal_orders);
                  ?>
                
                <div class="tab-pane fade shadow p-3" id="list-administrator" role="tabpanel" aria-labelledby="list-administrator-list">
                  <?php
                    $template->generateProfileStatic("menu");
                    $template->generateProfileAdmin("recipes", $connect,  $order_array);
                    $template->generateProfileAdmin("products", $connect,  $order_array);
                    $template->generateProfileAdmin("categories", $connect,  $order_array);
                    $template->generateProfileAdmin("cakes", $connect,  $order_array);
                    $template->generateProfileAdmin("promotions", $connect,  $order_array);
                    $template->generateProfileAdmin("orders", $connect, $order_array);
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php 
      $template->generateModal("login", "catalog");
      $template->generateModal("register", "catalog");
      $template->generateModal("cart", "catalog");
      $template->generateModal("order", "profile");
      $template->generateFooter();

      if(isset($_POST['order_id']))
      {
        $connect->editOrderStatus($_POST['order_id'],$_POST['order_status']);
      }

      if(isset($_POST['cake_add_size']))
      {
        $connect->addCake($_POST['cake_add_size'], $_POST['cake_add_price'], $_POST['cake_add_cm']);
      }

      if(isset($_POST['cake_delete_id']))
      {
        $connect->deleteCake($_POST['cake_delete_id']);
      }

      if(isset($_POST['category_add_name']))
      {
        $connect->addCategory($_POST['category_add_name']);
      }

      if(isset($_POST['category_delete_id']))
      {
        $connect->deleteCategory($_POST['category_delete_id']);
      }

      if(isset($_POST['product_add_name']))
      {
        $connect->addProduct($_POST['product_add_name'], $_POST['product_add_price']);
      }

      if(isset($_POST['product_delete_id']))
      {
        $connect->deleteProduct($_POST['product_delete_id']);
      }

      if(isset($_POST['promotion_code']))
      {
        $connect->addPromotionCode($_POST['promotion_code'], $_POST['percentage']);
      }

      if(isset($_POST['promotion_delete_id']))
      {
        $connect->deletePromotionCode($_POST['promotion_delete_id']);
      }

      if(isset($_POST['station']))
      {
        $connect->addStation($_POST['city'], $_POST['street'], $_POST['street_number'], $_POST['phone'], $_POST['close_time'], $_POST['open_time']);
      }

      if(isset($_POST['station_delete']))
      {
        $connect->deleteStation($_POST['station_delete_id']);
      }

      if(isset($_POST['pname']))
      {
        $connect->addRecipe($_POST['pname'], $_POST['selectRecipeProducts'], $_POST['selectRecipeCategory'], $_FILES);
      }

      if(isset($_POST['pizza_delete_id']))
      {
        $connect->deleteRecipe($_POST['pizza_delete_id']);
      }

    ?>
    <script src="./../js/catalog.js"></script>
    <script src="./../js/admin.js"></script>
  </body>
</html>