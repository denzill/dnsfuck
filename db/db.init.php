<?

$tables = array(
    'exp_domains' => array(
        'domain' => 'text',
        'registar' => 'text',
        'free_date' => 'text',
        'may_by_date' => 'text',
        'created' => 'text',
        'tlds' => 'text',
        'google_pr' => 'text',
        'yandex_tci' => 'int',
        'alexa' => 'text',
        'webarch' => 'text',
        'yc' => 'text',
        'glue' => 'text',
        'status' => 'text',
    ),
    'auc_domains' => array(
        'domain' => 'text',
        'price' => 'int',
        'bidders' => 'text',
        'auc_start' => 'text',
        'auc_end' => 'text',
        'created' => 'text',
        'tlds' => 'text',
        'google_pr' => 'text',
        'yandex_tci' => 'int',
        'alexa' => 'text',
        'webarch' => 'text',
        'yc' => 'text',
        'glue' => 'text',
        'status' => 'text',
    ),
    'basedates' => array(
        'file' => 'text',
        'date' => 'text',
    ),
    'filters' => array(
        'user' => 'text',
        'name' => 'text',
        'json' => 'text',
    ),
);

return $tables;
