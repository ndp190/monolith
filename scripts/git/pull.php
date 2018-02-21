<?php

namespace go1\monolith\git;

require_once __DIR__ . '/../../php/vendor/go1.autoload.php';

$cmd = implode(' ', $argv);
$confirm = false !== strpos($cmd, '--confirm');
$reset = false !== strpos($cmd, '--reset');
$multiProcess = false !== strpos($cmd, '--faster');
$pwd = dirname(dirname(__DIR__));
$projects = require __DIR__ . '/../_projects.php';
$projectsMap = require __DIR__ . '/../_projects_map.php';
$custom = $pwd . '/build.json';
$custom = is_file($custom) ? json_decode(file_get_contents($custom), true) : [];
$defaultBranch = 'master';

return call_user_func(function () use ($confirm, $reset, $pwd, $projects, $projectsMap, $custom, $defaultBranch, $multiProcess) {
    $canFork = $multiProcess && function_exists('pcntl_fork');
    $maxProcesses = 4;
    $processes = [];
    foreach ($projects as $lang => $services) {
        foreach ($services as $name => $path) {
            $do = true;
            $customName = isset($projectsMap[$lang][$name]) ? $projectsMap[$lang][$name] : $name;
            $branch = isset($custom['features']['services'][$customName]['branch']) ? $custom['features']['services'][$customName]['branch'] : $defaultBranch;
            $target = "$pwd/$lang/$name";

            if ($confirm) {
                echo "Do you want to pull branch '$branch' of $lang/$name? [y/n]";
                $do = 'y' === trim(fgets(STDIN));
            }

            if ($do && is_dir($target)) {
                $cmd = $reset
                    ? "cd $target && git reset --hard && git checkout $branch && git pull origin $branch"
                    : "cd $target && git checkout $branch && git pull origin $branch";

                if ($canFork && $maxProcesses > 1) {
                    if (count($processes) >= $maxProcesses) {
                        $result = pcntl_wait($processStatus);
                        unset($processes[$result]);
                    }
                    switch ($pid = pcntl_fork()) {
                        case 0:
                            echo "$cmd\n";
                            passthru($cmd);
                            exit(0);
                        case -1:
                            break;
                        default:
                            $processes[$pid] = true;
                            continue 2;
                    }
                }

                echo "$cmd\n";
                passthru($cmd);
            }
        }
    }

    if ($canFork) {
        foreach (array_keys($processes) as $processId) {
            pcntl_waitpid($processId, $processStatus);
        }
    }
});
