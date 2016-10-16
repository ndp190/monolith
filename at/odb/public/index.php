<?php

use andytruong\odb\App;

return call_user_func(function () {
    require_once __DIR__ . '/../vendor/autoload.php';

    !defined('APP_ROOT') && define('APP_ROOT', dirname(__DIR__));

    $cnf = is_file(__DIR__ . '/../config.php') ? __DIR__ . '/../config.php' : __DIR__ . '/../config.default.php';
    $app = new App(require $cnf);

    return ('cli' === php_sapi_name()) ? $app : $app->run();
});
