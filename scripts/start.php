<?php

$pwd = dirname(__DIR__);
passthru("mkdir -p $pwd/.data/nginx/sites-available");
passthru("touch $pwd/.data/nginx/sites-available/default.conf");
passthru("cp $pwd/.data/nginx/app.conf $pwd/.data/nginx/sites-available/default.conf");
passthru("docker-compose up --force-recreate"); # docker-compose restart &
echo "Ctrl+c to stop services.\n";

# Wait for all services to be started, this is not accurate.
sleep(30);

# Make sure we have database for all services
$projects = require __DIR__ . '/_projects.php';
foreach ($projects['php'] as $name) {
    file_get_contents("http://localhost/GO1/{$name}/install");
}
