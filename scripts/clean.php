<?php

namespace go1\monolith\scripts;

passthru('docker-compose down');
passthru('docker rmi $(docker images | grep monolith | awk "{print \$3}")');
passthru('docker images -q --filter "dangling=true" | xargs docker rmi');
passthru('docker volume rm $(docker volume ls -f dangling=true -q)');
