<?php

namespace go1\monolith;

$pwd = dirname(__DIR__);

passthru("cd $pwd/web/report-component && npm install");
passthru("cd $pwd/web/report-component && grunt install");
passthru("cd $pwd/web/report-component && grunt build");
passthru("cd $pwd/web/report-component && grunt generate-doc");
passthru("cd $pwd/web/report-component/build && bash mapping.sh");
passthru("cd $pwd/web/report-component/build && bash import.sh");
