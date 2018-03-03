<?php

require_once('Cool/BaseController.php');

class UploadController extends BaseController
{
    public function uploadAction() {
        session_start();
        $users_data = ['user' => $_SESSION];

        if (isset($_POST['upload-btn'])) {
            $name = $_FILES['user_file']['name'];
            $type = $_FILES['user_file']['type'];
            $size = $_FILES['user_file']['size'];
            $tmp_name = $_FILES['user_file']['tmp_name'];
            $file_data = ['name' => $name, 'type' => $type, 'size' => $size, 'tmp_name' => $tmp_name];
            $user_dir = $users_data['user']['user_dir'];

            if (!is_uploaded_file($tmp_name)) {
                exit("Cannot find the file");
            }

            if (!move_uploaded_file($tmp_name, $user_dir . $name)) {
                exit("Cannot move file into $user_dir");
            }
        }
        return $this->render('upload.html.twig', $users_data);
    }
}