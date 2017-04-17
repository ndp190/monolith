<?php

namespace go1\monolith;

$pwd = dirname(__DIR__);

 -passthru("cd $pwd/web/ui && npm install");
 -passthru("cd $pwd/web/ui && bower install");
 -passthru("cd $pwd/web/ui && grunt set-env:monolith");
 -passthru("cd $pwd/web/ui && grunt build");
