<?php

require_once('Cool/DBManager.php');

class AccountManager {
    
    public function register($firstname, $lastname, $email, $country, $city, $birthdate, $password, $password_check) {
        $errors = [];
        $sendForm = true;

        if (strlen($firstname) <= 2 || strlen($firstname) > 25) {
            $errors['firstname'] = 'Firstname must be 3-25 long';
            $sendForm = false;
            
        }

        if (strlen($lastname) <= 2 || strlen($lastname) > 25) {
            $errors['lastname'] = 'Lastname must be 3-25 long';
            $sendForm = false;
        }

        if (strlen($password) <= 5 || strlen($password) > 15) {
            $errors['password'] = 'Password must be 8-15';
            $sendForm = false;
        }

        if ($password !== $password_check) {
            $errors['password_check'] = 'Please type the same password';
            $sendForm = false;
        }

        $db = DBManager::getInstance();
        $pdo = $db->getPdo();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $request = $pdo->prepare("INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `country`, `city`, `birthdate`, `password`) VALUES (NULL, '" . $firstname . "', '" . $lastname . "',
        '" . $email . "', '" . $country . "', '" . $city . "', '" . $birthdate . "', '" . $password . "')");

        $emailVerif = $pdo->prepare("SELECT `email` FROM `users` WHERE `email` = '$email'");
        $emailVerif->execute();
        $isEmailUsed = $emailVerif->fetch();
        
        if ($isEmailUsed['email'] === $email) {
            $errors['email'] = 'You already have an account';
            $sendForm = false;
        }

        if ($sendForm === true) {
            $request->execute();
            $dir = 'uploads/' . $firstname . '/';
            mkdir($dir, 0777, true);
            header('Location: ?action=login');

        }else {
            return $errors;
        }
    }

    public function login($email, $password) {
        $db = DBManager::getInstance();
        $pdo = $db->getPdo();

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $login = $pdo->query("SELECT * FROM `users` WHERE `email` = '" . $email . "' AND `password` = '" . $password . "'");

        $user = $login->fetch();

        if (!empty($user)) { 
            $_SESSION['username'] = $user['firstname'];
            $_SESSION['user_dir'] = 'uploads/' . $user['firstname'] . '/';
            $handle = fopen('logs/access.log', 'a+');
            fwrite($handle, "[" . date('Y-m-d') . " : " . date('H-i-s') . "] : " ."Logged in as ". "'" . $email . "'\n");
            fclose($handle);
            header('Location: ?action=upload');
            exit();
        }else {
            return 'Invalid email or password';
        }
    }
}