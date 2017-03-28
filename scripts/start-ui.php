<?php

namespace go1\monolith;

passthru("docker-compose -f docker-compose-ui.yml up --force-recreate");
