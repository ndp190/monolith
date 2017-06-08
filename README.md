GO1 monolith
====

> This project is **not yet useful** for community as we are trying to opensource the GO1 LMS.
> Keep your eyes here to have updates.

## 1. Dependencies

- git
- php7
    - composer
- golang:
    - `composer global require symfony/yaml`
    - glide: `curl https://glide.sh/get | sh`
    - `export PATH="/path/to/monolith/scripts:$PATH"`
- nodejs if you are frontend developers.
    - npm
    - bower
    - grunt

## 2. Usage

- `php ./scripts/build.php`: Build the code base.
    - `--skip-php`: Don't run composer commands. 
    - `--skip-web`: Don't run npm commands.
    - `--skip-drupal`: Don't build drupal code base.
    - `--skip-go`: Don't build golang code base.
    - Build golang only: `php scripts/build.php --skip-php --skip-web --skip-drupal`
    - Build PHP only: `php scripts/build.php --skip-go --skip-web --skip-drupal`
- `php scripts/start.php`, then try some links:
    - http://localhost/ — #ui
    - http://localhost/v3/ — #api
    - http://localhost/GO1/user/ — #service
    - http://staff.local — #staff, with some notes:
        - You NEED config this domain name in `/etc/hosts`.
        - You can use staff@local/root to login.
    - http://localhost:7474/ - #neo4j admin
    - http://localhost/GO1/adminer/ — SQL database management.
    - http://localhost:15672/ - rabbitMQ admin
- `php scripts/install.php` to install database.
- If you are frontend developers:
    - `php scripts/build-web.php`
    - `cd web/ui && grunt serve`
    - Then try:
        - http://localhost:9090/ — #ui live
    - `grunt build`
    - `grunt test`
- If you need to work with interactive li:
    - `php scripts/start-scorm.php`
- Run test cases without Docker:
    - `cd php/user/`
    - `phpunit`
- To rebuild:
    - `docker-compose pull`
    - `php scripts/git/pull.php --confirm`
    - `php scripts/clean.php`
    - `php scripts/build.php --skip-web --skip-drupal --skip-go`
    - `php scripts/start.php`

To avoid PHPStorm to index too much, exclude these directory:

- .data
- drupal/gc/test
- php/adminer
- web/ui (if you're not #ui dev)

## 3. Tools

- `php ./git/prune.php`
- `php ./git/pull.php` — pull master
- `php ./git/pull.php --confirm` — pull master with confirmation 
- `php ./git/generate.php`
- `php ./gitlab/build-configuration.php`
- `php ./gitlab/deploy/staging.php`
- `php ./gitlab/deploy/production.php`
- Dummy: Generate dummy content for testing.
    1. Make sure the services are up. Ref (4).
    - `docker exec monolith_web_1 bash -c 'php /scripts/dummy/generate.php'`
- Run xdebug on certain request:
    - From Google Chrome > `Copy as CURL`
    - `bash -c "clear && docker exec -it monolith_web_1 sh"`
    - `php /app/scripts/xdebug.php $STRING_COPIED_FROM_GOOGLE_CHROME` 

## 4. TODO

- Install #realtime
- Install #cron
