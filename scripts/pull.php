<?php

namespace go1\monolith\scripts;

passthru('docker-compose pull');

// Pull images that can't be pull by the command above.
passthru('docker pull mysql:5.7');
passthru('docker pull registry.code.go1.com.au/apiom/apiom-ui:master');
passthru('docker pull registry.code.go1.com.au/web/go1web:master');
passthru('docker pull go1com/php:7-nginx');
passthru('docker pull node:7-alpine');
passthru('docker pull registry.code.go1.com.au/microservices/work:master');
passthru('docker pull registry.code.go1.com.au/microservices/consumer:master');
passthru('docker pull registry.code.go1.com.au/microservices/scormengine:master');
