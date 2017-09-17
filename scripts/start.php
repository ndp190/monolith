<?php

namespace go1\monolith;

$pwd = dirname(__DIR__);

@mkdir("$pwd/.data");
@mkdir("$pwd/.data/nginx");
@mkdir("$pwd/.data/nginx/sites-available");
@unlink("$pwd/.data/nginx/sites-available/default.conf");
@copy("$pwd/.data/nginx/app.conf", "$pwd/.data/nginx/sites-available/default.conf");

$ip = require 'ip.php';

passthru("MONOLITH_HOST_IP='{$ip}' docker-compose up --force-recreate");
