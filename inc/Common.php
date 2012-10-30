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
function logger($string) {
    global $IP;

    $logfile = "{$IP}/log/log.log";
    if (!is_string($string)) {
        file_put_contents($logfile, var_export($string, 1) . "\n", FILE_APPEND);
    } else {
        file_put_contents($logfile, trim($string) . "\n", FILE_APPEND);
    }
}

function getTableData($tablename, $sortField, $page = 1, $emptyMessage = '') {
    global $content, $db, $results, $crapFilter;

    $start = ($page - 1) * $results;
    $db_res = $db->select($tablename, '*', $crapFilter, "order by {$sortField} desc LIMIT {$start},{$results} ");
    if ($db_res->numRows() == 0) { // Записей нет
        $html = $content->makeAlert('Внимание!', $emptyMessage);
    } else {
        $html = "<table id='{$tablename}_table' table='{$tablename}' class='table table-bordered table-striped table-hover'>";
        $row = $db_res->fetchObject();
        $table = $content->makeTableHeaders($row);
        $table .="<tbody id='{$tablename}_tbody' sort='{$sortField}'>\n";
        while ($row) {
            $table .= $content->makeTableRow($row);
            $row = $db_res->fetchObject();
        }
        $html .= "{$table}</tbody>\n</table>\n";
    }
    return $html;
}

function getTablePagination($table, $countField) {
    global $db, $results, $content, $crapFilter;

    $count = $db->countRecords($table, $countField, $crapFilter);
    $html = "Всего доменов: {$count}<br />";
    $pages = floor($count / $results);
    $current = $content->getVal('page', 1);
    $sort = $content->getVal('sort', 'yandex_tci');
    $sortdir = $content->getVal('sortdir', 'desc');
    if (($count % $results) > 0) {
        $pages++;
    }
    $html .="<div id='{$table}_pagination' sortdir='{$sortdir}' sort='{$sort}' class='pagination pagination-centered' page='{$current}'>\n<ul table='{$table}'>\n";
    $html.="<li" . ($current == 1 ? " class='active'" : "") . "><a href='#'>Prev</a></li>";
    $counter = 0;
    while ($pages > $counter) {
        $counter++;
        if ($counter == $current) {
            $class = 'active';
        } else {
            $class = '';
        }
        $html.="<li class='{$class}'><a href='#' page='{$counter}'>{$counter}</a></li>";
    }
    $html.="<li" . ($current == $counter ? " class='active'" : "") . "><a href='#'>Next</a></li>";
    $html .="</ul>\n</div><br>\n";
    return $html;
}

function makeFilterForm($table) {
    global $db, $table_headers;

    $html = "<form class='form-inline' id='{$table}_form'>\n<input type='hidden' value='{$table}' name='table'>\n<input type='hidden' value='create' name='mode'>";
    $html .= "  <legend>Фильтр</legend>\n";
    $desc = $db->describe($table);
    foreach ($desc as $name => $type) {
        $html .= "&nbsp;<input class='input-small' type='text' name='{$name}' dbtype='$type' placeholder='" . (isset($table_headers[$name]) ? $table_headers[$name] : $name) . "'>";
    }
    $html .= "&nbsp;" . ContentClass::makeButton(array('filter', 'primary'), '', 'Применить', array(), array('placement' => 'top', 'original-title' => 'Фильтр откроется в новой вкладке.'));
    $html .= "</form>";
    return $html;
}