<?php
class Template{

    private $path = array();

    private function assignArray(array $array){
        $length = $count($array);
        for($i = 0; $i < $length; $i++){
            $this->$path[i] = $array[$i];
        }
    }
    
    public function generateHeader(array $path){
        echo <<<END
        <head>
            <meta http-equiv="Content-Language" content="pl">
            <meta charset="UTF-8">
            <meta name="referrer" content="no-referrer"/>
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <link rel="stylesheet" href="./$path[0]/css/bootstrap.min.css">
            <link rel="stylesheet" href="./$path[0]/css/styles.css">
            <link rel="stylesheet" href="./$path[0]/css/animate.css">
            <link rel="stylesheet" href="./$path[0]/css/animate_wow.css">
            <link rel="stylesheet" href="./$path[0]/fontawesome/css/all.css">
            <link href='http://fonts.googleapis.com/css?family=Alegreya&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
            <script src="./$path[0]/js/jquery.min.js"></script>
            <script src="./$path[0]/js/bootstrap.bundle.min.js"></script>
            <script src="./$path[0]/js/wow.js"></script>
            <script src="./$path[0]/js/sweetalert.min.js"></script>
            <script src="./$path[0]/js/validate.js"></script>
            <script>new WOW().init();</script>
            <title>Pizzeria</title>
        </head>
        END;
    }

    public function generateSlider(array $path){
        echo<<<END
        <div id="carouselExampleIndicators" class="carousel slide m-0 p-0" data-ride="carousel">
            <div class="carousel-inner">
                <h1 class="display-1 text-center position-relative">Pizzeriano</h1>
                <div class="carousel-item active">
                    <img class="d-block" src="./$path[1]/images/slider/2.jpg" alt="Pizza slide 1">
                    <div class="carousel-caption d-none d-md-block">
                        <h2 class="display-4">Zapraszamy do zapoznania z naszą ofertą</h2>
                        <a class="nav-link text-white" href="./$path[0]/sites/catalog.php"><button class="btn-lg btn-danger mt-3 wow animated flipInX">KATALOG</button></a>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="d-block" src="./$path[1]/images/slider/1.jpg" alt="Pizza slider 2">
                    <div class="carousel-caption d-none d-block">
                        <h2 class="display-4">Tylko dziś!<br> Rabat 20% ceny na każdą pizze z kodem:<br>BON20</h2>
                    </div>
                </div>
            </div>
        </div>
        END;
    }

    public function generateFilter($pizza_categories){
        echo '
        <div class="col-md-6 text-center">
            <h2 class="mt-5 mb-4">Filtry</h2>
            <form id="categories" class="mb-4">';
            foreach($pizza_categories as &$category)
            {
                echo '
                <div class="form-check d-inline-block m-2">
                    <input class="form-check-input" name="category" type="checkbox" id="category'.$category['id'].'" value="'.$category['id'].'">
                    <label class="form-check-label" for="Hot">'.$category['name'].'</label>
                </div>
            ';
          }

        echo '
                <button class="btn btn-primary" type="submit" name="filter" id="filter" > Filtruj </button>
            </form>
        </div>';
    }

