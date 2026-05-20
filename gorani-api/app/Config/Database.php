<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Database extends BaseConfig
{
    public $defaultGroup = 'default';

    public $default = [
        'DSN'          => '',
        'hostname'     => getenv('DB_HOST') ?: 'localhost',
        'username'     => getenv('DB_USERNAME') ?: 'pgpr_user',
        'password'     => getenv('DB_PASSWORD') ?: 'pgpr_dev_password',
        'database'     => getenv('DB_DATABASE') ?: 'pgpr_platform',
        'DBDriver'     => 'Postgre',
        'DBPrefix'     => '',
        'pConnect'     => false,
        'DBDebug'      => (ENVIRONMENT !== 'production'),
        'charset'      => 'utf8',
        'DBCollat'     => '',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'saveQueries'  => true,
    ];

    public $tests = [
        'DSN'         => '',
        'hostname'     => '127.0.0.1',
        'username'     => 'root',
        'password'     => '',
        'database'     => 'database_test',
        'DBDriver'     => 'MySQLi',
        'DBPrefix'     => 'db_',
        'pConnect'     => false,
        'DBDebug'      => true,
        'charset'      => 'utf8',
        'DBCollat'     => '',
        'swapPre'      => '',
        'encrypt'      => false,
        'compress'     => false,
        'strictOn'     => false,
        'failover'     => [],
        'saveQueries'  => true,
    ];
}