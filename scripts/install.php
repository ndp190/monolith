<?php

namespace go1\monolith;

use mysqli;

# Make sure database 'go1_dev' is created.
$db = new mysqli('127.0.0.1', 'root', 'root');
if (!$db->select_db('go1_dev')) {
    if (true !== $db->query('CREATE DATABASE go1_dev')) {
        die("Failed to create 'go1_dev': {$db->error}\n");
    }
}
# Make sure default portal 'default.go1.local' is created.
if ($db->select_db('go1_dev')) {
    if (empty($db->query("SELECT id FROM gc_instance WHERE title = 'default.go1.local'"))) {
        $data = '{"author":"admin@default.go1.local","configuration":{"is_virtual":1,"user_invite":1,"send_welcome_email":1},"features":{"marketplace":true,"user_invite":true,"auth0":false},"user_plan":{"license":10,"price":3620,"product":"marketplace"}}';
        $now = time();
        $sql = "INSERT INTO gc_instance (title, status, is_primary, version, data, timestamp, created)
            VALUES ('default.go1.local', 1, 1, 'v3.0.0', '{$data}', {$now}, {$now})";
        if (true !== $db->query($sql)) {
            die("Failed to insert 'default.go1.local': {$db->error}\n");
        }
    }
}
$db->close();

# Make sure we have database for all services
$projects = require __DIR__ . '/_projects.php';
foreach (array_keys($projects['php']) as $name) {
    echo "[install] GET http://localhost/GO1/{$name}/install\n";
    @file_get_contents("http://localhost/GO1/{$name}/install");
}
