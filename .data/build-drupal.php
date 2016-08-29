<?php

namespace at\labs;

return function ($pwd, $home) {
  $docker = "docker run --rm"; # -it
  $docker .= " -v $pwd/drupal/:/app/";
  $docker .= " -v $pwd/.data/cli/:/cli/";
  $docker .= " -v $pwd/.data/drupal/:/drupal/";
  $docker .= " -v '$home/.ssh/id_rsa:/root/.ssh/id_rsa'";
  $docker .= " -v '$pwd/.data/.ssh/config:/root/.ssh/config'";
  $docker .= " -w=/drupal/ go1com/php:php7";
  $php = "$docker php";
  $drush = "$php /cli/drush";
  $composer = "$php /cli/composer";

  if (!file_exists("$pwd/.data/cli/drush")) {
    passthru("mkdir $pwd/.data/cli");
    passthru("wget https://s3.amazonaws.com/files.drush.org/drush.phar -O $pwd/.data/cli/drush");
    passthru("chmod +x $pwd/.data/cli/drush");
  }

  // Create Drupal directory
  !is_dir("$pwd/.data/drupal") && passthru("mkdir $pwd/.data/drupal");

  // Build Drupal
  passthru("$drush make /app/gc/build/build.make -y -vvv");
  passthru("cd /drupal/profiles/gocatalyze/vendor/ && $composer --ignore-platform-reqs install");
};
