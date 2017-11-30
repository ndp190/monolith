<?php

namespace go1\monolith\scripts;

if (PHP_OS === 'Darwin') {
    passthru('docker-compose stop');
    passthru('docker-sync stop');
}
elseif (PHP_OS === 'Linux' || PHP_OS === 'Windows') {
    passthru('docker-compose stop');
}
