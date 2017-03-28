<?php

namespace at\labs;

use Symfony\Component\Yaml\Yaml;

function buildGlideYaml(string $pwd, array $projects)
{
    $glide = [
        'import'     => [],
        // 'testImport' => [],
    ];

    if (!class_exists(Yaml::class)) {
        die("Please install YAML: composer global require symfony/yaml.\n");
    }

    foreach ($projects['golang'] as $project => $repository) {
        if (is_file("$pwd/golang/src/vendor/go1/$project/glide.yaml")) {
            $arr = Yaml::parse(file_get_contents("$pwd/golang/src/vendor/go1/$project/glide.yaml"));

            foreach (['import', 'testImport'] as $type) {
                if (isset($arr[$type])) {
                    foreach ($arr[$type] as &$package) {
                        $found = false;
                        foreach ($glide[$type] as $import) {
                            if ($package['package'] == $import['package']) {
                                $found = true;
                            }
                        }

                        if (!$found) {
                            $glide[$type][] = $package;
                        }
                    }
                }
            }
        }
    }

    $glide['import'][]['package'] = 'code.go1.com.au/go1/goutil';
    $glide['import'][]['package'] = 'code.go1.com.au/go1/api.v3';
    $glide['import'][]['package'] = 'code.go1.com.au/microservices/batch-go';
    $glide['import'][]['package'] = 'code.go1.com.au/microservices/consumer';
    $glide['import'][]['package'] = 'code.go1.com.au/microservices/work';

    $goDir = "$pwd/golang/src";
    if (!is_dir($goDir)) {
        mkdir($goDir, 0777, true);
    }
    file_put_contents("$pwd/golang/src/glide.yaml", Yaml::dump($glide));
}

function glideInstall($pwd)
{
    $output = [];
    exec('which glide', $output);
    if (!$output) {
        die("Please install glide: curl https://glide.sh/get | sh\n");
    }

    echo "GOPATH=$pwd/golang glide install\n";
    passthru("git config --global url.\"git@code.go1.com.au:\".insteadOf \"https://code.go1.com.au/\"");
    passthru("cd $pwd/golang/src && rm -f glide.lock && GOPATH=$pwd/golang glide install > /dev/null");
}

return function (string $pwd, string $home, array $projects) {
    buildGlideYaml($pwd, $projects);
    glideInstall($pwd);
};