    public function generateCatalog($pizza_list, $size_list, $connect, $action){
        if($action === "")
        {
            foreach($pizza_list as &$pizza_name)
            {
                $pizza_product_list = $connect->getPizzaProducts($pizza_name['id']);
                echo '
                <div class="col-md-6">
                    <div class="product_container p-3 text-center">
                        <img class="img-fluid" src="./../../assets/images/catalog/'.$pizza_name['id'].'.jpg" alt="pizza">
                        <h3 class="pt-2 text-left">'.$pizza_name['name'].'</h3>
                        <p class="text-left">';

                        foreach($pizza_product_list as &$products)
                        {
                            echo $products['name']."&nbsp ";
                        }

                        echo '
                        </p>
                        <form id="'.$pizza_name['id'].'" name="pizza_form">
                            <div class="form-group">
                                <select class="form-control w-75" name="pizza_size">';
                                    foreach($size_list as &$val)
                                    {
                                        $new_price = $val['price'] + $pizza_name['sum'];
                                        echo'
                                        <option value="'.$val['size'].'" data-price="'.$new_price.'">'.$val['size'].' ('.$val['cm'].' cm) - '.$new_price.' zł</option>
                                        ';
                                    }
                                
                            echo '</select>
                            </div>
                            <div class="quantity mb-3 text-left">
                                <span class="minus bg-danger" onClick="minus('.$pizza_name['id'].');">-</span>
                                <input id="counter" type="number" class="count count'.$pizza_name['id'].'" name="pizza_quantity" value="1">
                                <span class="plus bg-success" onClick="plus('.$pizza_name['id'].');">+</span>
                            </div>
                            <input type="hidden" name="pizza_id" value="'.$pizza_name['id'].'">
                            <input type="hidden" name="pizza_name" value="'.$pizza_name['name'].'">
                            <button type="submit" class="btn btn-primary">Dodaj do koszyka</button>
                        </form>
                    </div>
                </div>';
            }
        } 
        else 
        {
            foreach($pizza_list as &$pizza_name)
            {
                foreach($action as &$filter)
                {
                    if($filter === $pizza_name['id_category'])
                    {
                    $pizza_product_list = $connect->getPizzaProducts($pizza_name['id']);
                    echo '
                    <div class="col-md-6">
                        <div class="product_container p-3 text-center">
                            <img class="img-fluid" src="./../../assets/images/catalog/'.$pizza_name['id'].'.jpg" alt="pizza">
                            <h3 class="pt-2 text-left">'.$pizza_name['name'].'</h3>
                            <p class="text-left">';

                            foreach($pizza_product_list as &$products)
                            {
                                echo $products['name']."&nbsp ";
                            }

                            echo '
                            </p>
                            <form id="'.$pizza_name['id'].'" name="pizza_form">
                                <div class="form-group">
                                    <select class="form-control w-75" name="pizza_size">';
                                    foreach($size_list as &$val)
                                    {
                                        $new_price = $val['price'] + $pizza_name['sum'];
                                        echo'
                                            <option value="'.$val['size'].'" data-price="'.$new_price.'">'.$val['size'].' ('.$val['cm'].' cm) - '.$new_price.' zł</option>
                                        ';
                                    }
                            echo '</select>
                                </div>
                                <div class="quantity mb-3 text-left">
                                    <span class="minus bg-danger" onClick="minus('.$pizza_name['id'].');">-</span>
                                    <input id="counter" type="number" class="count count'.$pizza_name['id'].'" name="pizza_quantity" value="1">
                                    <span class="plus bg-success" onClick="plus('.$pizza_name['id'].');">+</span>
                                </div>
                                <input type="hidden" name="pizza_id" value="'.$pizza_name['id'].'">
                                <input type="hidden" name="pizza_name" value="'.$pizza_name['name'].'">
                                <button type="submit" class="btn btn-primary">Dodaj do koszyka</button>
                            </form>
                        </div>
                    </div>';
                    }
                }
            }
        }
    }

    public function generateNavbar(array $path){
        echo<<<END
        <nav class="navbar navbar-expand-lg navbar-dark p-3">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse animate__animated animate__fadeInDown" id="navbarNavAltMarkup">
                <div class="navbar-nav mx-auto align-items-center">
                    <a class="navbar-brand ml-3" href="#"><img src="./$path[1]/images/main_page/pizza_logo.png" alt="pizza logo"></a>
                    <a class="nav-link text-white" href="./$path[2]index.php">Strona Główna</a>
                    <a class="nav-link text-white" href="./$path[0]/sites/catalog.php"> 🍕 Katalog</a>
                    <a class="nav-link text-white" href="./$path[0]/sites/contact.php"> 📞 Kontakt</a>
END;
                    if(isset($_SESSION['logged']))
                    {
                        echo '
                        <a class="nav-link text-white" href="./'.$path[0].'/sites/profile.php"> 🔒 Konto </a>
                        <form class="p-0 m-0" id="logoutForm" method="POST"><input class="logout" type="submit" name="logout" value="🔑 WYLOGUJ"></form>
                        ';
                    } 
                    else 
                    {
                      echo'
                        <a class="nav-link text-white" href="#" data-toggle="modal" data-target="#loginModal"> 🔑 Logowanie </a>
                        <a class="nav-link text-white" href="#" data-toggle="modal" data-target="#registerModal"> 🖊️ Rejestracja</a>';
                    }
        echo "  
                    <a class='nav-link text-white' href='#' data-toggle='modal' data-target='#orderModal'> 📝 Sprawdź zamówienie </a>
                </div>
            </div>
        </nav>";
    }

    public function generateFooter(){
        $date = date("Y");
        echo<<<END
            <div class="container-fluid text-center bg-dark ">
                <div class="row p-5 align-items-center">
                    <div class="col-md-6 mx-auto align-items-center text-white">
                        <h5>Wszystkie prawa zastrzeżone &copy Mateusz Cichulski $date</h5>
                    </div>
                </div>
            </div>
        END;
    }

