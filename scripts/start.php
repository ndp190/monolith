<?php

namespace go1\monolith;

$pwd = dirname(__DIR__);
passthru("mkdir -p $pwd/.data/nginx/sites-available");
passthru("touch $pwd/.data/nginx/sites-available/default.conf");
passthru("cp $pwd/.data/nginx/app.conf $pwd/.data/nginx/sites-available/default.conf");
passthru("docker-compose up --force-recreate");
