<?php

namespace go1\monolith\scripts;

$pwd       = dirname(__DIR__);
$hasCustom = is_file($pwd . '/build.json');

@mkdir("$pwd/.data/nginx/sites-available", 0777, true);
@unlink("$pwd/.data/nginx/sites-available/default.conf");
@copy("$pwd/.data/nginx/app.conf", "$pwd/.data/nginx/sites-available/default.conf");

$ip        = require 'ip.php';
$extraArgs = $hasCustom ? ' -d --build' : '';
$domain    = 'host';
if ($hasCustom) {
    $custom = json_decode(file_get_contents($pwd . '/build.json'), true);
    $domain = !empty($custom['features']['domain']) ? $custom['features']['domain'] : $domain;
}

$cmd = implode(' ', $argv);
$scorm = false !== strpos($cmd, '--with-scorm') ? "-f {$pwd}/docker-compose-scorm.yml" : '';

passthru("php {$pwd}/infrastructure/cron/build-cron.php");
passthru("MONOLITH_HOST_IP='{$ip}' ENV_HOSTNAME={$domain} docker-compose -f {$pwd}/docker-compose.yml {$scorm} up --force-recreate {$extraArgs}");
