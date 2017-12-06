<?php

namespace go1\monolith\scripts;

$pwd       = dirname(__DIR__);
$hasCustom = is_file($pwd . '/build.json');

@mkdir("$pwd/.data");
@mkdir("$pwd/.data/nginx");
@mkdir("$pwd/.data/nginx/sites-available");
@unlink("$pwd/.data/nginx/sites-available/default.conf");
@copy("$pwd/.data/nginx/app.conf", "$pwd/.data/nginx/sites-available/default.conf");

$ip        = require 'ip.php';
$ip        = str_replace("\n", '', $ip);
$extraArgs = $hasCustom ? ' -d' : '';
$domain    = 'localhost';
if ($hasCustom) {
    $custom = json_decode(file_get_contents($pwd . '/build.json'), true);
    $domain = !empty($custom['features']['domain']) ? $custom['features']['domain'] : $domain;
}

if (PHP_OS === 'Darwin') {
    passthru('docker-sync start');
    passthru("MONOLITH_HOST_IP='{$ip}' ENV_HOSTNAME={$domain} docker-compose -f docker-compose.yml -f docker-compose-dev.yml up --force-recreate {$extraArgs}");
}
elseif (PHP_OS === 'Linux' || PHP_OS === 'Windows') {
    passthru("MONOLITH_HOST_IP='{$ip}' ENV_HOSTNAME={$domain} docker-compose -f {$pwd}/docker-compose.yml up --force-recreate {$extraArgs}");
}
