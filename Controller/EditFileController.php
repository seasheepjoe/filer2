<?php

require_once('Cool/BaseController.php');

class EditFileController extends BaseController
{
    public function editFileAction() {
        session_start();
        require_once('Model/FileManager.php');

        $file_manager = new FileManager();
        $users_data = [
            'user' => $_SESSION,
        ];
        
        if(isset($_POST['save-btn'])) {
            $file_manager->save($_POST['save-btn']);
        }
        
        return $this->render('editFile.html.twig', $users_data);
    }

        
    
}
