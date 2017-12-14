<?php

namespace go1\monolith;

$cmd = implode(' ', $argv);
$pwd = dirname(__DIR__);
$home = getenv('HOME');

$projects = require __DIR__ . '/_projects.php';
$projectsMap = require __DIR__ . '/../_projects_map.php';
$custom = $pwd . '/build.json';
$custom = is_file($custom) ? json_decode(file_get_contents($custom), true) : [];
$customOptions = isset($custom['options']) && is_array($custom['options']) ? $custom['options'] : [];

false === strpos($cmd, '--skip-pull') && call_user_func(require $pwd . '/scripts/build-git-pull.php', $pwd, $projects, $projectsMap, $custom);

if (isset($custom['gitlab']['username']) && isset($custom['gitlab']['password'])) {
    echo "docker login registry.code.go1.com.au --username={$custom['gitlab']['username']} --password=*******\n";
    passthru("docker login registry.code.go1.com.au --username={$custom['gitlab']['username']} --password={$custom['gitlab']['password']}");
}
else {
    echo "docker login registry.code.go1.com.au\n";
    passthru('docker login registry.code.go1.com.au');
}

# Build PHP tools, from now we already have PHP libraries for later uses.
false === strpos($cmd, '--skip-php') && call_user_func(require $pwd . '/scripts/build-php.php', $pwd, $home, $projects);

call_user_func(require $pwd . '/scripts/build-web.php', $pwd);

if (empty($custom)) {
    false === strpos($cmd, '--skip-drupal') && call_user_func(require $pwd . '/scripts/build-drupal.php', $pwd, $home);
    false === strpos($cmd, '--skip-go') && call_user_func(require $pwd . '/scripts/build-go.php', $pwd, $home, $projects);
}
