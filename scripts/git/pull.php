<?php

require_once __DIR__ . '/../../php/vendor/go1.autoload.php';

return call_user_func(function () {
    $branches = [
        'quiz'    => '1.x',
        'default' => 'master',
    ];

    $dir = dirname(dirname(__DIR__));
    $projects = require __DIR__ . '/../_projects.php';
    foreach ($projects['php'] as $name => $path) {
        $target = "{$dir}/php/{$name}";
        $branch = isset($branches[$name]) ? $branches[$name] : $branches['default'];
        $cmd = "cd $target && git pull -q --branch=$branch origin master";
        echo "$ {$cmd}\n";
        passthru($cmd);
    }
});
