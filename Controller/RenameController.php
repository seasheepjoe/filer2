<?php

require_once('Cool/BaseController.php');

class RenameController extends BaseController
{
    public function renameAction() {
        session_start();
        require_once('Model/FileManager.php');

        $file_manager = new FileManager();
        $users_data = [
            'user' => $_SESSION,
        ];
        
        if(isset($_POST['rename-btn'])) {
            $file_manager->renameFile($_POST['rename-btn']);
        }
        
        return $this->render('rename.html.twig', $users_data);
    }

        
    
}