    public function generateModal($name, $script){
        if($name === "login")
        {
            echo'
                <div class="modal fade" id="'.$name.'Modal" tabindex="-1" role="dialog" aria-labelledby="'.$name.'ModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="'.$name.'ModalLabel">Panel Logowania</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="loginForm" action="./'.$script.'.php" method="POST">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <input type="email" class="form-control" name="email" id="emailInput" placeholder="Adres Email" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" name="password" id="passwordInput" placeholder="Hasło" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button id="'.$name.'Button" name="loginButton"  type="submit" class="btn btn-primary">Zaloguj</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            ';
        } 
        elseif ($name === "register") 
        {
            echo'
                <div class="modal fade" id="'.$name.'Modal" tabindex="-1" role="dialog" aria-labelledby="'.$name.'ModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="'.$name.'ModalLabel">Panel Zakładania Konta</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="registerForm" action="./'.$script.'.php" method="POST">
                                <div class="modal-body row">
                                    <div class="col-md-6">
                                        <h4>Dane konta</h4>
                                        <div class="form-group">
                                            <input type="email" class="form-control" name="email" placeholder="Adres Email" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" id="pass" class="form-control" name="password" placeholder="Hasło" onkeyup="checkPass(); return false;" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" id="rpass" class="form-control" name="rpassword" placeholder="Powtórz hasło" onkeyup="checkPass(); return false;" required>
                                        </div>
                                        <label id="error-pass"></label>
                                    </div>
                                    <div class="col-md-6">
                                        <h4>Dane użytkownika</h4>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="fnameInput" name="fname" placeholder="Imię" pattern="[A-Za-ząęźżśóćńłĄĘŹŻŚÓĆŃŁ]{1,}" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="snameInput" name="sname" placeholder="Nazwisko" pattern="[A-Za-ząęźżśóćńłĄĘŹŻŚÓĆŃŁ]{1,}" required>
                                        </div>
                                        <div class="form-group">
                                            <input type="tel"  class="form-control" id="phoneInput" name="phone" placeholder="Numer telefonu np. (+48)" onkeyup="checkPhone(); return false;" pattern="[0-9+]{3}[0-9]{3}[0-9]{3}[0-9]{3}" required>
                                            <label id="error-phone"></label>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="postalCodeInput" name="postalcoderegister" placeholder="Kod pocztowy" onkeyup="checkPostal(); return false;" pattern="[0-9]{2}-[0-9]{3}" required>
                                            <label id="error-postal"></label>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="cityInput" name="city" placeholder="Miasto" pattern="[a-zA-ZąęźżśóćńłĄĘŹŻŚÓĆŃŁ ]{1,}"  required>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="streetInput" name="street" placeholder="Ulica i numer domu/mieszkania" pattern="[a-zA-Z0-9ąęźżśóćńłĄĘŹŻŚÓĆŃŁ/., ]{1,}"  required>
                                        </div>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="rulesCheck" name="rulesCheckbox" required>
                                            <label class="form-check-label" for="rulesCheck">Zapoznałem się i akceptuję warunki korzystania z serwisu</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button id="'.$name.'Button" name="registerButton" type="submit" class="btn btn-primary">Zarejestruj</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            ';
        }
        elseif($name === "cart")
        {
            echo'
            <div class="modal fade" id="'.$name.'Modal" tabindex="-1" role="dialog" aria-labelledby="'.$name.'ModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg " role="document">
                    <div class="modal-content">
                        <div class="modal-header ">
                            <h5 class="modal-title p-2" id="'.$name.'ModalLabel">Koszyk</h5>
                            <form id="clearForm" name="clearCart"><button type="submit" class=" p-2 ml-3 mt-1 btn btn-danger">Wyczyść</button></form>
                            <div class="ml-auto p-2">
                                <button onClick="previous();" type="button" class="btn btn-primary modal-btn-previous" data-orientation="previous">Wróć</button>
                                <button onClick="next();" type="button" class="btn btn-success modal-btn-next" data-orientation="next">Dalej</button>
                            </div>
                            <button type="button" class="close m-0" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body cart-body p-0">
                            <div class="step-1" id="cartModal2" >';
                            if(isset($_COOKIE["cart"]))
                            {
                                $sum = 0;
                                $lp = 0;
                                $cookie_data = stripslashes($_COOKIE['cart']);
                                $cart = json_decode($cookie_data, true);
                                echo "
                                <div class='table-responsive' id='cartDetails'>
                                    <table class='table' class='text-center'>
                                        <thead>
                                            <tr class='text-center'>
                                                <th scope='col'>LP</th>
                                                <th scope='col'>Obrazek</th>
                                                <th scope='col'>Nazwa</th>
                                                <th scope='col'>Rozmiar</th>
                                                <th scope='col'>Cena</th>
                                                <th scope='col'>Sztuk</th>
                                                <th scope='col'></th>
                                            </tr>
                                        </thead>
                                        <tbody class='text-center'>";
                                        foreach($cart as $keys => $values)
                                        {
                                            $lp++;
                                            echo '
                                            <tr>
                                                <th scope="row" class="align-middle">'.$lp.'</th>
                                                <td class="w-25 align-middle" scope="row"><img style="width:200px; height:100px; padding:10px;" src="./../../assets/images/catalog/'.$values['pizza_id'].'.jpg" alt="pizza"></td>
                                                <td class="align-middle" scope="row">'.$values['pizza_name'].'</td>
                                                <td class="align-middle" scope="row">'.$values['pizza_size'].'</td>
                                                <td class="align-middle" scope="row">'.$values['pizza_price'].'</td>
                                                <td class="align-middle" scope="row">'.$values['pizza_quantity'].'</td>
                                                <td class="align-middle" scope="row">
                                                <form id="'.$values['pizza_id'].$values['pizza_size'].'" name="removeFromCartForm" method="POST">
                                                    <input name="pizza_info" type="hidden" data-size="'.$values['pizza_size'].'" value="'.$values["pizza_id"].'">
                                                    <button id="removeItem'.$values['pizza_id'].'" type="button" class="btn btn-danger removeItem">Usuń</button>
                                                </form>
                                                </td>
                                            </tr>';
                                            $sum += $values['pizza_price'];
                                        }
                                            echo "
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td>Suma: $sum zł</td>
                                            </tr>";
                            }
                                echo '  </tbody>
                                    </table>
                                </div>
                            </div>';
                        if(isset($_SESSION['logged']))
                        {
                            echo '
                            <form id="orderForm" name="orderForm" action="./'.$script.'.php" method="POST">
                                <div class="step-2 p-4">                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4>Dane Personalne</h4>
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="fname" placeholder="Imię*" pattern="[A-Za-ząęźżśóćńłĄĘŹŻŚÓĆŃŁ]{1,}"  value="'.$_SESSION['account']->getFname().'" required>
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="sname" placeholder="Nazwisko*" pattern="[A-Za-ząęźżśóćńłĄĘŹŻŚÓĆŃŁ]{1,}"  value="'.$_SESSION['account']->getSname().'" required>
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="phone" placeholder="Numer telefonu* (+XXXXXXXXXXX)" pattern="[0-9+]{3}[0-9]{3}[0-9]{3}[0-9]{3}" value="'.$_SESSION['account']->getPhone().'" required>
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" type="email" name="email" placeholder="twojemail@email.com*" value="'.$_SESSION['account']->getEmail().'" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h4>Dane zamówienia</h4>
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="city" pattern="[a-zA-ZąęźżśóćńłĄĘŹŻŚÓĆŃŁ ]{1,}" placeholder="Miasto*" value="'.$_SESSION['account']->getCity().'" required>
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="street" placeholder="Ulica i numer domu/mieszkania*" pattern="[a-zA-Z0-9ąęźżśóćńłĄĘŹŻŚÓĆŃŁ/., ]{1,}" value="'.$_SESSION['account']->getStreet().'" required>
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="postalcode" placeholder="Kod pocztowy*" pattern="[0-9]{2}-[0-9]{3}" value="'.$_SESSION['account']->getPostalCode().'" required>
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="comment" placeholder="Komentarz do zamówienia (opcjonalne)" pattern="[a-zA-Z0-9ąęźżśóćńłĄĘŹŻŚÓĆŃŁ,.-!@#$%^&*()![]{}:; ]{1,}">
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="companyname" pattern="[a-zA-Z0-9ąęźżśóćńłĄĘŹŻŚÓĆŃŁ,.-!@#$%^&*()![]{}:; ]{1,}" placeholder="Nazwa firmy (opcjonalne)">
                                            </div>
                                            <h5>Metoda Płatności</h5>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="pmethod" id="card" value="card" checked>
                                                <label class="form-check-label" for="card">
                                                Kartą przy odbiorze
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="pmethod" id="cash" value="cash">
                                                <label class="form-check-label" for="cash">
                                                Gotówka
                                                </label>
                                            </div>
                                            <div class="form-group pt-2">
                                                <p id="codeInfo" class="p-1"></p>
                                                <input class="form-control pcode" class="text" name="pcode" pattern="[a-zA-Z0-9ąęźżśóćńłĄĘŹŻŚÓĆŃŁ]{1,}" placeholder="Kod promocyjny (opcjonalne)">
                                            </div>
                                            <input class="btn btn-danger" type="reset" value="Wyczyść">
                                            <button id="'.$name.'Button" name="createOrderButton" type="submit" class="btn btn-primary">Zamawiam</button>
                                        </div>
                                    </div>
                                </div>
                            </form>';
                            }
                            else
                            {
                            echo'
                            <form id="orderForm" name="orderForm" action="./'.$script.'.php" method="POST">
                                <div class="step-2 p-4">                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4>Dane Personalne</h4>
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="fname" pattern="[A-Za-ząęźżśóćńłĄĘŹŻŚÓĆŃŁ]{1,}" placeholder="Imię*" required>
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="sname" pattern="[A-Za-ząęźżśóćńłĄĘŹŻŚÓĆŃŁ]{1,}" placeholder="Nazwisko*" required>
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" type="tel" name="phone" pattern="[0-9+]{3}[0-9]{3}[0-9]{3}[0-9]{3}" placeholder="Numer telefonu* (+XXXXXXXXXXX)" required>
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" type="email" name="email" placeholder="twojemail@email.com*" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h4>Dane zamówienia</h4>
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="city" pattern="[a-zA-ZąęźżśóćńłĄĘŹŻŚÓĆŃŁ ]{1,}" placeholder="Miasto*" required>
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="street" placeholder="Ulica i numer domu/mieszkania*" pattern="[a-zA-Z0-9ąęźżśóćńłĄĘŹŻŚÓĆŃŁ/., ]{1,}" required>
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="postalcode" pattern="[0-9]{2}-[0-9]{3}" placeholder="Kod pocztowy*" required>
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="comment" pattern="[a-zA-Z0-9ąęźżśóćńłĄĘŹŻŚÓĆŃŁ,.-!@#$%^&*()![]{}:; ]{1,}" placeholder="Komentarz do zamówienia (opcjonalne)">
                                            </div>
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="companyname" pattern="[a-zA-Z0-9ąęźżśóćńłĄĘŹŻŚÓĆŃŁ,.-!@#$%^&*()![]{}:; ]{1,}" placeholder="Nazwa firmy (opcjonalne)">
                                            </div>
                                            <h5>Metoda Płatności</h5>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="pmethod" id="card" value="card" checked>
                                                <label class="form-check-label" for="card">
                                                Kartą przy odbiorze
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="pmethod" id="cash" value="cash">
                                                <label class="form-check-label" for="cash">
                                                Gotówka
                                                </label>
                                            </div>
                                            <div class="form-group pt-2">
                                                <p id="codeInfo" class="p-1"></p>
                                                <input class="form-control pcode" class="text" name="pcode" pattern="[a-zA-Z0-9ąęźżśóćńłĄĘŹŻŚÓĆŃŁ]{1,}" placeholder="Kod promocyjny (opcjonalne)">
                                            </div>
                                            <input class="btn btn-danger" type="reset" value="Wyczyść">
                                            <button id="'.$name.'Button" name="createOrderButton" type="submit" class="btn btn-primary">Zamawiam</button>
                                        </div>                                        
                                    </div>
                                </div>
                            </form>';
                            }
                        echo'
                        </div>
                    </div>
                </div>
            </div>';
        }
        elseif($name === "order"){
            echo'
                <div class="modal fade" id="'.$name.'Modal" tabindex="-1" role="dialog" aria-labelledby="'.$name.'ModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="'.$name.'ModalLabel">Sprawdź zamówienie</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="pl-3 pr-3 pt-3" id="orderInfo"></div>
                            <form class="p-0 m-0" id="loginForm" action="./'.$script.'.php" method="POST">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <input type="number" class="form-control order_number" name="order_number" id="orderInput" placeholder="Numer zamówienia" required>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            ';
        }
    }

