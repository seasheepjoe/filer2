<?php

require_once('Cool/BaseController.php');

class AccountController extends BaseController
{
    public function registerAction() {
        require_once('Model/AccountManager.php');
        $firstname = ' ';
        $lastname = ' ';
        $email = ' ';
        $country = ' ';
        $city = ' ';
        $birthdate = ' ';
        $password = ' ';
        $password_check = ' ';
        if (isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['country']) 
        && isset($_POST['city']) && isset($_POST['birthdate']) && isset($_POST['password']) && isset($_POST['password-check'])) {
            $firstname = htmlentities($_POST['firstname']);
            $lastname = htmlentities($_POST['lastname']);
            $email = htmlentities($_POST['email']);
            $country = htmlentities($_POST['country']);
            $city = htmlentities($_POST['city']);
            $birthdate = htmlentities($_POST['birthdate']);
            $password = $_POST['password'];
            $password_check = $_POST['password-check'];
            $account_manager = new AccountManager();
            $errors = $account_manager-> register($firstname, $lastname, $email, $country, $city, $birthdate, $password, $password_check);
            $data = ['errors' => $errors];
             
         }
        return $this->render('register.html.twig');
    }
}