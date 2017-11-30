<?php

namespace go1\monolith\scripts;

# Remove containers
passthru('docker rm $(docker ps -aq --filter name=monolith)');

# Remove images
passthru('docker rmi $(docker images | grep monolith | awk "{print \$3}")');
