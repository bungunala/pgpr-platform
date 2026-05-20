<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
    public $aliases = [
        'csrf'      => \CodeIgniter\Filters\CSRF::class,
        'toolbar'   => \CodeIgniter\Filters\DebugToolbar::class,
        'admins'    => \App\Filters\AdminFilter::class,
        'jwt'       => \App\Filters\JwtFilter::class,
    ];

    public $methods = [];

    public $filters = [
        'jwt' => ['before' => ['api/*']],
    ];
}