    public function generatePopup($message, $type){
        if($type == "error")
        echo '<div class="popup-error">
                <p>'.$message.'</p>
            </div>';
        elseif($type=="success"){
            echo '<div class="popup-success">
                <p>'.$message.'</p>
            </div>';
        }
    }
    

    public function generateProfilePersonal($name, $account, $personal_order){
        switch($name)
        {
            case "personal":
            echo '
                <div class="tab-pane fade show active shadow p-3" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
                    <p><b>Imię:</b> '.$account->getFname().'</p>
                    <p><b>Nazwisko:</b> '.$account->getSname().'</p>
                    <p><b>Email:</b> '.$account->getEmail().'</p>
                    <p><b>Numer telefonu:</b> '.$account->getPhone().'</p>
                    <p><b>Miasto:</b> '.$account->getCity().'</p>
                    <p><b>Ulica i numer mieszkania/domu:</b> '.$account->getStreet().'</p>
                </div>';
            break;
            case "orders":

            echo '<div class="tab-pane fade shadow p-3" id="list-orders" role="tabpanel" aria-labelledby="list-orders-list">';
            foreach($personal_order as $order)
            {
                $delivery_id = $order['delivery_id'];
                $dateYMD = $order['date_ymd'];
                $city = $order['city'];
                $street = $order['street'];
                $postalcode = $order['postalcode'];
                $date = $order['date_time'];
                $comment = $order['comment'];
                $delivery_code = $order['delivery_code'];
                $count = $order['count'];
                $status = $order['status'];
                $sum = $order['sum'];
             
                echo <<<END
                <div id="accordion">
                <div class="card">
                    <div class="card-header" id="heading$delivery_id">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse$delivery_id" aria-expanded="false" aria-controls="collapse$delivery_id">
                            Zamówienie $delivery_id / $dateYMD
                            </button>
                        </h5>
                    </div>
                    <div id="collapse$delivery_id" class="collapse" aria-labelledby="heading$delivery_id" data-parent="#accordion">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr class="text-center">
                                            <th scope="col">Nazwa</th>
                                            <th scope="col">Rozmiar</th>
                                            <th scope="col">Cena / szt.</th>
                                            <th scope="col">Ilość</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                END;
                                    foreach($order['items_order'] as $item)
                                    {
                                        $pizza_name = $item['pizza_name'];
                                        $pizza_size = $item['pizza_size'];
                                        $pizza_count = $item['pizza_count'];
                                        $pizza_piece = $item['pizza_piece'];
                echo <<<END
                                        <tr class="text-center">
                                            <td>$pizza_name</td>
                                            <td>$pizza_size</td>
                                            <td>$pizza_piece</td>
                                            <td>$pizza_count</td>
                                        </tr>
                END;
                                    }
                echo <<<END
                                    </tbody>
                                </table>
                                <p><b>Miasto:</b> $city</p>
                                <p><b>Ulica i numer domu/mieszkania:</b> $street</p>
                                <p><b>Kod pocztowy:</b> $postalcode</p>
                                <p><b>Status:</b> $status</p>
                                <p><b>Data i godzina:</b> $date</p>
                                <p><b>Suma:</b> $sum zł</p>
                                <p><b>Kod zamówienia:</b> $delivery_code</p>
                                <p><b>Komentarz:</b> $comment</p>
                            </div>
                        </div>
                    </div>
                </div></div>
                END;
            }
            echo '</div>';
            break;
        }
    }

