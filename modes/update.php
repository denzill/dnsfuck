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
global $expiringDomains, $auctionDomains, $IP, $db, $databasefile;

$start = time();
include ("{$IP}/tpl/updateHeader.php");
copy("{$IP}/db/{$databasefile}", "{$IP}/db/{$databasefile}.new");
$db = new DBClass("{$IP}/db/{$databasefile}.new");
$db->update('exp_domains', array('status' => "'deleted'"), '1=1');
$db->update('auc_domains', array('status' => "'deleted'"), '1=1');
$expiringDomains = updateArray($expiringDomains);
$auctionDomains = updateArray($auctionDomains);
$filelist = array_merge($expiringDomains, $auctionDomains);
echo ("Скачиваем файлы<br>");
// Download all files
foreach ($filelist as $file => $url) {
    echo (basename($url) . ": ");
    flush();
    $filename = "{$IP}/temp/" . basename($url);
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
        echo ("Ок.<br>");
    } else {
        echo ("Ошибка!<br>");
    }
    flush();
}
// Unpack all files
echo ("Обработка файлов<br>");
foreach ($filelist as $filename => $file) {
    logger("file {$filename} update started\n");
    echo ("{$filename}: ");
    $gz = gzopen($file, 'r');
    $basedate = trim(gzgets($gz, 4096));
    if (checkUpdated($file)) {
        logger("file {$file} already updated\n");
        echo ("уже обновлен.<br>\n");
        continue;
    } else {
        echo ("обновление запущено.<br>\n");
    }
    flush();
    gzgets($gz, 4096);
    if ($gz !== false) {
        $stringsnum = $counter = 1;
        $db->beginTransaction();
        while (!gzeof($gz)) {
            $counter++;
            $stringsnum++;
            $string = trim(gzgets($gz, 4096));
            $domainArray = explode("\t", $string);
            if (isset($expiringDomains[basename($file)])) {
                insertExpDomain($domainArray);
            } elseif (isset($auctionDomains[basename($file)])) {
                insertAucDomain($domainArray);
            }
            if ($counter == 500) {
                $counter = 0;
                echo ("Обработано записей {$stringsnum}<br>\n");
                flush();
            }
        }
        $db->commitTransaction();
        setUpdated($file, $basedate);
        echo ("{$filename} обработан. Записей: {$stringsnum}<br>\n");
        flush();
    }
}
echo ("Удаляем старые записи<br>\n");
$db->delete("auc_domains", array('status' => "'deleted'"));
$db->delete("exp_domains", array('status' => "'deleted'"));
echo ("Переименование базы...");
if (copy("{$IP}/db/{$databasefile}.new", "{$IP}/db/{$databasefile}") !== true) {
    echo ("Ошибке!!<br>\n");
} else {
    echo ("Акакей.<br>\n");
}
$duration = time() - $start;
$sec = $duration % 60;
$min = floor($duration / 60);
echo ("Время выполнения: {$min} минут {$sec} секунд<br>\n");
//echo ("Время выполнения: {$duration} секунд<br>\n");
flush();
include ("{$IP}/tpl/updateFooter.php");
/* END */

/**
 * 
 * @global DBClass $db
 * @param type $file
 * @param type $date
 * @return boolean
 */
function checkUpdated($file) {
    global $db;
    $md5 = md5_file($file);
    $res = $db->select('basedates', '*', "file='" . basename($file) . "' and checksum='{$md5}'");
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

function getYandexTCI($value) {
    if ($value == '?') {
        return "'0'";
    } elseif ($value == '<10') {
        return "'1'";
    } else {
        return "'{$value}'";
    }
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
        'yandex_tci' => getYandexTCI($domain[7]),
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
        'yandex_tci' => getYandexTCI($domain[7]),
        'alexa' => "'{$domain[8]}'",
        'webarch' => "'{$domain[9]}'",
        'status' => "'updated'",
    );
    if ($db->update('auc_domains', $values, "domain='{$domain[0]}'") == 0) {
        $db->insert('auc_domains', $values);
    }
}

function setUpdated($file) {
    global $db;

    $md5 = md5_file($file);
    $changes = $db->update('basedates', array('checksum' => "'{$md5}'"), array('file' => "'" . basename($file) . "'"));
    if ($changes == 0) {
        $db->insert('basedates', array('checksum' => "'{$md5}'", 'file' => "'" . basename($file) . "'"));
    }
}
