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
$IP = dirname(__FILE__);
$databasefile = 'dnsfuck.db';
$results = 100;

$expiringDomains = array(
    'http://auction.nic.ru/downloads/ru_expiring_list.gz',
    'http://auction.nic.ru/downloads/3d_expiring_list.gz',
);

$auctionDomains = array(
    'http://auction.nic.ru/downloads/ru_auction_list.gz',
    'http://auction.nic.ru/downloads/3d_auction_list.gz',
);

$allowed_fields = array(
    'domain',
    'price',
    'bidders',
    'auc_start',
    'auc_end',
    'created',
    'google_pr',
    'yandex_tci',
    'registar',
    'free_date',
    'may_by_date',
    'yc',
    'glue',
);

$filter_fields = array(
    'domain',
    'price',
    'bidders',
    'google_pr',
    'yandex_tci',
    'yc',
);

/* Фильтр говна 
 * (SQL WHERE condition)
 * ТиЦ + ЯК
 * ТиЦ без ЯК
 * ЯК без ТиЦ
 */
//$crapFilter = "(glue is not null and yc is not null and yc!='-') OR (glue!='true' and glue is not null and yandex_tci!='?' and yandex_tci!='<10')";
//$crapFilter = "(glue='false' and yc is not null and yc!='-') OR (glue!='true' and glue is not null and yandex_tci!='?' and yandex_tci!='<10')";
$crapFilter = "(glue='false' and yc!='-') OR (glue='false' and yandex_tci>1)";
