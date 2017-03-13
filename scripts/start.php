<?php

namespace go1\monolith;

use mysqli;

$pwd = dirname(__DIR__);
passthru("mkdir -p $pwd/.data/nginx/sites-available");
passthru("touch $pwd/.data/nginx/sites-available/default.conf");
passthru("cp $pwd/.data/nginx/app.conf $pwd/.data/nginx/sites-available/default.conf");
passthru("docker-compose up -d --force-recreate");

# Make sure database 'go1_dev' is created.
$db = new mysqli('127.0.0.1', 'root', 'root');
if (!$db->select_db('go1_dev')) {
    if (true !== $db->query('CREATE DATABASE go1_dev')) {
        die("Failed to create 'go1_dev': {$db->error}\n");
    }
}
$db->close();

# Make sure we have database for all services
$projects = require __DIR__ . '/_projects.php';
foreach (array_keys($projects['php']) as $name) {
    echo "[install] GET http://localhost/GO1/{$name}/install\n";
    @file_get_contents("http://localhost/GO1/{$name}/install");
}
