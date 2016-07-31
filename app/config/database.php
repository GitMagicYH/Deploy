<?php

return array(

    'fetch'   => PDO::FETCH_CLASS,

    'default' => 'project',

    'connections' => array(
        'project' => array(
            'host'      => '127.0.0.1',
            'port'      => '3306',
            'database'  => 'project',
            'username'  => 'root',
            'password'  => '123456',
            'options'   => array(
                PDO::ATTR_EMULATE_PREPARES => true,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
            ),
        ),
    ),

    'redis' => array(
        'demo' => array(
            'host'     => '127.0.0.1',
            'port'     => '6379',
            'password' => 'demo',
        ),
    ),

);
