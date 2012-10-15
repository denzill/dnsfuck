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
// Аукционы
$content->addTab('auc_domains', array(
    'page' => $content->getVal('page', 1),
    'sort' => $content->getVal('sort', 'yandex_tci'),
    'sortdir' => $content->getVal('sortdir', 'DESC'),
        ), true);
$content->addHTML(makeFilterForm('auc_domains'), 'auc_domains');
$content->addHTML(getTablePagination('auc_domains', 'domain'), 'auc_domains');
$content->addHTML(getTableData('auc_domains', $content->getVal('sort', 'yandex_tci'), $content->getVal('page', 1), "Нет информации об освободившихся доменах."), 'auc_domains');

// Освобождающиеся домены
$content->addTab('exp_domains', array(
    'page' => $content->getVal('page', 1),
    'sort' => $content->getVal('sort', 'yandex_tci'),
    'sortdir' => $content->getVal('sortdir', 'DESC'),
        ), false);
$content->addHTML(makeFilterForm('exp_domains'), 'exp_domains');
$content->addHTML(getTablePagination('exp_domains', 'domain'), 'exp_domains');
$content->addHTML(getTableData('exp_domains', $content->getVal('sort', 'yandex_tci'), $content->getVal('page', 1), "Нет информации об освобождающихся доменах."), 'exp_domains');

// Большая кнопка "обновить инфу"
$content->addHTML("<br />" . $content->makeButton(array('primary', 'block'), 'ref_button', 'Обновить информацию', array('toggle' => 'modal', 'target' => '#domainsUpdate'), array('placement' => 'bottom', 'original-title' => 'Обновление базы освобождающихся и уже освободившихся доменов.')) . "<br />");

