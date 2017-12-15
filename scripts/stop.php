<?php

namespace go1\monolith\scripts;

passthru('docker-compose -f docker-compose.yml -f docker-compose-scorm.yml stop');
