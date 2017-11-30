<?php

namespace go1\monolith\scripts;

if (PHP_OS === 'Darwin') {
    passthru('docker-sync-stack clean');
}
elseif (PHP_OS === 'Linux' || PHP_OS === 'Windows') {
    passthru('docker-compose down');
    passthru('docker rmi $(docker images | grep monolith | awk "{print \$3}")');
}
