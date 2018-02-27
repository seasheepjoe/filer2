<?php

require_once('Cool/BaseController.php');

class UploadController extends BaseController
{
    public function uploadAction()
    {
        require_once('init.php');
        if (isset($_POST['dl-file'])) {
            $name = $_FILES['file-upload']['name'];
            $type = $_FILES['file-upload']['type'];
            $size = $_FILES['file-upload']['size'];
            $tmp_name = $_FILES['file-upload']['tmp_name'];
            $file_data = ['name' => $name, 'type' => $type, 'size' => $size, 'tmp_name' => $tmp_name];
            $user_dir = $_SESSION['dir'];
            if (move_uploaded_file($tmp_name, $user_dir . $name)) {
                echo 'okok uploaded';
            }
            
        }
        
        return $this->render('upload.html.twig');
    }
}
