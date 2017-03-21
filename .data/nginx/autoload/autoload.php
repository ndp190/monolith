<?php

use Composer\Autoload\ClassLoader;

function __db_connection_options()
{
    return [
        'driver'        => 'pdo_mysql',
        'dbname'        => "go1_dev",
        'host'          => 'mysql',
        'user'          => 'root',
        'password'      => 'root',
        'port'          => '3306',
        'driverOptions' => [1002 => 'SET NAMES utf8'],
    ];
}

if (isset($_SERVER['REQUEST_URI'])) {
    if (0 === strpos($_SERVER['REQUEST_URI'], '/GO1/')) {
        $_SERVER['REQUEST_URI'] = preg_replace('`^/GO1/[a-z0-9\\-]+-service/(.*)$`', '/$1', $_SERVER['REQUEST_URI']);
        $_SERVER['REQUEST_URI'] = preg_replace('`^/GO1/[a-z0-9\\-]+/(.*)$`', '/$1', $_SERVER['REQUEST_URI']);
    }

    if (0 === strpos($_SERVER['REQUEST_URI'], '/v3/')) {
        $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], 3);
    }

    if (0 === strpos($_SERVER['REQUEST_URI'], '//')) {
        $_SERVER['REQUEST_URI'] = substr($_SERVER['REQUEST_URI'], 1);
    }
}

/** @var ClassLoader $loader */
$loader = require_once "/app/vendor/autoload.php";

// Custom psr4 goes here.
//$loader->addPsr4('go1\\MyNamespace\\', '/app/path/to/my/project');

return $loader;
