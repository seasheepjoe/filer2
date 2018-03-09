<?php

require_once('Cool/DBManager.php');

class FileManager {

    public function putFileOnDb($file_data) {
        $db = DBManager::getInstance();
        $pdo = $db->getPdo();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $request = $pdo->query("INSERT INTO `files` (`id`, `name`, `type`, `size`, `link`) VALUES (NULL, '".$file_data['name']."', '".$file_data['type']."', '".$file_data['size']."', '".$file_data['dir']."')");
    }

    public function getFilesInDb () {
        $db = DBManager::getInstance();
        $pdo = $db->getPdo();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $request = $pdo->query("SELECT * FROM `files` WHERE `link` = '" . $_SESSION['user_dir'] . "'");
        $files = [];    
        while ($result = $request->fetchAll()){
            $files[] = $result;
            return $files;
        }
    }


    public function upload($file_data) {

        $errors = [];
        $files = [];
        $upload = true;

        if (file_exists($file_data['dir'] . $file_data['name'])) {
            $errors['upload'] = 'Files exists, please choose another file';
            $upload = false;
        }

        if (!is_uploaded_file($file_data['tmp_name'])) {
            $errors['upload'] = 'Cannot find the file';
            $upload = false;
        }

        if ($file_data['size'] > 1000000000) {
            $errors['upload'] = 'File is too big, max 1go';
            $upload = false;
        }

        if ($upload === true) {
            if (!move_uploaded_file($file_data['tmp_name'], $file_data['dir'] . $file_data['name'])) {
                $errors['upload'] = 'File is too big, max 1go' . $file_data['dir'];
                $upload = false;
            }
            self::putFileOnDb($file_data);
        }else {
            return $errors;
        }
    }

    public function delete($id) {
        $db = DBManager::getInstance();
        $pdo = $db->getPdo();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $get = $pdo->query("SELECT * FROM `files` WHERE `id` = $id");
        $delete = $pdo->prepare("DELETE FROM `files` WHERE `id` = $id");
        while ($result = $get->fetchAll()){
            foreach ($result as $value) {
                if (unlink($value['link'] . $value['name']) && $delete->execute()) {
                    header('Location: ?action=upload');
                    exit();
                }
            }
        }
    }

    public function edit($id, $dir) {
        $db = DBManager::getInstance();
        $pdo = $db->getPdo();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $get_name = $pdo->query("SELECT * FROM `files` WHERE `id` = $id");
        while ($get_old_name = $get_name->fetchAll()) {
            foreach ($get_old_name as $old_name) {
                $old_name = $old_name['name'];
                $new_name = 'newndzdzzdzame';
                $set_new_name = $pdo->query("UPDATE `files` SET `name` = '" . $new_name . "' WHERE `id` = $id");
                header('Location: ?action=upload');
                exit();
            }
        }

    }
}