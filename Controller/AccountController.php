<?php

require_once('Cool/BaseController.php');

class AccountController extends BaseController
{
    public function registerAction()
    {
        require_once('init.php');
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
             $q = "INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `country`, `city`, `birthdate`, `password`) VALUES (NULL, '" . $firstname . "', '" . $lastname . "',
              '" . $email . "', '" . $country . "', '" . $city . "', '" . $birthdate . "', '" . $password . "')";
            $errors = [];

            $isEmailUsed = "SELECT `email` FROM `users` WHERE `email` = '$email'";
            $result = mysqli_query($link, $isEmailUsed);

            if (mysqli_fetch_assoc($result)["email"] === $email) {
                $errors[] = 'You already have an account';
            }

            if (strlen($firstname) <= 2 || strlen($firstname) > 25) {
                $errors[] = 'Firstname must be 3-25 long';
            }

            if (strlen($lastname) <= 2 || strlen($lastname) > 25) {
                $errors[] = 'Lastname must be 3-25 long';
            }

            if (strlen($password) <= 7 || strlen($password) > 15) {
                $errors[] = 'Password must be 8-15';
            }

            if ($password !== $password_check) {
                $errors[] = 'Please type the same password';
            }

            if (!empty($errors)) {
                foreach($errors as $error) {
                    echo $error . "<br>";
                }
            }else {
                mysqli_query($link, $q);
                header('Location: ?action=register');
                exit();
            }
            
         }
        return $this->render('register.html.twig');
    }

    public function loginAction()
    {
        require_once('init.php');
        $email = ' ';
        $password = ' ';
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = htmlentities($_POST['email']);
            $password = $_POST['password'];
            $errors = [];

            $ifUserExists = "SELECT * FROM `users` WHERE `email` = '" . $email . "' AND `password` = '" . $password . "'";
            $result = mysqli_query($link, $ifUserExists);
            $user = mysqli_fetch_assoc($result);

            if (empty($user)) {
                $errors = 'Wrong email or password';
            }

            if (!empty($errors)) {
                echo $errors;
            }else {
                $_SESSION['username'] = $user['firstname'];
                echo $_SESSION['username'];
                /*header('Location: ?action=upload');
                exit();*/

            }
        }
        return $this->render('login.html.twig');
    }
}