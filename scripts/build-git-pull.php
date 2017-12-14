<?php

namespace go1\monolith\scripts;

return function ($pwd, $projects, $projectsMap, $custom) {
    $defaultBranch = 'master';
    $single = !empty($custom);

    foreach ($projects as $lang => $services) {
        foreach ($services as $name => $path) {
            $customName = isset($projectsMap[$lang][$name]) ? $projectsMap[$lang][$name] : $name;
            $branch = isset($custom['features']['services'][$customName]['branch']) ? $custom['features']['services'][$customName]['branch'] : $defaultBranch;
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
