<?php

namespace go1\monolith\scripts;

$pwd = dirname(__DIR__);
$custom = is_file($pwd . '/build.json');

@mkdir("$pwd/.data");
@mkdir("$pwd/.data/nginx");
@mkdir("$pwd/.data/nginx/sites-available");
@unlink("$pwd/.data/nginx/sites-available/default.conf");
@copy("$pwd/.data/nginx/app.conf", "$pwd/.data/nginx/sites-available/default.conf");

$ip = require 'ip.php';
$ip = str_replace("\n", '', $ip);
$custom = $custom ? ' -d' : '';

if (PHP_OS === 'Darwin') {
    passthru('docker-sync start');
    passthru("MONOLITH_HOST_IP='{$ip}' docker-compose -f docker-compose.yml -f docker-compose-dev.yml up --force-recreate {$custom}");
}
elseif (PHP_OS === 'Linux' || PHP_OS === 'Windows') {
    passthru("MONOLITH_HOST_IP='{$ip}' docker-compose -f {$pwd}/docker-compose.yml up --force-recreate {$custom}");
}
