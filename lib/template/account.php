<?php

class Account{

    public $fname, $sname, $phone, $city, $street, $id_account, $email, $role, $postalcode;

    public function loadAccount($fname, $sname, $phone, $city, $street, $id_account, $email, $role, $postalcode){
        $this->fname = $fname;
        $this->sname = $sname;
        $this->phone = $phone;
        $this->city = $city;
        $this->street = $street;
        $this->id_account = $id_account;
        $this->email = $email;
        $this->role = $role;
        $this->postalcode = $postalcode;
    }

    public function getFname(){
        return $this->fname;
    }

    public function getSname(){
        return $this->sname;
    }

    public function getPhone(){
        return $this->phone;
    }

    public function getCity(){
        return $this->city;
    }

    public function getStreet(){
        return $this->street;
    }

    public function getIdAccount(){
        return $this->id_account;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getRole(){
        return $this->role;
    }

    public function getPostalCode(){
        return $this->postalcode;
    }

    public function createAccount($connect){
        $email = htmlentities(trim($_POST['email']));
        $password = htmlentities(trim($_POST['password']));
        $rpassword = htmlentities(trim($_POST['rpassword']));
        $fname = trim($_POST['fname']);
        $sname = trim($_POST['sname']);
        $phone = trim($_POST['phone']);
        $city = trim($_POST['city']);
        $street = trim($_POST['street']);
        $postalcode = htmlentities($_POST['postalcoderegister']);
        $service_statement = $_POST['rulesCheckbox'];
        if($password === $rpassword && isset($service_statement)){
          if($connect->checkIfAlreadyRegistered($email) < 1)
          {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $connect->register($email, $hash, $fname, $sname, $phone, $city, $street, $postalcode);
          } 
          else 
          {
            header("Location: $_SERVER[HTTP_REFERER]?error=7");
          }
        } 
        else 
        {
            header("Location: $_SERVER[HTTP_REFERER]?error=6");
        }
    }

    public function accountLogin($connect)
    {
        $email = htmlentities(trim($_POST['email']));
        $password = htmlentities(trim($_POST['password']));
        $connect->login($email, $password);
    }
}


?>