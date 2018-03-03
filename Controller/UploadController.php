<?php

require_once('Cool/BaseController.php');

class UploadController extends BaseController
{
    public function uploadAction() {
        session_start();
        $users_data = ['user' => $_SESSION];

        if (isset($_POST['file'])) {
            $name = $_FILES['file']['name'];
            $type = $_FILES['file']['type'];
            $size = $_FILES['file']['size'];
            $tmp_name = $_FILES['file']['tmp_name'];
            $file_data = ['name' => $name, 'type' => $type, 'size' => $size, 'tmp_name' => $tmp_name];
            $user_dir = $users_data['user_dir'];
            if (move_uploaded_file($tmp_name, $user_dir . $name)) {
                echo 'okok uploaded';
            }else {
                exit('nope');
            }
            
        }
        return $this->render('upload.html.twig', $users_data);
    }
}