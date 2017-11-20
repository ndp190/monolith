<?php

namespace go1\monolith;

$pwd = dirname(__DIR__);
$custom = is_file($pwd . '/build.json');

@mkdir("$pwd/.data");
@mkdir("$pwd/.data/nginx");
@mkdir("$pwd/.data/nginx/sites-available");
@unlink("$pwd/.data/nginx/sites-available/default.conf");
@copy("$pwd/.data/nginx/app.conf", "$pwd/.data/nginx/sites-available/default.conf");

$ip = require 'ip.php';
$cmd = "MONOLITH_HOST_IP='{$ip}' docker-compose up --force-recreate";
$cmd .= $custom ? ' -d' : '';

passthru($cmd);
