<?php

namespace at\labs;

function buildComposerJson($pwd, $projects, $baseDir = 'php')
{
    $json = json_decode(file_get_contents("$pwd/php/composer.json"), true);
    foreach (array_keys($projects) as $service) {
        if ($baseDir === 'php' && strpos($service, '-') !== false) {
            $namespace = 'go1\\' . str_replace('-', '', ucwords($service, '-')) . '\\';
        }
        else {
            $namespace = "go1\\$service\\";
        }
        $json['autoload']['psr-4'][$namespace] = './' . str_replace(['/php/', '/php/libraries/'], ['/app/', '/libraries/'], $baseDir) . "/$service/";

        if (file_exists("$pwd/$baseDir/$service/composer.json")) {
            $sub = json_decode(file_get_contents("$pwd/$baseDir/{$service}/composer.json"), true);
            if (!empty($sub['require'])) {
                foreach ($sub['require'] as $lib => $version) {
                    if (false === strpos($lib, 'go1/')) {
                        if (!in_array($lib, ['php', 'phpunit/phpunit'])) {
                            $json['require'][$lib] = $version;
                        }
                    }
                }
            }

            if (!empty($sub['repositories'])) {
                foreach ($sub['repositories'] as $name => $info) {
                    $json['repositories'][$name] = $info;
                }
            }
        }

        passthru("mkdir -p $pwd/$baseDir/$service/vendor");
        file_put_contents(
            "$pwd/$baseDir/$service/vendor/autoload.php",
            '<?php' . "\n\n"
            . 'if (is_file("/autoload/autoload.php")) return require_once "/autoload/autoload.php";' . "\n"
            . 'if (is_file(__DIR__ . "/../../vendor/autoload.php")) return require_once __DIR__ . "/../../vendor/go1.autoload.php";'
        );
    }

    ksort($json['autoload']['psr-4']);
    ksort($json['require']);
    $json = json_encode($json, JSON_PRETTY_PRINT);
    $json = str_replace('\/', '/', $json);
    file_put_contents("$pwd/php/composer.json", $json);
}

return function ($pwd, $home, $projects) {
    buildComposerJson($pwd, $projects['php'], 'php');
    buildComposerJson($pwd, $projects['php/libraries'], 'php/libraries');

    passthru("cd $pwd/php && composer install -vvv --no-scripts -vvv");

    // Our #adminer is not yet compatible with #monolith.
    // We try to hack it.
    passthru("rm -rf {$pwd}/php/adminer & mkdir -p {$pwd}/php/adminer/public");
    file_put_contents("{$pwd}/php/adminer/public/index.php", file_get_contents('https://github.com/vrana/adminer/releases/download/v4.2.5/adminer-4.2.5-en.php'));
};
