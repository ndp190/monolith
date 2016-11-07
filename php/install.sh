#!/usr/bin/env sh

php /cli/composer.phar install --no-scripts -vvv
php /cli/composer.phar dumpautoload
# rm -rf composer-setup.php composer.lock
