GO1 monolith
====

> This project is **not yet useful** for community as we are trying to opensource the GO1 LMS.
> Keep your eyes here to have updates.

## 1. Dependencies

- docker (at least 17.09.1-ce on MAC OS) & docker-compose
- git
- php7 & composer
- golang:
    - `composer global require symfony/yaml`
    - glide: `curl https://glide.sh/get | sh`
    - `export PATH="/path/to/monolith/scripts:$PATH"`
- nodejs & npm & bower & grunt
- /etc/hosts
    - `127.0.0.1	localhost staff.local website.local host portal1.go1.local portal2.go1.local`

## 2. Usage

### Build

    php scripts/stop.php
    php scripts/build.php --skip-drupal --skip-go
    php scripts/start.php
    php scripts/install.php

### Rebuild

    php scripts/stop.php
    php scripts/pull.php
    php scripts/git/pull.php --confirm # Add --reset if you want to reset the code
    php scripts/clean.php
    php scripts/build.php --skip-drupal --skip-go --skip-pull
    cd php && rm composer.lock && composer install -v && cd ..
    php scripts/start.php
    php scripts/install.php

### Remove Data

    php scripts/stop.php
    php scripts/clear-data.php
    php scripts/start.php

## 2. Notes

- `php ./scripts/build.php`: Here are some options:
    - `--skip-php`: Don't run composer commands. 
    - `--skip-web`: Don't run npm commands.
    - `--skip-drupal`: Don't build drupal code base.
    - `--skip-go`: Don't build golang code base.
- If you are frontend developers:
    ```
    $ cd web/ui && grunt serve
    $ grunt build
    $ grunt test
    ```
- Some useful links:
    - http://localhost/ — #ui
    - http://localhost:9090/ — #ui live
    - http://localhost/v3/ — #api
    - http://localhost/GO1/user/ — #service
    - http://staff.local — #staff, with some notes:
        - You can use staff@go1.co/root to login.
    - http://localhost:7474/ - #neo4j admin
    - http://localhost:15672/ - rabbitMQ admin
    - http://website.local — #website, to create portal:
        -Click 'Free Trial' -> email 'admin@portal1.go1.local' -> click 'Get Started' to add new portal.
    - http://localhost:9900/minio - #minio (s3) file management.
    - http://portal1.go1.local/ or http://portal1.go1.local:9090 to test issues related to domain.
- If you need to work with scorm engine:
    - `php scripts/start-scorm.php`
- Run test cases without Docker:
    ```
    $ cd php/[MICROSERVICE]
    $ phpunit
    ```
- To avoid PHPStorm to index too much, exclude these directory:
    - .data
    - drupal/gc/test
    - web/ui (if you're not #ui dev)
- To setup xdebug with PHPStorm, please read:
    - [Debug command line](resources/docs/debug-command-line.md)
    - [Debug web](resources/docs/debug-web.md)
- If docker-compose fails to pull image from GO1 private registry, please signing out personal Docker ID because Docker uses your Docker ID to save your account settings.

## 3. Tools

- `php scripts/git/prune.php`
- `php scripts/git/pull.php` — pull master
- `php scripts/git/pull.php --confirm` — pull master with confirmation
- `php scripts/git/generate.php`
- `php scripts/gitlab/build-configuration.php`
- `php scripts/gitlab/deploy/staging.php`
- `php scripts/gitlab/deploy/production.php`
- `php scripts/ecs-ssh.php staging lo-staging` - to configure aws, see [this](http://docs.aws.amazon.com/cli/latest/userguide/cli-chap-getting-started.html)
- `php scripts/migration/util/fix-namespace.php` - fix namespace errors, because not all microservices come with new
  version of util library.
- `php scripts/ecs-ssh.php staging staff-dev` - SSH to #staff-dev ECS.
- Dummy: Generate dummy content for testing.
    1. Make sure the services are up. Ref (4).
    - `docker exec monolith_web_1 bash -c 'php /scripts/dummy/generate.php'`
- Run xdebug on certain request:
    - From Google Chrome > `Copy as CURL`
    - `bash -c "clear && docker exec -it monolith_web_1 sh"`
    - `php /app/scripts/xdebug.php $STRING_COPIED_FROM_GOOGLE_CHROME` 

## 4. TODO

- Install #cron (GO1P-16718)
- Install #realtime (GO1P-xxxxx)
