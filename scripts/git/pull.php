<?php

namespace go1\monolith\git;

require_once __DIR__ . '/../../php/vendor/go1.autoload.php';

$cmd = implode(' ', $argv);
$confirm = strpos($cmd, '--confirm') ? true : false;
$reset = strpos($cmd, '--reset') ? true : false;

return call_user_func(function () use ($cmd, $confirm, $reset) {
    $dir = dirname(dirname(__DIR__));
    $projects = require __DIR__ . '/../_projects.php';
    foreach ($projects as $folder => $repositories) {
        foreach ($repositories as $name => $repo) {
            $do = true;
            $branch = ($name == 'quiz') ? '1.x' : 'master';
            $target = "{$dir}/{$folder}/{$name}";

            if ($confirm) {
                echo "Do you want to pull {$name}/{$branch}? [y/n]";
                $do = 'y' === trim(fgets(STDIN));
            }

            if ($do) {
                $cmd = $reset
                    ? "cd $target && git reset --hard && git checkout {$branch} && git pull origin {$branch}"
                    : "cd $target && git checkout {$branch} && git pull origin {$branch}";

                echo "$cmd\n";
                passthru($cmd);
            }
        }
    }

    // Update Docker images
    passthru('docker pull registry.code.go1.com.au/apiom/apiom-ui:master');
    passthru('docker pull go1com/php:7-nginx');
    passthru('docker pull node:7-alpine');
    passthru('docker pull registry.code.go1.com.au/microservices/work:master');
    passthru('docker pull registry.code.go1.com.au/microservices/consumer:master');
});