    public function generateProfileStatic($name){
        switch($name)
        {
            case "menu":
                echo '<button class="btn btn-primary m-2" type="button" data-toggle="collapse" data-target="#collapseRecipes" aria-expanded="false" aria-controls="collapseRecipes">
                        Zarządzaj przepisami
                    </button>
                    <button class="btn btn-primary m-2" type="button" data-toggle="collapse" data-target="#collapseProducts" aria-expanded="false" aria-controls="collapseProducts">
                        Zarządzaj produktami
                    </button>
                    <button class="btn btn-primary m-2" type="button" data-toggle="collapse" data-target="#collapseCategories" aria-expanded="false" aria-controls="collapseCategories">
                        Zarządzaj kategoriami
                    </button>
                    <button class="btn btn-primary m-2" type="button" data-toggle="collapse" data-target="#collapseOrders" aria-expanded="false" aria-controls="collapseOrders">
                        Zarządzaj zamówieniami
                    </button>
                    <button class="btn btn-primary m-2" type="button" data-toggle="collapse" data-target="#collapseCakes" aria-expanded="false" aria-controls="collapseCakes">
                        Zarządzaj ciastami
                    </button>
                    <button class="btn btn-primary m-2" type="button" data-toggle="collapse" data-target="#collapsePromotions" aria-expanded="false" aria-controls="collapsePromotions">
                        Zarządzaj kodami promocyjnymi
                    </button>';
            break;
        }
    }

