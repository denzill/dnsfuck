<?php

/**
 * <Create your comment here>
 *
 * $Revision: $
 * $Id: $
 * $Date:  $
 *
 * @Author: $Author: $
 * @version $Revision: $
 */
require_once 'config.php';
require_once 'inc/start.php';

$result = array('status' => false, 'text' => '');
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if (file_exists("ajax/{$action}.php")) {
        include_once "ajax/{$action}.php";
        if (is_callable($action)) {
            $result = call_user_func($action);
        } else {
            $result['status_text'] = 'action not callable!';
        }
    } else {
        $result['status_text'] = 'action not exist!';
    }
} else {
    $result['status_text'] = 'action not defined!';
}
exit(json_encode($result));
