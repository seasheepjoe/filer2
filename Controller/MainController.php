<?php

require_once('Cool/BaseController.php');

class MainController extends BaseController
{
    public function homeAction()
    {
        return $this->render('home.html.twig');
    }

    public function registerAction()
    {
        $firstname = ' ';
        $lastname = ' ';
        $email = ' ';
        $country = ' ';
        $city = ' ';
        $birthdate = ' ';
        $password = ' ';
        $password_check = ' ';
        if (isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['email']) && isset($_POST['email'])
         && isset($_POST['country']) && isset($_POST['city']) && isset($_POST['birthdate']) && isset($_POST['password']) && isset($_POST['password-check'])) {
             $firstname = htmlentities($_POST['firstname']);
             $lastname = htmlentities($_POST['lastname']);
             $email = htmlentities($_POST['email']);
             $country = htmlentities($_POST['country']);
             $city = htmlentities($_POST['city']);
             $birthdate = htmlentities($_POST['birthdate']);
             $password = $_POST['password'];
             $password_check = $_POST['password-check'];
             $link = mysqli_connect('localhost', 'root', '', 'filer2');
             $q = "INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `country`, `city`, `birthdate`, `password`) VALUES (NULL, '" . $firstname . "', '" . $lastname . "',
              '" . $email . "', '" . $country . "', '" . $city . "', '" . $birthdate . "', '" . $password . "')";
            $errors = [];

            if (strlen($firstname) <= 2 || strlen($firstname) > 25) {
                $errors[] = 'Firstname must be 3-25 long';
            }

            if (strlen($lastname) <= 2 || strlen($lastname) > 25) {
                $errors[] = 'Lastname must be 3-25 long';
            }

            if (!empty($errors)) {
                foreach($errors as $lol) {
                    echo $lol;
                }
            }else {
                echo 'No errors';
            }
            
         }
        return $this->render('register.html.twig');
    }
}
