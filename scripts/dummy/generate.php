<?php

namespace go1\monolith\scripts;

use Doctrine\DBAL\DriverManager;
use go1\schema\mock\OneMock;
use GuzzleHttp\Client;
use PDO;

// This script can only run inside Docker.
require '/autoload/autoload.php';

call_user_func(function () {
    $db = DriverManager::getConnection(['url' => 'mysql://root:root@mysql/go1_dev']);
    # $db = DriverManager::getConnection(['url' => 'mysql://root:root@mysql']);
    $one = new OneMock;

    // Make sure the database is created
    $params = $db->getParams();
    unset($params['dbname'], $params['path'], $params['url']);
    $tmp = DriverManager::getConnection($params);
    $databases = $tmp->executeQuery('show databases;')->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('go1_dev', $databases)) {
        $tmp->getSchemaManager()->createDatabase($db->getDatabase());
    }

    // Install GO1 schema & dummy content.
    $one->install($db);

    // Run /install on all other services
    $client = new Client;
    $projects = require __DIR__ . '/../_projects.php';
    foreach (array_keys($projects['php']) as $name) {
        $url = 'http://web/GO1/' . $name . '/install';
        echo "GET $url\n";
        $client->get($url, ['http_errors' => false]);
    }

    // Run custom dummy scripts if found.
    foreach (glob(__DIR__ . '/services/*.php') as $file) {
        $callback = require $file;
        if (is_callable($callback)) {
            call_user_func($callback, $db);
        }
    }
});
