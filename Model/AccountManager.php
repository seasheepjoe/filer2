<?php

require_once 'Cool/DBManager.php';

class AccountManager {
    
    public function register($firstname, $lastname, $email, $country, $city, $birthdate, $password, $password_check) {
        $errors = [];
        $sendForm = true;

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
        
        if (mysqli_fetch_assoc($result)["email"] === $email) {
            $errors[] = 'You already have an account';
        }
    }
{