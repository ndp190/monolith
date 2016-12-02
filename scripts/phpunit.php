<?php

namespace at\labs;

$pwd = dirname(__DIR__);

// Install PHPUnit if it's missing
if (!is_file("$pwd/.data/cli/phpunit.phar")) {
    copy('https://phar.phpunit.de/phpunit.phar', "$pwd/.data/cli/phpunit.phar");
    passthru("chmod +x $pwd/.data/cli/phpunit.phar");
}

$phpunit = "php $pwd/.data/cli/phpunit.phar";

if (!empty($argv[1])) {
    $path = $argv[1];
    $path = is_file("$pwd/$path") ? "$pwd/$path" : $path;
    $chunks = explode('/', str_replace("$pwd/", '', $path));
    if (!empty($chunks[1])) {
        $service = $chunks[1];

        switch ($service) {
            default:
                $phpunit .= " --configuration=$pwd/php/$service/phpunit.xml.dist";
                $phpunit .= " --bootstrap=$pwd/php/vendor/go1.autoload.php";
                break;
        }
    }
}

passthru($cmd = implode(' ', [$phpunit] + $argv));
