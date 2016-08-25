<?php

$pwd = __DIR__;

passthru("mkdir $pwd/.data/nginx/conf.d");
passthru("touch $pwd/.data/nginx/conf.d/default.conf");
passthru("cp $pwd/.data/nginx/app.conf $pwd/.data/nginx/conf.d/app.conf");
passthru("docker-compose up --force-recreate");
