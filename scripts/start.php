<?php
namespace go1\monolith\scripts;
$pwd       = dirname(__DIR__);
$hasCustom = is_file($pwd . '/build.json');
$ip        = require 'ip.php';
$extraArgs = [];
$domain    = 'host';
$scorm     = '';
$debug     = '';
$https     = '';
if ($hasCustom) {
    $custom = json_decode(file_get_contents($pwd . '/build.json'), true);
    $domain = !empty($custom['features']['domain']) ? $custom['features']['domain'] : $domain;
    if (!empty($custom['rebuild'])) {
        $extraArgs[] = '--build';
    }
    if (!empty($custom['background'])) {
        $extraArgs[] = '-d';
    }
    if (!empty($custom['scorm'])) {
        $scorm = "-f {$pwd}/docker-compose-scorm.yml";
    }
    if (empty($custom['debug'])) {
        $debug = "-f {$pwd}/docker-compose-no-debug.yml";
    }
    if (!empty($custom['https'])) {
        $https = "-f {$pwd}/docker-compose-https.yml";
    }
}

passthru("MONOLITH=1 php {$pwd}/infrastructure/cron/build-cron.php");
passthru("MONOLITH_HOST_IP='{$ip}' ENV_HOSTNAME={$domain} docker-compose -f {$pwd}/docker-compose.yml {$scorm} {$debug} {$https} up --force-recreate " . implode(' ', $extraArgs));
