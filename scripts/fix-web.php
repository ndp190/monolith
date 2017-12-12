<?php

namespace go1\monolith\scripts;

return function ($pwd, $domain) {
    $files = [
        $pwd . '/web/ui/app/scripts/constants/monolith.json',
        $pwd . '/web/website/env/monolith.json',
    ];
    foreach ($files as $file) {
        $env = file_get_contents($file);
        $env = str_replace('localhost/GO1', $domain . '/GO1', $env);
        file_put_contents($file, $env);
    }
};
