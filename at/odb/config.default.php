<?php

return call_user_func(function () {
    return [
        'debug'      => true,
        'sources'    => [
            'en' => 'http://odb.org',
            'jp' => 'http://japanese-odb.org/',
            'vi' => 'http://vietnamese-odb.org',
            'zh' => 'http://simplified-odb.org',
        ],
        'db.options' => [
            'driver'        => 'pdo_sqlite',
            'path'          => __DIR__ . '/odb.sqlite',
            'driverOptions' => [1002 => 'SET NAMES utf8'],
        ],
    ];
});
