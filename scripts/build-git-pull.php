<?php

namespace go1\monolith\scripts;

return function ($pwd, $projects, $custom) {
    $defaultBranch = 'master';
    $single = !empty($custom);

    foreach ($projects as $lang => $services) {
        foreach ($services as $name => $path) {
            $branch = isset($custom['features']['services'][$name]['branch']) ? $custom['features']['services'][$name]['branch'] : $defaultBranch;
            $target = ('golang' === $lang) ? "$pwd/$lang/src/vendor/go1/$name" : "$pwd/$lang/$name";

            if (!is_dir($target)) {
                if ($single) {
                    echo "git clone -q --single-branch --branch=$branch $path $target\n";
                    passthru("git clone -q --single-branch --branch=$branch $path $target");
                }
                else {
                    echo "git clone -q --branch=$branch $path $target\n";
                    passthru("git clone -q --branch=$branch $path $target");
                }
            }
        }
    }
};
