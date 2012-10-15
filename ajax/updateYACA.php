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
function updateYACA() {
    global $IP, $db, $databasefile;

    $db = new DBClass("{$IP}/db/{$databasefile}");
    $tables = array('auc_domains', 'exp_domains');
    $count = 0;
    foreach ($tables as $table) {
        $res = $db->select($table, '*', "yc IS NULL OR glue IS NULL", 'ORDER BY yandex_tci desc LIMIT 0,25');
        while ($row = $res->fetchObject()) {
            $count++;
            $domain = strtolower($row->domain);
            $url = "http://bar-navig.yandex.ru/u?ver=2&show=16&url=http://{$domain}";
            $xml = simplexml_load_file($url);
            if (($xml->url->attributes()->domain == $domain) || ($xml->url->attributes()->domain == "www.{$domain}")) {
                $update['glue'] = "'{$row->domain}'";
            } else {
                $update['glue'] = "'{$xml->url->attributes()->domain}'";
            }
            $textinfo = "";
            foreach ($xml->textinfo as $value) {
                $textinfo .= $value;
            }
            $update['yc'] = "'" . str_replace("\n", "<br />", trim($textinfo)) . "'";
            $db->update($table, $update, array('domain' => "'{$row->domain}'"));
        }
    }
    $result = array('count' => $count);
    return $result;
}
