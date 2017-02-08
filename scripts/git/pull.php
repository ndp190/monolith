<?php

require_once __DIR__ . '/../../php/vendor/go1.autoload.php';

return call_user_func(function () {
    $dir = dirname(dirname(__DIR__));
    $projects = require __DIR__ . '/../_projects.php';
    foreach ($projects as $folder => $repos) {
        foreach ($repos as $name => $repo) {
            $branch = ($name == 'quiz') ? '1.x' : 'master';

            $target = "{$dir}/{$folder}/{$name}";
            $cmd = "cd $target && git checkout {$branch} && git pull origin {$branch}";
            echo "$ {$cmd}\n";
            passthru($cmd);
        }
    }
});
