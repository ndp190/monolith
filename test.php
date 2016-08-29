#!/usr/bin/env php
<?php

namespace at\labs;

$pwd = __DIR__;

// Install PHPUnit if it's missing
if (!is_file("$pwd/.data/cli/phpunit.phar")) {
    copy('https://phar.phpunit.de/phpunit.phar', "$pwd/.data/cli/phpunit.phar");
    passthru("chmod +x $pwd/.data/cli/phpunit.phar");
}

$docker = "docker run --rm";
$docker .= " -v $pwd/php/:/app/";
$docker .= " -v $pwd/drupal/:/drop/";
$docker .= " -v $pwd/.data/drupal/:/drupal/";
$docker .= " -v $pwd/.data/cli/:/cli/";
$docker .= " -w=/app/ go1com/php:php7";
$phpunit = "$docker php /cli/phpunit.phar";

if (!empty($argv[1])) {
    $path = $argv[1];
    $path = is_file("$pwd/$path") ? "$pwd/$path" : $path;
    $chunks = explode('/', str_replace("$pwd/", '', $path));
    if (!empty($chunks[1])) {
        $service = $chunks[1];

        switch ($service) {
            case 'gc':
                $phpunit .= " --configuration=/drop/gc/phpunit.xml.dist";
                break;

            default:
                $phpunit .= " --configuration=/app/$service/phpunit.xml.dist";
                break;
        }
    }
}

$cmd = implode(' ', $argv);
$cmd = preg_replace('/\d\d+/', ' ', $cmd);
$cmd = preg_replace('/test\.php (.+)$/', "$phpunit /app/$1", $cmd);
$cmd = str_replace('/app/php/', '/app/', $cmd);
$cmd = str_replace('/app/drupal/', '/drop/', $cmd);
$cmd = ltrim($cmd, './');

# echo "$cmd\n";

passthru("$cmd");
