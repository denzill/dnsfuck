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
function filterAPI() {
    global $IP, $databasefile, $db;

    $db = new DBClass("{$IP}/db/{$databasefile}");
    $content = new ContentClass();
    $mode = $content->getVal('mode', '');
    if ($mode != '') {
        if (is_callable($mode . "Filter")) {
            logger($mode . "Filter");
//            $this->$mode();
}
    } else {
        logger(__METHOD__ . ": mode not defined");
    }
}

function createFilter($param) {

}

function applyFilter($param) {

}

function deleteFilter($param) {

}
