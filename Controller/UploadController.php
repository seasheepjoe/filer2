<?php

require_once('Cool/BaseController.php');

class UploadController extends BaseController
{
    public function uploadAction()
    {
        if (isset($_POST['dl-file'])) {
            $name = $_FILES['file-upload']['name'];
            $type = $_FILES['file-upload']['type'];
            $size = $_FILES['file-upload']['size'];
            $tmp_name = $_FILES['file-upload']['tmp_name'];
            $file_data = ['name' => $name, 'type' => $type, 'size' => $size, 'tmp_name' => $tmp_name    ];
            var_dump($file_data);
        }
        
        return $this->render('upload.html.twig');
    }
}
