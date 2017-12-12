<?php

namespace go1\monolith\git;

require_once __DIR__ . '/../../php/vendor/go1.autoload.php';

$cmd = implode(' ', $argv);
$confirm = false !== strpos($cmd, '--confirm') ? true : false;
$reset = false !== strpos($cmd, '--reset') ? true : false;

$pwd = dirname(dirname(__DIR__));
$projects = require __DIR__ . '/../_projects.php';

$custom = $pwd . '/build.json';
$custom = is_file($custom) ? json_decode(file_get_contents($custom), true) : [];

$defaultBranch = 'master';

return call_user_func(function () use ($confirm, $reset, $pwd, $projects, $custom, $defaultBranch) {
    foreach ($projects as $lang => $services) {
        foreach ($services as $name => $path) {
            $do = true;
            $branch = isset($custom['features']['services'][$name]['branch']) ? $custom['features']['services'][$name]['branch'] : $defaultBranch;
            $target = "$pwd/$lang/$name";

            if ($confirm) {
                echo "Do you want to pull {$name}/{$branch}? [y/n]";
                $do = 'y' === trim(fgets(STDIN));
            }

            if ($do && is_dir($target)) {
                $cmd = $reset
                    ? "cd $target && git reset --hard && git checkout $branch && git pull origin $branch"
                    : "cd $target && git checkout $branch && git pull origin $branch";

                echo "$cmd\n";
                passthru($cmd);
            }
        }
    }
});