    public function generateProfileAdmin($name, $connect, $order_array){
        switch($name){
            case "recipes":
            echo <<<END
                        <div class="collapse" id="collapseRecipes" data-parent="#list-administrator">
                            <div class="card card-body">
                                <h3 class="text-center">Panel zarządzania przepisami</h3>
                                <form id="addPizzaForm" name="addPizzaForm" class="w-50 p-2" enctype="multipart/form-data">
                                    <h4>Dodawanie przepisu</h4>
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="pname" id="pname" placeholder="Nazwa pizzy"  required>
                                    </div>
                                    <div class="form-group">
                                        <label>(Trzymaj CTRL przy zaznaczaniu wielu elementów)</label>
                                        <select multiple class="form-control" id="selectRecipeProducts" name="selectRecipeProducts[]" required>
            END;
                                        $productList = $connect->getAllProducts();
                                        $pizzaList = $connect->getAllPizza();

                                        foreach($productList as $product){
                                            echo '<option value="'.$product['id'].'">'.$product['name'].'</option>';
                                        }
            echo <<<END
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Wybierz kategorię</label>
                                        <select class="form-control" id="selectRecipeCategory" name="selectRecipeCategory" required>
            END;
                                        $categoriesList = $connect->getCategories();
                                        foreach($categoriesList as $category){
                                            echo '<option value="'.$category['id'].'">'.$category['name'].'</option>';
                                        }
            echo <<<END
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="pizzaPicture">Obraz Pizzy</label>
                                        <input type="file" name="pizzaPicture" class="form-control-file" id="pizzaPicture" accept=".jpg" required>
                                    </div>
                                    <button class="btn btn-danger" type="reset">Wyczyść</button>
                                    <button type="submit" id="addPizzaButton" class="btn btn-info">Dodaj</button>
                                </form>
                                <form id="deletePizzaForm" name="deletePizzaForm" class="w-50 p-2">
                                    <h4>Usuwanie przepisu</h4>
                                    <div class="form-group">
                                        <select class="form-control" id="deletePizzaSelection" required>
            END;
                                        foreach($pizzaList as $pizza){
                                            echo '<option value="'.$pizza['id'].'">'.$pizza['name'].'</option>';
                                        }
            echo <<<END
                                        </select>
                                    </div>
                                    <button type="submit" id="deletePizzaButton" class="btn btn-info">Usuń</button>
                                </form>
                            </div>
                        </div>
            END;
            break;
            case "products":
            echo <<<END
                        <div class="collapse" id="collapseProducts" data-parent="#list-administrator">
                            <div class="card card-body">
                                <h3 class="text-center">Panel zarządzania produktami</h3>
                                <form id="addProductForm" name="addProductForm" class="w-50 p-2">
                                    <h4>Dodawanie produktu</h4>
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="prname" id="prname" placeholder="Nazwa produktu" required>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="prprice" id="prprice" placeholder="Cena produktu (PLN)" required>
                                    </div>
                                    <button class="btn btn-danger" type="reset">Wyczyść</button>
                                    <button type="submit" id="addProductButton" class="btn btn-info">Dodaj</button>
                                </form>
                                <form id="deleteProductForm" name="deleteProductForm" class="w-50 p-2">
                                    <h4>Usuwanie produktu</h4>
                                    <div class="form-group">
                                        <select class="form-control" id="deleteProductSelection" required>
            END;
                                       foreach($productList as $product){
                                         echo '<option value="'.$product['id'].'">'.$product['name'].'</option>';
                                       }
            echo <<<END
                                        </select>
                                    </div>
                                    <button type="submit" id="deleteProductButton" class="btn btn-info">Usuń</button>
                                </form>
                            </div>
                        </div>
            END;
            break;
            case "categories":
                echo <<<END
                            <div class="collapse" id="collapseCategories" data-parent="#list-administrator">
                                <div class="card card-body">
                                    <h3 class="text-center">Panel zarządzania kategoriami</h3>
                                    <form id="addCategoryForm" name="addCategoryForm" class="w-50 p-2">
                                        <h4>Dodawanie kategorii</h4>
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="catname" id="catname" placeholder="Nazwa kategorii" required>
                                        </div>
                                        <button class="btn btn-danger" type="reset">Wyczyść</button>
                                        <button type="submit" id="addCategoryButton" class="btn btn-info">Dodaj</button>
                                    </form>
                                    <form id="deleteCategoryForm" name="deleteCategoryForm" class="w-50 p-2">
                                        <h4>Usuwanie kategorii</h4>
                                        <div class="form-group">
                                            <select class="form-control" id="deleteCategory" required>
                END;
                                            $categoriesList = $connect->getCategories();
                                            foreach($categoriesList as $category){
                                                echo '<option value="'.$category['id'].'">'.$category['name'].'</option>';
                                            }
                echo <<<END
                                            </select>
                                        </div>
                                        <button type="submit" id="deleteCategoryButton" class="btn btn-info">Usuń</button>
                                    </form>
                                </div>
                            </div>
                END;
                break;
                case "cakes":
                echo <<<END
                            <div class="collapse" id="collapseCakes" data-parent="#list-administrator">
                                <div class="card card-body">
                                    <h3 class="text-center">Panel zarządzania ciastami</h3>
                                    <form id="addCakeForm" name="addCakeForm" class="w-50 p-2">
                                        <h4>Dodawanie ciasta</h4>
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="csize" id="csize" placeholder="Określenie rozmiaru (np. M)" required>
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="cprice" id="cprice" placeholder="Cena" required>
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="cm" id="cm" placeholder="Rozmiar (CM)" required>
                                        </div>
                                        <button class="btn btn-danger" type="reset">Wyczyść</button>
                                        <button type="submit" id="addCakeButton" class="btn btn-info">Dodaj</button>
                                    </form>
                                    <form id="deleteCakeForm" name="deleteCakeForm" class="w-50 p-2">
                                        <h4>Usuwanie Ciasta</h4>
                                        <div class="form-group">
                                            <select class="form-control" id="deleteCake" required>
                END;
                                                $cakesList = $connect->getPizzaCakes();
                                                foreach($cakesList as $cake){
                                                    echo '<option value="'.$cake['id'].'">'.$cake['size'].'</option>';
                                                }
                echo <<<END
                                            </select>
                                        </div>
                                        <button type="submit" id="deleteCakeButton" class="btn btn-info">Usuń</button>
                                    </form>
                                </div>
                            </div>
                END;
                break;
                case "promotions":
                echo <<<END
                        <div class="collapse" id="collapsePromotions" data-parent="#list-administrator">
                            <div class="card card-body">
                              <h3 class="text-center">Panel zarządzania kodami promocyjnymi</h3>
                              <form id="addPromotionForm" name="addPromotionForm" class="w-50 p-2">
                                  <h4>Dodawanie kodu promocyjnego</h4>
                                  <div class="form-group">
                                    <input class="form-control" type="text" name="pcode" id="pcode" placeholder="Kod promocyjny" required>
                                  </div>
                                  <div class="form-group">
                                    <input class="form-control" type="number" name="percentage" id="percentage" placeholder="Procent" required>
                                  </div>
                                  <button class="btn btn-danger" type="reset">Wyczyść</button>
                                  <button type="submit" id="addPromotionButton" class="btn btn-info">Dodaj</button>
                              </form>
                              <form id="deletePromotionForm" name="deletePromotionForm" class="w-50 p-2">
                                <h4>Usuwanie kodu promocyjnego</h4>
                                <div class="form-group">
                                    <select class="form-control" id="deletePromotion" required>
                END;
                                    $promotionsList = $connect->getPromotionCodes();
                                    foreach($promotionsList as $promotion){
                                        echo '<option value="'.$promotion['id'].'">'.$promotion['code'].' - '.$promotion['percentage'].'%</option>';
                                    }
                echo <<<END
                                    </select>
                                </div>
                                <button type="submit" id="deletePromotionButton" class="btn btn-info">Usuń</button>
                              </form>
                            </div>
                        </div>
                END;
                break;
                case "orders":
                echo <<<END
                <div class="collapse" id="collapseOrders" data-parent="#list-administrator">
                    <div class="card card-body">
                        <h3 class="text-center">Panel zarządzania Zamówieniami</h3>
                END;

                foreach($order_array as $order)
                {
                    $delivery_id = $order['delivery_id'];
                    $dateYMD = $order['date_ymd'];
                    $city = $order['city'];
                    $street = $order['street'];
                    $postalcode = $order['postalcode'];
                    $date = $order['date_time'];
                    $comment = $order['comment'];
                    $delivery_code = $order['delivery_code'];
                    $count = $order['count'];
                    $status = $order['status'];
                    $sum = $order['sum'];
             
                echo <<<END
                        <div id="accordion-admin">
                            <div class="card">
                                <div class="card-header" id="headinga$delivery_id">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapsea$delivery_id" aria-expanded="false" aria-controls="collapsea$delivery_id">
                                        Zamówienie $delivery_id / $dateYMD
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapsea$delivery_id" class="collapse" aria-labelledby="headinga$delivery_id" data-parent="#accordion-admin">
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th scope="col">Nazwa</th>
                                                        <th scope="col">Rozmiar</th>
                                                        <th scope="col">Cena / szt.</th>
                                                        <th scope="col">Ilość</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                END;
                                                foreach($order['items_order'] as $item)
                                                {
                                                    $pizza_name = $item['pizza_name'];
                                                    $pizza_size = $item['pizza_size'];
                                                    $pizza_count = $item['pizza_count'];
                                                    $pizza_piece = $item['pizza_piece'];
                echo <<<END
                                                    <tr class="text-center">
                                                        <td>$pizza_name</td>
                                                        <td>$pizza_size</td>
                                                        <td>$pizza_piece</td>
                                                        <td>$pizza_count</td>
                                                    </tr>
                END;
                                                }
                echo <<<END
                                                </tbody>
                                            </table>
                                            <p><b>Miasto:</b> $city</p>
                                            <p><b>Ulica i numer domu/mieszkania:</b> $street</p>
                                            <p><b>Kod pocztowy:</b> $postalcode</p>
                                            <form id="editOrderStatus$delivery_id" name="editOrderStatus">
                                                <input type="hidden" name="order_id" value="$delivery_id">
                                                <div class="form-group d-inline-block">
                                                    <label class="d-inline-block"><b>Obecny status: </b>$status</label>
                                                    <select class="form-control d-inline-block" name="orderStatus">
                                                        <option>W trakcie realizacji</option>
                                                        <option>Zakończone</option>
                                                        <option>Odrzucone</option>
                                                    </select>
                                                </div>
                                                <button class="btn btn-info d-inline-block ml-3" type="submit" id="editOrderStatusButton$delivery_id">Edytuj</button>
                                            </form>
                                            <p><b>Data i godzina:</b> $date</p>
                                            <p><b>Suma:</b> $sum zł</p>
                                            <p><b>Kod zamówienia:</b> $delivery_code</p>
                                            <p><b>Komentarz:</b> $comment</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                END;
                }
                echo <<<END
                    </div>
                </div>
                END;
                break;
        }
    }


}

?>