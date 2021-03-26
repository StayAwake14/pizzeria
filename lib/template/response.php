<?php

class Response{

    public function getErrorResponse($type, $template)
    {
        switch($type)
        {
            case 1:
                $template->generatePopup("Nie wybrano restauracji.<script>$(document).ready(function () {
                                            $('html, body').animate({
                                                scrollTop: $('#selectRestaurant').offset().top
                                            }, 'slow');
                                        });
                </script> ", "error");
            break;
            case 2:
                $template->generatePopup("Podano złe hasło.", "error");
            break;
            case 3:
                $template->generatePopup("Podane konto nie istnieje.", "error");
            break;
            case 4:
                $template->generatePopup("Nie mozna utworzyc konta w systemie. Sprawdz poprawnosc danych w formularzu.", "error");
            break;
            case 5:
                $template->generatePopup("Podano niepoprawny email lub hasło", "error");
            break;
            case 6:
                $template->generatePopup("Podane hasła muszą być takie same.", "error");
            break;
            case 7:
                $template->generatePopup("Konto o takim adresie e-mail juz istnieje w serwisie.", "error");
            break;
            case 8:
                $template->generatePopup("Nie znaleziono konta w systemie.", "error");
            break;
            case 9:
                $template->generatePopup("Musisz się zalogować w systemie, aby móc korzystać z tej funkcjonalności.", "error");
            break;
            case 10:
                $template->generatePopup("Nie można zrealizować zamówienia z pustym koszykiem.", "error");
            break;
            case 11:
                $template->generatePopup("Restauracja nieczynna.", "error");
            break;
        }
    }

    public function getSuccesResponse($type, $template)
    {
        switch($type)
        {
            case 1:
                $template->generatePopup("Pomyślnie zalogowano.", "success");
            break;
            case 2:
                $template->generatePopup("Pomyślnie wylogowano.", "success");
            break;
            case 3:
                $template->generatePopup("Pomyślnie utworzono konto", "success");
            break;
            case 4:
                $template->generatePopup("Pomyślnie dodano zamówienie", "success");
            break;
        }
    }

    public function getOrderCode($type)
    {
        echo '<script>swal("Numer zamówienia", "Twój numer zamówienia: '.$type.'. Smacznego!", "warning");</script>';
    }
}



?>