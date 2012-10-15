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
function getData() {
    global $db, $results, $IP, $databasefile;

    $content = new ContentClass();
    $db = new DBClass("{$IP}/db/{$databasefile}");
    $table = $content->getVal('table', null);
    $page = $content->getVal('page', 1);
    $sort = $content->getVal('sort', 'yandex_tci');
    $sortdir = $content->getVal('sortdir', 'desc');
    if ($table !== null) {
        $start = ($page - 1) * $results;
        $db_res = $db->select($table, '*', '', "order by {$sort} {$sortdir} LIMIT {$start},{$results} ");
        if ($db_res->numRows() == 0) { // Записей нет
            $html = $content->makeAlert('Внимание!', 'Ничего не найдено');
        } else {
            $row = $db_res->fetchObject();
            $html = '';
            while ($row) {
                $html .= $content->makeTableRow($row);
                $row = $db_res->fetchObject();
            }
        }
    } else {
        $html = ContentClass::makeAlert('Warning', 'Хде все параметры?');
    }
    return array('html' => $html);
}