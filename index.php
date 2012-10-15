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
error_reporting(E_ALL);
require_once 'config.php';
require_once 'inc/start.php';

$db = new DBClass("{$IP}/db/{$databasefile}");
$content = new ContentClass();
$content->execute();
?>