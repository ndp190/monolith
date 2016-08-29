#!/usr/bin/env sh

php /cli/composer.phar install
php /cli/composer.phar dumpautoload
rm -rf composer-setup.php composer.lock
