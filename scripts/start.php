<?php

$pwd = dirname(__DIR__);
passthru("mkdir -p $pwd/.data/nginx/sites-available");
passthru("touch $pwd/.data/nginx/sites-available/default.conf");
passthru("cp $pwd/.data/nginx/app.conf $pwd/.data/nginx/sites-available/default.conf");
passthru("docker-compose start"); # docker-compose restart &

# Make sure we have database for all services
$projects = require __DIR__ . '/_projects.php';
foreach (array_keys($projects['php']) as $name) {
    echo "[install] GET http://localhost/GO1/{$name}/install\n";
    @file_get_contents("http://localhost/GO1/{$name}/install");
}
