<?php

namespace go1\monolith;

$ip = shell_exec("ifconfig $(netstat -rn | grep -E '^default|^0.0.0.0' | head -1 | awk '{print \$NF}') | grep 'inet ' | awk '{print \$2}' | grep -Eo '([0-9]*\\.){3}[0-9]*'");
echo "Your ip address is {$ip}\n";

passthru("MONOLITH_MYSQL_IP='{$ip}' docker-compose -f docker-compose-scorm.yml up --force-recreate");
