<?php
/*Name: dispatcher.php
Description: This file is responsible for routing the actions from the frontend to the appropriate controllers in the backend.
Paraskevas Vafeiadis
27-feb-2026 v0.1
Inputs: action (string)
Outputs: None
Error Messages: None
Files in use: logoutcontroller.php where the log out process is handled.
*/
declare(strict_types=1);

        $action = $_POST['action'] ?? '';
        switch($action){
            case 'logout':
                // the controller resides in ../controllers relative to this file
                require_once __DIR__ . '/../controllers/logoutcontroller.php';
                break;
            default:
                echo "Invalid action";
        }

?>