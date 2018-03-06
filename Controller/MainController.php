<?php

require_once('Cool/BaseController.php');

class MainController extends BaseController
{
    public function homeAction()
    {
        session_start();
        $users_data = ['user' => $_SESSION];
        return $this->render('home.html.twig', $users_data);
    }
}
