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

            if (!is_dir("$pwd/$lang/$name")) {
                print_r("git clone -q --branch={$branch} $path $pwd/$lang/$name\n");
                passthru("git clone -q --branch={$branch} $path $pwd/$lang/$name");
            }
            elseif ($pull) {
                print_r("git pull -q origin {$branch}\n");
                passthru("cd $pwd/$lang/$name && git pull -q --single-branch --branch={$branch} origin master && cd $pwd");
            }
        }
    }
};
