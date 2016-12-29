<?php

require_once __DIR__ . '/../../php/vendor/go1.autoload.php';

return call_user_func(function () {
    $dir = dirname(dirname(__DIR__));
    $projects = require __DIR__ . '/../_projects.php';
    foreach ($projects['php'] as $name => $path) {
        $target = "{$dir}/php/{$name}";
        $cmd = "cd $target && git fetch origin --prune";
        echo "$ {$cmd}\n";

        passthru($cmd);
    }
});
