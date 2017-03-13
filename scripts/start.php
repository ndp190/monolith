<?php

$pwd = dirname(__DIR__);
passthru("mkdir -p $pwd/.data/nginx/sites-available");
passthru("touch $pwd/.data/nginx/sites-available/default.conf");
passthru("cp $pwd/.data/nginx/app.conf $pwd/.data/nginx/sites-available/default.conf");
passthru("docker-compose up -d --force-recreate");

# Make sure database 'go1_dev' is created.
$conn = new mysqli('127.0.0.1', 'root', 'root');
if ($conn->select_db('go1_dev')) {
    echo "Database 'go1_dev' exists\n";
}
else {
    echo "Database 'go1_dev' does not exists, creating...\n";
    if ($conn->query('CREATE DATABASE go1_dev') === TRUE) {
        echo "Database 'go1_dev' created successfully\n";
    }
    else {
        echo "Error creating database 'go1_dev': " . $conn->error . "\n";
    }
}
$conn->close();

# Make sure we have database for all services
$projects = require __DIR__ . '/_projects.php';
foreach (array_keys($projects['php']) as $name) {
    echo "[install] GET http://localhost/GO1/{$name}/install\n";
    @file_get_contents("http://localhost/GO1/{$name}/install");
}
