<?php

require_once('Cool/DBManager.php');

class FileManager {
    public function upload($file_data) {
        if (!is_uploaded_file($file_data['tmp_name'])) {
            exit("Cannot find the file");
        }

        if (!move_uploaded_file($file_data['tmp_name'], $file_data['dir'] . $file_data['name'])) {
            exit("Cannot move file into" . $file_data['dir']);
        }
    }
}