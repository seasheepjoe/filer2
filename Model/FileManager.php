<?php
require_once('Cool/DBManager.php');
class FileManager {
    public function putFileOnDb($file_data, $ext) {
        $db = DBManager::getInstance();
        $pdo = $db->getPdo();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $request = $pdo->query("INSERT INTO `files` (`id`, `name`, `type`, `size`, `link`) VALUES (NULL, '".$file_data['name']."', '".$ext."', '".$file_data['size']."', '".$file_data['dir']."')");
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
    public function renameFileInDb($value,$id) {
        $db = DBManager::getInstance();
        $pdo = $db->getPdo();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $request = $pdo->query("UPDATE files SET name = '$value' WHERE id = $id");
    }
    public function upload($file_data) {
        $errors = [];
        $files = [];
        $upload = true;
        //Remove ext to secure
        list($file_data['name'], $ext) = explode(".", $file_data['name']);
        if (file_exists($file_data['dir'] . $file_data['name']  . "." . $ext)) {
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
            if (!move_uploaded_file($file_data['tmp_name'], $file_data['dir'] . $file_data['name'] . "." . $ext)) {
                $errors['upload'] = 'File is too big, max 1go' . $file_data['dir'];
                $upload = false;
            }
            self::putFileOnDb($file_data, $ext);
            self::write('access.log', 'Uploaded file from ', $file_data['dir'] . " / " . $file_data['name'] . " / " . $file_data['type']);
        }else {
            self::write('security.log', 'Error uploading file, error : ', $errors['upload']);
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
                if (unlink($value['link'] . $value['name'] . "." . $value['type']) && $delete->execute()) {
                    header('Location: ?action=upload');
                    exit();
                }
            }
        }    
    }
    /*public function edit($id) {
        $data = self::getDataFromID($id)->fetchAll();
        foreach ($data as $value) {
            $old_name = "/uploads/John/buck.png/";
            $new_name = "wesh";
            $db = DBManager::getInstance();
            $pdo = $db->getPdo();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            rename($old_name, $new_name);
            $set_new_name = $pdo->query("UPDATE `files` SET `name` = '" . $new_name . "' WHERE `id` = $id");
            header('Location: ?action=upload');
            exit();
        }
    }*/

    public function write($file, $message, $data) {
        $handle = fopen('logs/' . $file, 'a+');
        fwrite($handle, "[" . date('Y-m-d') . " : " . date('H-i-s') . "] : " . $message . " : " . "'" . $data . "'" .  "\n");
        fclose($handle);
    }

    public function download($id) {
        $data = self::getDataFromID($id)->fetchAll();
        foreach ($data as $value) {
            $file = $value['link'] . $value['name'] . "." . $value['type'];
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename="' . $value['name'] . '.' . $value['type'] . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            exit();
        }
    }

    private function getDataFromID($id) {
        $db = DBManager::getInstance();
        $pdo = $db->getPdo();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $get_data = $pdo->query("SELECT * FROM `files` WHERE `id` = $id");
        return $get_data;
    }

    public function getContent($id) {
        $data = self::getDataFromID($id);
        foreach ($data as $value) {
            $file = $value['link'] . $value['name'] . "." . $value['type'];
            if ($value['type'] === 'png' || $value['type'] === 'gif' || $value['type'] === 'jpg' || $value['type'] === 'jpeg') {
                header('Location: ?action=upload');
                exit();
            } else {
                $content = file_get_contents($file);
                return $content;
            }
        }
    }

    public function save($id) {
        $data = self::getDataFromID($id);
        foreach ($data as $value) {
            $file = $value['link'] . $value['name'] . "." . $value['type'];
            if (isset($_POST['textarea'])) {
                $put_content = file_put_contents($file, $_POST['textarea']);
                header('Location: ?action=upload');
                exit();
            }
        }
    }

    public function getOneFile($id) {
        $data = self::getDataFromID($id)->fetchAll();
        foreach ($data as $value) {
            return $value['id'];
        }
    }
}