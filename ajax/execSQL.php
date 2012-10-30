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
function execSQL() {
    global $IP, $db, $databasefile, $header, $tblheader;

    $db = new DBClass("{$IP}/db/{$databasefile}");
    $content = new ContentClass();

    $header = '';
    $tblheader = '';
    $sql = $content->getVal('sql');
    logger($sql);
    $res = $db->query($sql);
    $html = "";
    if ($res->numRows() > 0) {
        $count = 0;
        $html .= '<table class="table table-striped table-bordered">';
        while ($row = $res->fetchObject()) {
            $count++;
            $html .= printRow($row);
            if ($count > 50) {
                break;
            }
        }
        $html .= '</table>';
    } elseif ($db->db->changes() > 0) {
        $html .="<div class='alert alert-info'>Изменено строк: {$db->db->changes()}</div>";
    } else {
        $html .="<div class='alert alert-info'>Запрос вернул пустой результат</div>";
    }
    return $html;
}

function printRow($row) {
    global $header, $tblheader;

    $rowHtml = '';
    if ($header == false) {
        $tblheader = '';
    }
    foreach (get_object_vars($row) as $name => $value) {
        if ($header == false) {
            $tblheader .= "<th>{$name}</th>";
        }
        $rowHtml .= "<td>{$value}</td>";
    }
    if ($header == false) {
        $rowHtml = "<tr>{$tblheader}</tr>\n<tr>{$rowHtml}</tr>\n";
    } else {
        $rowHtml = "<tr>{$rowHtml}</tr>\n";
    }
    $header = true;
    return $rowHtml;
}