<?php

namespace go1\monolith;

$cmd = implode(' ', $argv);
$pwd = dirname(__DIR__);
$home = getenv('HOME');

if (is_file("$home/.composer/vendor/autoload.php")) {
    require_once "$home/.composer/vendor/autoload.php";
}

$projects = require __DIR__ . '/_projects.php';
$pull = strpos($cmd, '--pull') ? true : false;
call_user_func(require $pwd . '/scripts/build-git-pull.php', $pwd, $pull, $projects);

!strpos($cmd, '--skip-php') && call_user_func(require $pwd . '/scripts/build-php.php', $pwd, $home, $projects);
!strpos($cmd, '--skip-drupal') && call_user_func(require $pwd . '/scripts/build-drupal.php', $pwd, $home);
!strpos($cmd, '--skip-web') && call_user_func(require $pwd . '/scripts/build-web.php', $pwd, $home);
!strpos($cmd, '--skip-tools') && call_user_func(require $pwd . '/scripts/build-tools.php', $pwd);
!strpos($cmd, '--skip-go') && call_user_func(require $pwd . '/scripts/build-go.php', $pwd, $home, $projects);
!strpos($cmd, '--skip-docker-compose') && call_user_func(require $pwd . '/scripts/build-docker-compose.php', $pwd, $home, $projects);
