<?php

require_once('Cool/BaseController.php');

class AccountController extends BaseController
{
    public function registerAction() {
        $errors_data = [];
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
        && isset($_POST['city']) && isset($_POST['birthdate']) && isset($_POST['password']) && isset($_POST['password-check']) && isset($_POST['register-btn'])) {
            $firstname = htmlentities($_POST['firstname']);
            $lastname = htmlentities($_POST['lastname']);
            $email = htmlentities($_POST['email']);
            $country = htmlentities($_POST['country']);
            $city = htmlentities($_POST['city']);
            $birthdate = htmlentities($_POST['birthdate']);
            $password = $_POST['password'];
            $password_check = $_POST['password-check'];
            $account_manager = new AccountManager();
            $errors = $account_manager->register($firstname, $lastname, $email, $country, $city, $birthdate, $password, $password_check);
            $errors_data = ['errors' => $errors];
             
         }
        return $this->render('register.html.twig', $errors_data);
    }

    public function loginAction() {
        require_once('Model/AccountManager.php');
        $errors_data = [];
        $email = ' ';
        $password = ' ';
        if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['login-btn'])) {
            $email = htmlentities($_POST['email']);
            $password = $_POST['password'];
            $account_manager = new AccountManager();
            $errors = $account_manager->login($email, $password);
            $errors_data = ['errors' => $errors];
        }
        return $this->render('login.html.twig');
    }
}