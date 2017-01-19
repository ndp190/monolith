<?php

namespace at\labs;

use Symfony\Component\Yaml\Yaml;

function buildGlideYaml(string $pwd, array $projects)
{
    $glide = [
        'import'     => [],
        'testImport' => [],
    ];

    if (!class_exists(Yaml::class)) {
        die("Please install YAML: composer global require symfomy/yaml.\n");
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

    $glide['import'][] = ['package' => 'go1'];
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
    passthru("cd $pwd/golang/src && rm -f glide.lock && GOPATH=$pwd/golang glide install > /dev/null 2>&1");
}

return function (string $pwd, string $home, array $projects) {
    buildGlideYaml($pwd, $projects);
    glideInstall($pwd);
};
