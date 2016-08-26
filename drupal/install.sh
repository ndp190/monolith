#!/usr/bin/env sh

# Install composer & drush
# ---------------------
cd /app
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('SHA384', 'composer-setup.php') === 'e115a8dc7871f15d853148a7fbac7da27d6c0030b848d9b3dc09e2a0388afed865e6a3d6b3c0fad45c48e2b5fc1196ae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php composer.phar global require drush/drush:^6.2.0

# Build drupal code base
# ---------------------
cd /drupal
~/.composer/vendor/bin/drush make /app/gc/build/build.make -y

# rm -rf composer-setup.php composer.phar composer.lock
