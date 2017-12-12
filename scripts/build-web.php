<?php

namespace go1\monolith\scripts;

return function ($pwd) {
    passthru("cd $pwd/web/ui && npm install");
    passthru("cd $pwd/web/ui && bower install --allow-root");
    passthru("cd $pwd/web/ui && grunt set-env:monolith");
    passthru("cd $pwd/web/ui && grunt build");
    passthru("cd $pwd/web/website && npm install");
    passthru("cd $pwd/web/website && bower install --allow-root");
    passthru("cd $pwd/web/website && grunt set-env:monolith");
    passthru("cd $pwd/web/website && grunt build");
};
