<?php

return array(

    'default'     => 'mysql',
    'fetch'       => PDO::FETCH_ASSOC,
    'migrations'  => 'migrations',
    'connections' => array(
        'mysql' => array(
            'driver'    => 'mysql',
            'host'      => 'crm-legal.cduqidloj8ih.us-west-2.rds.amazonaws.com',
            'database'  => 'publisher_prod',
            'username'  => 'royalty',
            'password'  => 'eVedDL9mjw7AxPap',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'options' => [
                PDO::MYSQL_ATTR_LOCAL_INFILE => true
            ],
        ),
    ),
);
