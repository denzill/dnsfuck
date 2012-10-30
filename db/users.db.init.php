<?

$tables = array(
    'users' => array(
        'id' => 'int primary key',
        'user' => 'text',
        'password' => 'text',
        'cookie' => 'text',
    ),
);

return $tables;
