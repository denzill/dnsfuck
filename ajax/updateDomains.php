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
function updateDomains() {
    global $expiringDomains, $auctionDomains, $IP, $db, $databasefile, $status;

    $db = new DBClass("{$IP}/db/{$databasefile}");
    $db->update('exp_domains', array('status' => "'deleted'"), '1=1');
    $db->update('auc_domains', array('status' => "'deleted'"), '1=1');
    $result = array();
    $result['status_text'] = '<pre>';
    $expiringDomains = updateArray($expiringDomains);
    $auctionDomains = updateArray($auctionDomains);
    $filelist = array_merge($expiringDomains, $auctionDomains);
    logger($filelist);
    $status = $filelist;
    updateStatus(null, 'Добавлен в очередь.');
    foreach ($filelist as $file => $url) {
        // Download all files
        $filename = "{$IP}/temp/" . basename($url);
        updateStatus($file, 'Скачивание.');
        $out = fopen($filename, 'w');
        $input = fopen($url, 'r'); // input file
        if ($input !== false) {
            while (!feof($input)) {
                $buff = fread($input, 4096);
                fwrite($out, $buff);
            }
            fclose($input);
            fclose($out);
            $filelist[$file] = $filename;
            updateStatus($file, 'Скачан.');
        }
    }
    logger($filelist);
    // Unpack all files
    foreach ($filelist as $filename => $file) {
        logger("file {$filename} update started\n");
        $result['status_text'].= $file . "<br>\n";
        $gz = gzopen($file, 'r');
        $basedate = trim(gzgets($gz, 4096));
        if (checkUpdated($file, $basedate)) {
            logger("file {$file} already updated\n");
            $result['status_text'].= "уже обновлен.<br>\n";
            continue;
        }
        gzgets($gz, 4096);
        if ($gz !== false) {
            $stringsnum = $counter = 1;
            $db->beginTransaction();
            while (!gzeof($gz)) {
                $counter++;
                $stringsnum++;
                $string = trim(gzgets($gz, 4096));
                $domainArray = explode("\t", $string);
                if (crapFilter($domainArray)) {
                    continue;
                }
                if (isset($expiringDomains[basename($file)])) {
                    insertExpDomain($domainArray);
                } elseif (isset($auctionDomains[basename($file)])) {
                    insertAucDomain($domainArray);
                }
                if ($counter == 100) {
                    $counter = 1;
                    updateStatus($filename, "Обработано записей: {$stringsnum}");
                }
            }
            $db->commitTransaction();
            setUpdated($file, $basedate);
            updateStatus($filename, "Обработан. Записей: {$stringsnum}");
            $result['status_text'].= "{$filename} обработан. Записей: {$stringsnum}<br>\n";
        }
    }
    $db->delete("auc_domains", array('status' => 'deleted'));
    $db->delete("exp_domains", array('status' => 'deleted'));
    $result['status'] = true;
    return $result;
}

function checkUpdated($file, $date) {
    global $db;
    $res = $db->select('basedates', '*', "file='{$file}' and date='{$date}'");
    if ($res->numRows() > 0) {
        return true;
    } else {
        return false;
    }
}

function updateArray($array) {
    $updated = array();
    foreach ($array as $value) {
        $updated[basename($value)] = $value;
    }
    return $updated;
}

function insertExpDomain($domain) {
    global $db;
    $values = array(
        'domain' => "'{$domain[0]}'",
        'registar' => "'{$domain[1]}'",
        'free_date' => "'{$domain[2]}'",
        'may_by_date' => "'{$domain[3]}'",
        'created' => "'{$domain[4]}'",
        'tlds' => "'{$domain[5]}'",
        'google_pr' => "'{$domain[6]}'",
        'yandex_tci' => "'{$domain[7]}'",
        'alexa' => "'{$domain[8]}'",
        'webarch' => "'{$domain[9]}'",
        'status' => "'updated'",
    );
    if ($db->update('exp_domains', $values, "domain='{$domain[0]}'") == 0) {
        $db->insert('exp_domains', $values);
    }
}

function insertAucDomain($domain) {
    global $db;
    list ($start, $end) = explode('-', $domain[3]);
    $values = array(
        'domain' => "'{$domain[0]}'",
        'price' => "'{$domain[1]}'",
        'bidders' => "'{$domain[2]}'",
        'auc_start' => "'{$start}'",
        'auc_end' => "'{$end}'",
        'created' => "'{$domain[4]}'",
        'tlds' => "'{$domain[5]}'",
        'google_pr' => "'{$domain[6]}'",
        'yandex_tci' => "'{$domain[7]}'",
        'alexa' => "'{$domain[8]}'",
        'webarch' => "'{$domain[9]}'",
        'status' => "'updated'",
    );
    if ($db->update('auc_domains', $values, "domain='{$domain[0]}'") == 0) {
        $db->insert('auc_domains', $values);
    }
}

function setUpdated($file, $date) {
    global $db;
    $changes = $db->update('basedates', array('date' => "'{$date}'"), array('file' => "'{$file}'"));
    if ($changes = 0) {
        $db->insert('basedates', array('date' => "'{$date}'", 'file' => "'{$file}'"));
    }
}

function updateStatus($statusName, $statusStr) {
    global $status, $IP;
    if ($statusName === null) {
        foreach ($status as $key => $value) {
            $status[$key] = $statusStr;
        }
    } else {
        if (isset($status[$key])) {
            $status[$key] = $statusStr;
        }
    }
    file_put_contents("{$IP}/temp/status", serialize($status));
}

/**
 * Фильтр говна
 * @param type $param
 * @return boolean true - если домен говно и false если домен гут
 */
function crapFilter($param) {
//    if (($param[6] == '?') || ($param[7] == '<10') || ($param[8] == '-') || ($param[9] == '-')) {
    if (($param[7] == '<10') || (!intval($param[7]))) {
        return true;
    }
    return false;
}