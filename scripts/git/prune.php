<?php

namespace go1\monolith\git;

require_once __DIR__ . '/../../php/vendor/go1.autoload.php';

function prune(string $target)
{
    $cmd = "cd $target && git fetch origin --prune";
    echo "$ {$cmd}\n";
    passthru($cmd);
}

return call_user_func(function () {
    prune($dir = dirname(dirname(__DIR__)));

    $projects = require __DIR__ . '/../_projects.php';
    foreach ($projects['php'] as $name => $path) {
        prune("{$dir}/php/{$name}");
    }
});
