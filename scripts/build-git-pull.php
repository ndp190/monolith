<?php

namespace go1\monolith\scripts;

return function ($pwd, $projects, $custom) {
    $defaultBranch = 'master';
    $single = !empty($custom);

    foreach ($projects as $lang => $services) {
        foreach ($services as $name => $path) {
            $branch = isset($custom['features']['services'][$name]['branch']) ? $custom['features']['services'][$name]['branch'] : $defaultBranch;
            $target = "$pwd/$lang/$name";

            if (!is_dir($target)) {
                if ($single) {
                    $cmd = "git clone -q --single-branch --branch=$branch $path $target";
                }
                else {
                    $cmd = "git clone -q --branch=$branch $path $target";
                }
            }
            else {
                $cmd = "cd $target; git pull origin $branch; cd - >/dev/null";
            }
            echo "$cmd\n";
            passthru($cmd);
        }
    }
};
