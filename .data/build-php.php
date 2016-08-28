<?php

namespace at\labs;

return function ($pwd, $projects) {
  $composer = json_decode(file_get_contents("$pwd/php/composer.json"), TRUE);
  foreach (array_keys($projects['php']) as $service) {
    $composer['autoload']['psr-4']["go1\\$service\\"] = "/app/$service/";
    if (file_exists("$pwd/php/$service/composer.json")) {
      $sub = json_decode(file_get_contents("$pwd/php/{$service}/composer.json"), TRUE);
      if (!empty($sub['require'])) {
        foreach ($sub['require'] as $lib => $version) {
          $composer['require'][$lib] = $version;
        }
      }
    }
  }

  ksort($composer['autoload']['psr-4']);
  ksort($composer['require']);
  $composer = json_encode($composer, JSON_PRETTY_PRINT);
  $composer = str_replace('\/', '/', $composer);
  file_put_contents("$pwd/php/composer.json", $composer);

  passthru("cd $pwd/php && composer install -vvv && cd $pwd");
  passthru("docker run --rm -v $pwd/php/:/app/ go1com/php:php7 sh /app/install.sh");
};
