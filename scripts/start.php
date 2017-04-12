<?php

namespace go1\monolith;

$pwd = dirname(__DIR__);

@mkdir("$pwd/.data");
@mkdir("$pwd/.data/nginx");
@mkdir("$pwd/.data/nginx/sites-available");
@unlink("$pwd/.data/nginx/sites-available/default.conf");
@copy("$pwd/.data/nginx/app.conf", "$pwd/.data/nginx/sites-available/default.conf");

$ip = shell_exec("ifconfig $(netstat -rn | grep -E '^default|^0.0.0.0' | head -1 | awk '{print \$NF}') | grep 'inet ' | awk '{print \$2}' | grep -Eo '([0-9]*\\.){3}[0-9]*'");
echo "Your ip address is {$ip}\n";

passthru("MONOLITH_XDEBUG_IP='{$ip}' docker-compose up --force-recreate");
