<?php

namespace at\labs;

return function ($pwd, $pull, $prune, $projects) {
    $branches = [
        'quiz'    => '1.x',
        'default' => 'master',
    ];

    foreach ($projects as $lang => $services) {
        foreach ($services as $name => $path) {
            $branch = isset($branches[$name]) ? $branches[$name] : $branches['default'];
            $target = ('golang' === $lang) ? "$pwd/$lang/src/vendor/go1/$name" : "$pwd/$lang/$name";

            if (!is_dir($target)) {
                print_r("git clone -q --branch=$branch $path $target\n");
                passthru("git clone -q --branch=$branch $path $target");
            }
            else {
                if ($pull) {
                    print_r("git pull -q origin {$branch}\n");
                    passthru("cd $target && git pull -q --single-branch --branch={$branch} origin master && cd $pwd");
                }

                if ($prune) {
                    print_r("[$lang/$name]$ git fetch origin --prune\n");
                    passthru("cd $target && git fetch origin --prune && cd $pwd");
                }
            }
        }
    }
};
