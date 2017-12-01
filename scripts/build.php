<?php

namespace go1\monolith;

$cmd = implode(' ', $argv);
$pwd = dirname(__DIR__);
$home = getenv('HOME');

$projects = require __DIR__ . '/_projects.php';
$custom = $pwd . '/build.json';
$custom = is_file($custom) ? json_decode(file_get_contents($custom), true) : [];

!strpos($cmd, '--skip-pull') && call_user_func(require $pwd . '/scripts/build-git-pull.php', $pwd, $projects, $custom);

if (isset($custom['gitlab']['username']) && isset($custom['gitlab']['password'])) {
    echo "docker login registry.code.go1.com.au --username={$custom['gitlab']['username']} --password=*******\n";
    passthru("docker login registry.code.go1.com.au --username={$custom['gitlab']['username']} --password={$custom['gitlab']['password']}");
}
else {
    echo "docker login registry.code.go1.com.au\n";
    passthru('docker login registry.code.go1.com.au');
}

# Build PHP tools, from now we already have PHP libraries for later uses.
!strpos($cmd, '--skip-php') && call_user_func(require $pwd . '/scripts/build-php.php', $pwd, $home, $projects);

$skipBuildWeb = !strpos($cmd, '--skip-web') || in_array('skip-web', $custom['options']);
if (!$skipBuildWeb) {
    call_user_func(require $pwd . '/scripts/build-web.php', $pwd, $home);
}

if (empty($custom)) {
    !strpos($cmd, '--skip-drupal') && call_user_func(require $pwd . '/scripts/build-drupal.php', $pwd, $home);
    !strpos($cmd, '--skip-go') && call_user_func(require $pwd . '/scripts/build-go.php', $pwd, $home, $projects);
}
