<?php

namespace at\labs;

return function ($pwd, $pull, $projects) {
    $branches = [
        'quiz'    => '1.x',
        'default' => 'master',
    ];

    foreach ($projects as $lang => $services) {
        foreach ($services as $name => $path) {
            $branch = isset($branches[$name]) ? $branches[$name] : $branches['default'];
            $target = ('golang' === $lang) ? "$pwd/$lang/src/go1/$name" : "$pwd/$lang/$name";

            if (!is_dir($target)) {
                print_r("git clone -q --branch=$branch $path $target\n");
                passthru("git clone -q --branch=$branch $path $target");
            }
            elseif ($pull) {
                print_r("git pull -q origin {$branch}\n");
                passthru("cd $target && git pull -q --single-branch --branch={$branch} origin master && cd $pwd");
            }
        }
    }
};
