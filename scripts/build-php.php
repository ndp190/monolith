<?php

namespace at\labs;

use RuntimeException;

function buildComposerJson($pwd, $projects, $baseDir = 'php')
{
    $json = json_decode(file_get_contents("$pwd/php/composer.json"), true);
    foreach (array_keys($projects) as $service) {
        $json['autoload']['psr-4']["go1\\$service\\"] = './' . str_replace(['/php/', '/php/libraries/'], ['/app/', '/libraries/'], $baseDir) . "/$service/";

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
    $docker = "docker run --rm";
    $docker .= " -v $pwd/php/:/app/";
    $docker .= " -v $pwd/.data/cli/:/cli/";
    $docker .= " -v '$home/.ssh/id_rsa:/root/.ssh/id_rsa'";
    $docker .= " -v '$home/.ssh/id_rsa.pub:/root/.ssh/id_rsa.pub'";
    $docker .= " -v $home/.composer/:/root/.composer/";
    $docker .= " -w=/app/ go1com/php:php7";

    buildComposerJson($pwd, $projects['php'], 'php');
    buildComposerJson($pwd, $projects['php/libraries'], 'php/libraries');

    if (!is_file("$pwd/.data/cli/composer.phar")) {
        if (!is_dir("$pwd/.data/cli")) {
            mkdir("$pwd/.data/cli", 0777, true);
        }
        copy('https://getcomposer.org/installer', "$pwd/.data/cli/composer-setup.php");

        passthru("cd $pwd/.data/cli && php composer-setup.php");
    }

    passthru("$docker sh /app/install.sh");
};
