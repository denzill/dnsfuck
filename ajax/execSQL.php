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
    $res = $db->query($sql);
    $html = "";
    if ($res->numRows() > 0) {
        $count = 0;
        $html .= '<table class="table table-striped">';
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
    $array2 = unserialize('v8#a:41:{s:8:"_version";s:2:"v8";s:10:"_view_type";s:6:"simple";s:8:"per_page";i:50;s:17:"highlight_changed";i:6;s:13:"sticky_issues";s:2:"on";s:4:"sort";s:12:"last_updated";s:3:"dir";s:4:"DESC";s:8:"platform";a:1:{i:0;s:1:"0";}s:2:"os";a:1:{i:0;s:1:"0";}s:8:"os_build";a:1:{i:0;s:1:"0";}s:10:"project_id";a:1:{i:0;i:-3;}s:11:"start_month";i:10;s:9:"start_day";i:1;s:10:"start_year";i:2012;s:9:"end_month";i:10;s:7:"end_day";i:16;s:8:"end_year";i:2012;s:6:"search";s:0:"";s:16:"and_not_assigned";b:0;s:17:"do_filter_by_date";s:2:"on";s:10:"view_state";i:0;s:17:"relationship_type";i:-1;s:16:"relationship_bug";i:0;s:14:"target_version";a:1:{i:0;s:1:"0";}s:10:"tag_string";s:0:"";s:10:"tag_select";i:0;s:13:"show_category";a:1:{i:0;s:1:"0";}s:13:"show_severity";a:1:{i:0;i:0;}s:11:"show_status";a:1:{i:0;i:0;}s:11:"reporter_id";a:1:{i:0;i:0;}s:10:"handler_id";a:1:{i:0;i:0;}s:12:"note_user_id";a:1:{i:0;i:0;}s:15:"show_resolution";a:1:{i:0;i:0;}s:13:"show_priority";a:1:{i:0;i:0;}s:10:"show_build";a:1:{i:0;s:1:"0";}s:12:"show_version";a:1:{i:0;s:1:"0";}s:11:"hide_status";a:1:{i:0;i:90;}s:16:"fixed_in_version";a:1:{i:0;s:1:"0";}s:12:"user_monitor";a:1:{i:0;i:0;}s:12:"show_profile";a:1:{i:0;i:0;}s:13:"custom_fields";a:7:{i:6;a:1:{i:0;s:1:"0";}i:4;a:1:{i:0;s:1:"0";}i:5;a:1:{i:0;s:1:"0";}i:2;a:1:{i:0;s:1:"0";}i:9;a:3:{i:0;s:0:"";i:1;s:1:"1";i:2;s:1:"1";}i:3;a:3:{i:0;s:0:"";i:1;s:1:"1";i:2;s:1:"1";}i:7;a:1:{i:0;s:1:"0";}}}');
    error_reporting(E_ALL);
    $html .= var_export($array2,1);
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