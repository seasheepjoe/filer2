<?php

require_once('Cool/BaseController.php');

class UploadController extends BaseController
{
    public function uploadAction() {
        session_start();
        $users_data = ['user' => $_SESSION];   
        return $this->render('upload.html.twig', $users_data);
    }
}