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

## 2. Usage

1. `php ./scripts/build.php`: Build the code base.
    - `--skip-php`: Don't run composer commands. 
    - `--skip-web`: Don't run npm commands.
    - `--skip-drupal`: Don't build drupal code base.
    - `--skip-go`: Don't build golang code base.
    - Build golang only: `php scripts/build.php --skip-php --skip-web --skip-drupal`
    - Build PHP only: `php scripts/build.php --skip-go --skip-web --skip-drupal`
- `php scripts/start.php`, then try some links:
    - http://localhost/ — #ui (Create portal default.go1.local if you see portal not found page)
    - http://localhost/v3/ — #api
    - http://localhost/GO1/user/ — #service
    - http://staff.local — #staff, you NEED config this domain name in `/etc/hosts`.
    - http://localhost:7474/ - #neo4j admin
    - http://localhost/GO1/adminer/ — SQL database management.
    - http://localhost:15672/ - rabbitMQ admin
- `php scripts/install.php` to install database.
- If you are frontend developers:
    - `php scripts/build-web.php`
    - `php scripts/start-ui.php`
    - Then try:
        - http://localhost:9090/ — #ui live
- Run test cases without Docker:
    - `cd php/user/`
    - `phpunit`

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

## 4. TODO

- Install #realtime
- Install #cron
- Fix `grunt build` in ui live
- Fix `grunt test` in ui live
