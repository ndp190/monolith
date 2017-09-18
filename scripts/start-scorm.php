<?php

namespace go1\monolith;

$ip = require 'ip.php';

passthru("MONOLITH_HOST_IP='{$ip}' docker-compose -f docker-compose-scorm.yml up --force-recreate");
