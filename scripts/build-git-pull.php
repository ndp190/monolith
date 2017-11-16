<?php

namespace at\labs;

return function ($pwd, $projects, $custom) {
    $defaultBranch = 'master';

    foreach ($projects as $lang => $services) {
        foreach ($services as $name => $path) {
            $branch = isset($custom['features']['services'][$name]['branch']) ? $custom['features']['services'][$name]['branch'] : $defaultBranch;
            $target = ('golang' === $lang) ? "$pwd/$lang/src/vendor/go1/$name" : "$pwd/$lang/$name";

            if (!is_dir($target)) {
                echo "git clone -q --branch=$branch $path $target\n";
                passthru("git clone -q --branch=$branch $path $target");
            }
        }
    }
};
