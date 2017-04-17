<?php

namespace go1\monolith;

$pwd = dirname(__DIR__);

passthru("cd $pwd/web/ui && bower install");
