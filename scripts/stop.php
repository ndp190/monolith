<?php

namespace go1\monolith\scripts;

passthru('docker-compose stop');
passthru('docker-compose -f docker-compose-scorm.yml stop');
