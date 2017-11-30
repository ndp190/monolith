<?php

namespace go1\monolith\scripts;

$ip = require 'ip.php';

if (PHP_OS === 'Darwin') {
    passthru('docker-sync start');
    passthru("MONOLITH_HOST_IP='{$ip}' docker-compose -f docker-compose-scorm.yml -f docker-compose-scorm-dev.yml up --force-recreate");
}
elseif (PHP_OS === 'Linux' || PHP_OS === 'Windows') {
    passthru("MONOLITH_HOST_IP='{$ip}' docker-compose -f docker-compose-scorm.yml up --force-recreate");
}
